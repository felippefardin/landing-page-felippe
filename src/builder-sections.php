<?php
declare(strict_types=1);

function sb_section_labels(): array {
    return ['about'=>'Sobre','services'=>'Serviços','benefits'=>'Benefícios','portfolio'=>'Portfólio','team'=>'Equipe','testimonials'=>'Depoimentos','faq'=>'Perguntas frequentes','contact'=>'Contato'];
}
function sb_extra_fields(): array {
    $fields=['seo_title'=>['Título para buscadores',120], 'seo_description'=>['Descrição para buscadores e compartilhamento',300], 'site_url'=>['Endereço definitivo do site (https://)',250], 'facebook'=>['Facebook (https://)',250], 'linkedin'=>['LinkedIn (https://)',250], 'youtube'=>['YouTube (https://)',250], 'address'=>['Endereço / localização',300]];
    foreach(range(1,3) as $n){
        $fields['benefit'.$n]=['Benefício '.$n,240];
        $fields['portfolio_title'.$n]=['Projeto '.$n.' — título',100];
        $fields['portfolio_text'.$n]=['Projeto '.$n.' — descrição',450];
        $fields['portfolio_url'.$n]=['Projeto '.$n.' — link (https://)',250];
        $fields['team_name'.$n]=['Pessoa '.$n.' — nome',100];
        $fields['team_role'.$n]=['Pessoa '.$n.' — função / apresentação',240];
        $fields['testimonial_name'.$n]=['Depoimento '.$n.' — nome do cliente',100];
        $fields['testimonial_text'.$n]=['Depoimento '.$n.' — relato autorizado',600];
        $fields['faq_question'.$n]=['Pergunta '.$n,200];
        $fields['faq_answer'.$n]=['Resposta '.$n,1200];
    }
    return $fields;
}
function sb_image_keys(): array {return ['logo','photo','portfolio_image1','portfolio_image2','portfolio_image3','team_image1','team_image2','team_image3','share_image'];}
function sb_image_is_selected(array $p,string $key): bool {
    if(str_starts_with($key,'portfolio_'))return in_array('portfolio',$p['sections'],true);
    if(str_starts_with($key,'team_'))return in_array('team',$p['sections'],true);
    return true;
}
function sb_project(array $in,bool $draft=false): array {
    // Editing may temporarily leave required fields and URLs incomplete.
    if($draft){
        foreach(['brand'=>'Seu negócio','headline'=>'Seu título principal'] as $key=>$fallback){
            if(isset($in[$key])&&is_string($in[$key])&&trim($in[$key])==='')$in[$key]=$fallback;
        }
        foreach(['email','phone','instagram','site_url','facebook','linkedin','youtube','portfolio_url1','portfolio_url2','portfolio_url3'] as $key){
            if(!isset($in[$key])||!is_string($in[$key]))continue;
            $v=trim($in[$key]);
            $valid=match($key){'email'=>(bool)filter_var($v,FILTER_VALIDATE_EMAIL),'phone'=>(bool)preg_match('/^\d{10,15}$/D',$v),default=>filter_var($v,FILTER_VALIDATE_URL)&&preg_match('~^https://~i',$v)&&parse_url($v,PHP_URL_USER)===null&&parse_url($v,PHP_URL_PASS)===null};
            if(!$valid)$in[$key]='';
        }
    }
    $sections=$in['sections']??['about','services','contact'];
    if(!is_array($sections)||count($sections)>8)throw new InvalidArgumentException('Escolha até oito seções.');
    foreach($sections as $key)if(!is_string($key)||!isset(sb_section_labels()[$key]))throw new InvalidArgumentException('Seção inválida.');
    if(count($sections)!==count(array_unique($sections)))throw new InvalidArgumentException('Uma seção foi repetida.');
    $base=$in;$base['sections']=array_values(array_intersect($sections,['about','services','contact']));
    $p=sb_project_base($base);$p['sections']=array_values($sections);
    foreach(sb_extra_fields() as $key=>[$label,$max])$p[$key]=sb_text($in,$key,$max);
    foreach(['site_url','facebook','linkedin','youtube','portfolio_url1','portfolio_url2','portfolio_url3'] as $key){
        if($p[$key]!==''&&(!filter_var($p[$key],FILTER_VALIDATE_URL)||!preg_match('~^https://~i',$p[$key])||parse_url($p[$key],PHP_URL_USER)!==null||parse_url($p[$key],PHP_URL_PASS)!==null))throw new InvalidArgumentException('Confira o endereço https:// em '.$key.'.');
    }
    $p['font']=in_array($in['font']??'', ['system','serif','humanist','mono'],true)?$in['font']:'system';
    $p['theme']=in_array($in['theme']??'', ['light','dark','auto'],true)?$in['theme']:'light';
    foreach(['background'=>'#f7f9fc','text_color'=>'#172033'] as $key=>$fallback){
        $value=$in[$key]??$fallback;
        if(!is_string($value)||!preg_match('/^#[0-9a-f]{6}$/iD',$value))throw new InvalidArgumentException('Cor inválida.');
        $p[$key]=$value;
    }
    // Enforce readable user-selected text/background pairs.
    if(!$draft&&sb_contrast($p['background'],$p['text_color'])<4.5)throw new InvalidArgumentException('As cores de fundo e texto precisam de mais contraste. Escolha texto mais escuro ou fundo mais claro.');
    $total=0;
    foreach(sb_image_keys() as $key){
        if(!in_array($key,['logo','photo'],true))$p[$key]=sb_image($in[$key]??null);
        if($p[$key])$total+=strlen($p[$key]['bytes']);
    }
    if($total>3800000)throw new InvalidArgumentException('As imagens juntas excedem o limite. Reduza o tamanho ou remova algumas imagens.');
    foreach(['services'=>'service','benefits'=>'benefit','portfolio'=>'portfolio_title','team'=>'team_name','testimonials'=>'testimonial_text','faq'=>'faq_question'] as $section=>$prefix){
        if($draft||!in_array($section,$p['sections'],true))continue;
        $hasContent=false;foreach(range(1,3) as $n)if(($p[$prefix.$n]??'')!=='')$hasContent=true;
        if(!$hasContent)throw new InvalidArgumentException('Preencha ao menos um item de '.sb_section_labels()[$section].' ou desmarque essa seção.');
    }
    foreach(range(1,3) as $n){
        if(!$draft&&in_array('testimonials',$p['sections'],true)&&$p['testimonial_text'.$n]!==''&&$p['testimonial_name'.$n]==='')throw new InvalidArgumentException('Informe o nome do cliente no depoimento '.$n.'.');
        if(!$draft&&in_array('faq',$p['sections'],true)&&$p['faq_question'.$n]!==''&&$p['faq_answer'.$n]==='')throw new InvalidArgumentException('Preencha a resposta da pergunta '.$n.'.');
    }
    return $p;
}
function sb_contrast(string $a,string $b): float {
    $lum=function($hex){$c=sscanf($hex,'#%02x%02x%02x');$c=array_map(function($v){$v/=255;return $v<=.04045?$v/12.92:(($v+.055)/1.055)**2.4;},$c);return .2126*$c[0]+.7152*$c[1]+.0722*$c[2];};
    $x=$lum($a);$y=$lum($b);return (max($x,$y)+.05)/(min($x,$y)+.05);
}
function sb_styles(): string {return sb_styles_base()."\nbody{font-size:1.0625rem}\n".<<<'CSS'

:root{color-scheme:light}body{background:var(--page-bg,#f7f9fc);color:var(--page-fg,#172033);font-family:var(--site-font,Arial,sans-serif)}.shell{width:calc(100% - clamp(28px,5vw,80px));max-width:none}.hero.no-photo .shell{max-width:none}.top{background:var(--page-bg,#fff);border-color:#94a3b8}.section{margin:24px auto;padding:36px 0;width:calc(100% - 32px);border:1px solid #94a3b8;border-radius:18px}.section .shell{width:calc(100% - 40px)}.services,.contact,.local .contact{background:transparent}.local .cards article{background:transparent}.cards article{border-color:#94a3b8}.cards img{width:100%;height:240px;object-fit:cover;border-radius:12px}.cards blockquote{margin:0;white-space:pre-line}.cards p{white-space:pre-line}h1,h2,h3{overflow-wrap:anywhere}.hero .eyebrow{color:#fff}.consultoria .hero .eyebrow{color:#172033}.consultoria h1,.consultoria h2{font-family:inherit}.button{cursor:pointer;border:0;font-family:inherit}.section .button{color:var(--button-text);margin:12px 12px 0 0}.cards a{display:inline-block;margin-top:12px;text-decoration:underline}.faq-list details{padding:18px;border:1px solid #94a3b8;border-radius:12px;margin-bottom:12px}.faq-list summary{cursor:pointer;font-weight:700}.faq-list p{white-space:pre-line}.site-tools{display:flex;flex-wrap:wrap;gap:8px;padding:12px 20px;justify-content:flex-end}.site-tools button{padding:9px 14px;background:var(--page-bg,#fff);color:var(--page-fg,#172033);border:1px solid #64748b;border-radius:8px;font:inherit;cursor:pointer}.skip-link{position:absolute;left:12px;top:-100px;z-index:99;padding:12px;background:#fff;color:#111}.skip-link:focus{top:12px}:is(button,input,textarea,summary):focus-visible{outline:3px solid #d97706;outline-offset:4px}.contact-form{max-width:none}.contact-form .button{color:var(--button-text)}footer{color:inherit}.social-links{display:flex;flex-wrap:wrap;gap:14px}.social-links a{margin:0}.whatsapp-float{position:fixed;bottom:18px;right:18px;z-index:20;background:#075e42;color:#fff;padding:12px 18px;border-radius:40px;text-decoration:none;font-weight:700;box-shadow:0 5px 16px #0003}body{padding-bottom:65px}html[data-site-theme=dark]{color-scheme:dark}html[data-site-theme=dark] body{--page-bg:#101827!important;--page-fg:#eef4ff!important}html[data-site-theme=dark] :is(.hero,.consultoria .hero,.local .hero){background:#19283d;color:#eef4ff}html[data-site-theme=dark] .hero :is(p,.eyebrow){color:#eef4ff}html[data-site-theme=dark] .contact-form :is(input,textarea){background:#172339;color:#eef4ff;border-color:#94a3b8}@media(min-width:701px){.cards{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:700px){.section{padding:24px 0;margin-block:16px}.section .shell{width:calc(100% - 28px)}.cards{grid-template-columns:1fr}.hero img{height:auto;max-height:400px}.site-tools{justify-content:flex-start}.top nav{max-width:100%}}@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto!important}}@media print{.site-tools,.whatsapp-float{display:none}.section{break-inside:avoid}}
CSS;}
function sb_site_script(): string {return <<<'JS'
(()=>{'use strict';const root=document.documentElement;let size=100;const key='client-site:'+location.pathname;function save(k,v){try{localStorage.setItem(key+k,v)}catch{}}function read(k){try{return localStorage.getItem(key+k)}catch{return null}}const initial=root.dataset.defaultTheme||'light';const preferred=read(':theme')||initial;function theme(v){root.dataset.siteTheme=v==='auto'?(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light'):v;const b=document.querySelector('[data-theme-toggle]');if(b)b.setAttribute('aria-pressed',String(root.dataset.siteTheme==='dark'));}theme(preferred);const stored=Number(read(':font'));if(stored>=80&&stored<=150)size=stored;function font(){root.style.fontSize=size+'%';const s=document.querySelector('[data-font-status]');if(s)s.textContent=size+'%';}font();document.querySelector('[data-theme-toggle]')?.addEventListener('click',()=>{const next=root.dataset.siteTheme==='dark'?'light':'dark';theme(next);save(':theme',next)});document.querySelectorAll('[data-font-change]').forEach(b=>b.addEventListener('click',()=>{size=Math.min(150,Math.max(80,size+Number(b.dataset.fontChange)));font();save(':font',String(size))}));document.querySelector('[data-font-reset]')?.addEventListener('click',()=>{size=100;font();save(':font','100')});matchMedia('(prefers-color-scheme: dark)').addEventListener('change',()=>{if(!read(':theme')&&initial==='auto')theme('auto')});document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{const id=a.getAttribute('href').slice(1);const target=document.getElementById(id);if(target){e.preventDefault();target.scrollIntoView({behavior:matchMedia('(prefers-reduced-motion: reduce)').matches?'auto':'smooth'});if(id==='conteudo')target.focus({preventScroll:true})}}));})();
JS;}
function sb_html(array $p,bool $preview=false): string {
    $e=fn($v)=>sb_escape($v);
    $img=function($key,$alt)use($p,$preview,$e){$im=$p[$key]??null;if(!$im)return '';return '<img loading="lazy" src="'.$e($preview?($im['data']??''):'assets/'.$key.'.'.$im['ext']).'" alt="'.$e($alt).'">';};
    $baseProject=$p;$baseProject['sections']=array_values(array_intersect($p['sections'],['about','services','contact']));
    $base=sb_html_base($baseProject,$preview);
    preg_match('~<section id="inicio".*?</section>~s',$base,$hero);
    $sections='';$nav='';$labels=sb_section_labels();
    foreach($p['sections'] as $key){
        $nav.='<a href="#'.$key.'">'.$labels[$key].'</a>';$content='';
        if($key==='about')$content='<p class="prose">'.$e($p['about']).'</p>';
        if(in_array($key,['services','benefits','portfolio','team','testimonials'],true)){
            $cards='';foreach(range(1,3) as $n){$c='';
                if($key==='services'&&($p['service'.$n]??'')!=='')$c='<p>'.$e($p['service'.$n]).'</p>';
                if($key==='benefits'&&($p['benefit'.$n]??'')!=='')$c='<p>'.$e($p['benefit'.$n]).'</p>';
                if($key==='portfolio'&&($p['portfolio_title'.$n]??'')!==''){$c=$img('portfolio_image'.$n,$p['portfolio_title'.$n]).'<h3>'.$e($p['portfolio_title'.$n]).'</h3><p>'.$e($p['portfolio_text'.$n]??'').'</p>';if(($p['portfolio_url'.$n]??'')!=='')$c.='<a href="'.$e($p['portfolio_url'.$n]).'" target="_blank" rel="noopener noreferrer">Conhecer projeto</a>';}
                if($key==='team'&&($p['team_name'.$n]??'')!=='')$c=$img('team_image'.$n,$p['team_name'.$n]).'<h3>'.$e($p['team_name'.$n]).'</h3><p>'.$e($p['team_role'.$n]??'').'</p>';
                if($key==='testimonials'&&($p['testimonial_text'.$n]??'')!=='')$c='<blockquote><p>'.$e($p['testimonial_text'.$n]).'</p><cite>'.$e($p['testimonial_name'.$n]??'').'</cite></blockquote>';
                if($c!=='')$cards.='<article>'.$c.'</article>';
            }$content='<div class="cards">'.$cards.'</div>';
        }
        if($key==='faq'){$content='<div class="faq-list">';foreach(range(1,3) as $n)if(($p['faq_question'.$n]??'')!=='')$content.='<details><summary>'.$e($p['faq_question'.$n]).'</summary><p>'.$e($p['faq_answer'.$n]??'').'</p></details>';$content.='</div>';}
        if($key==='contact'){
            $content='<div class="social-links">';
            if($p['phone']!=='')$content.='<a class="button" href="https://wa.me/'.$e($p['phone']).'">WhatsApp</a>';
            if($p['email']!=='')$content.='<a href="mailto:'.$e($p['email']).'">'.$e($p['email']).'</a>';
            foreach(['instagram'=>'Instagram','facebook'=>'Facebook','linkedin'=>'LinkedIn','youtube'=>'YouTube'] as $field=>$label)if(($p[$field]??'')!=='')$content.='<a href="'.$e($p[$field]).'" target="_blank" rel="noopener noreferrer">'.$label.'</a>';
            $content.='</div>';
            if(($p['address']??'')!=='')$content.='<p>'.$e($p['address']).'</p><a href="https://www.google.com/maps/search/?api=1&amp;query='.rawurlencode($p['address']).'" target="_blank" rel="noopener noreferrer">Ver localização no mapa</a>';
            $content.='<!--CONTACT_FORM-->';
            if($preview)$content=str_replace('<!--CONTACT_FORM-->','<h3>Envie uma mensagem</h3><form class="contact-form"><label>Nome<input disabled name="nome" minlength="2" maxlength="100"></label><label>E-mail<input disabled name="email" type="email" maxlength="254"></label><label>Mensagem<textarea disabled name="mensagem" rows="4" minlength="10" maxlength="3000"></textarea></label><p>As mensagens serão recebidas no painel após a instalação.</p><button disabled class="button">Enviar mensagem</button></form>',$content);
        }
        $sections.='<section class="section '.$key.'" id="'.$key.'"><div class="shell"><h2>'.$labels[$key].'</h2>'.$content.'</div></section>';
    }
    $fonts=['system'=>'Arial, sans-serif','serif'=>'Georgia, serif','humanist'=>'Trebuchet MS, Arial, sans-serif','mono'=>'Courier New, monospace'];
    $color=$p['color'];$button=sb_contrast($color,'#ffffff')>=sb_contrast($color,'#071b35')?'#ffffff':'#071b35';
    $title=($p['seo_title']??'')?:$p['brand'];$desc=($p['seo_description']??'')?:$p['intro'];
    $meta='<title>'.$e($title).'</title><meta name="description" content="'.$e($desc).'"><meta property="og:type" content="website"><meta property="og:title" content="'.$e($title).'"><meta property="og:description" content="'.$e($desc).'"><meta name="twitter:card" content="summary_large_image">';
    if(($p['site_url']??'')!==''){$url=rtrim($p['site_url'],'/').'/';$meta.='<link rel="canonical" href="'.$e($url).'"><meta property="og:url" content="'.$e($url).'">';if($p['share_image']??null)$meta.='<meta property="og:image" content="'.$e($url.'assets/share_image.'.$p['share_image']['ext']).'"><meta name="twitter:image" content="'.$e($url.'assets/share_image.'.$p['share_image']['ext']).'">';}
    $style=$preview?'<style>'.sb_styles().'</style>':'<link rel="stylesheet" href="style.css">';
    $script=$preview?'<script>'.sb_site_script().'</script>':'<script src="site.js" defer></script>';
    $tools='<div class="site-tools" role="group" aria-label="Acessibilidade"><button type="button" data-font-change="-10" aria-label="Diminuir fonte">A−</button><button type="button" data-font-change="10" aria-label="Aumentar fonte">A+</button><button type="button" data-font-reset>Restaurar fonte</button><span data-font-status aria-live="polite">100%</span><button type="button" data-theme-toggle aria-pressed="false">Alternar tema</button></div>';
    $logo=$img('logo',$p['brand'])?:$e($p['brand']);
    $floating=$p['phone']!==''?'<a class="whatsapp-float" href="https://wa.me/'.$e($p['phone']).'" aria-label="Conversar pelo WhatsApp">WhatsApp</a>':'';
    return '<!doctype html><html lang="pt-BR" data-default-theme="'.$e($p['theme']??'light').'" data-site-theme="'.$e(($p['theme']??'light')==='dark'?'dark':'light').'"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'.$meta.$style.'</head><body class="'.$e($p['template']).'" style="--accent:'.$color.';--button-text:'.$button.';--page-bg:'.($p['background']??'#f7f9fc').';--page-fg:'.($p['text_color']??'#172033').';--site-font:'.$e($fonts[$p['font']??'system']??$fonts['system']).'"><a class="skip-link" href="#conteudo">Ir para o conteúdo</a>'.$tools.'<header class="top"><div class="shell"><a href="#inicio" class="brand">'.$logo.'</a><nav aria-label="Seções do site">'.$nav.'</nav></div></header><main id="conteudo" tabindex="-1">'.($hero[0]??'').$sections.'</main><footer><div class="shell">'.$e($p['footer']?:$p['brand'].' — Todos os direitos reservados.').'</div></footer>'.$floating.$script.'</body></html>';
}
