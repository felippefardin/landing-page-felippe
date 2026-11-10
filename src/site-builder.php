<?php
declare(strict_types=1);

const BUILDER_TERMS_VERSION = '2026-11-10-v3-refunds';
function sb_escape(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function sb_money(int $cents): string { return 'R$ '.number_format($cents/100,2,',','.'); }
function sb_price(bool $support, bool $coupon): array {
    return ['base_cents'=>35000,'discount_cents'=>$coupon?5250:0,'support_cents'=>$support?15000:0,'total_cents'=>35000-($coupon?5250:0)+($support?15000:0)];
}
function sb_text(array $input,string $key,int $max,string $fallback=''): string {
    $v=$input[$key]??$fallback;
    if(!is_string($v) || mb_strlen($v)>$max) throw new InvalidArgumentException('Confira o campo '.$key.'.');
    return trim($v);
}
function sb_image(mixed $image): ?array {
    if($image===null || $image==='') return null;
    if(!is_string($image) || strlen($image)>2800000 || !preg_match('~^data:image/(png|jpeg|webp);base64,([A-Za-z0-9+/=]+)$~D',$image,$m)) throw new InvalidArgumentException('Use uma imagem PNG, JPG ou WebP de até 2 MB.');
    $bytes=base64_decode($m[2],true);
    if($bytes===false || strlen($bytes)>2*1024*1024) throw new InvalidArgumentException('Imagem muito grande.');
    $info=@getimagesizefromstring($bytes);
    if(!$info || !in_array($info['mime'],['image/png','image/jpeg','image/webp'],true) || $info['mime']!=='image/'.$m[1] || $info[0]>6000 || $info[1]>6000) throw new InvalidArgumentException('Imagem inválida ou com dimensões maiores que 6000 pixels.');
    return ['data'=>$image,'bytes'=>$bytes,'ext'=>['png'=>'png','jpeg'=>'jpg','webp'=>'webp'][$m[1]]];
}
function sb_project(array $in): array {
    $p=[];
    foreach(['brand'=>80,'headline'=>140,'intro'=>450,'about'=>1800,'service1'=>240,'service2'=>240,'service3'=>240,'cta'=>60,'footer'=>180,'email'=>254,'phone'=>20,'instagram'=>250] as $key=>$max) $p[$key]=sb_text($in,$key,$max);
    if($p['brand']==='' || $p['headline']==='') throw new InvalidArgumentException('Preencha o nome do negócio e o título principal.');
    if($p['email']!=='' && !filter_var($p['email'],FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('E-mail de contato inválido.');
    if($p['phone']!=='' && !preg_match('/^\d{10,15}$/D',$p['phone'])) throw new InvalidArgumentException('Informe o WhatsApp com DDI e apenas números.');
    if($p['instagram']!=='' && (!filter_var($p['instagram'],FILTER_VALIDATE_URL) || !preg_match('~^https://~i',$p['instagram']))) throw new InvalidArgumentException('Informe o endereço completo com https:// para a rede social.');
    $p['template']=in_array($in['template']??'', ['studio','consultoria','local'],true)?$in['template']:'studio';
    $p['color']=is_string($in['color']??null) && preg_match('/^#[0-9a-f]{6}$/iD',$in['color'])?$in['color']:'#2563eb';
    $sections=$in['sections']??['about','services','contact'];
    if(!is_array($sections) || count($sections)>3 || count($sections)!==count(array_unique($sections,SORT_REGULAR))) throw new InvalidArgumentException('Organização das seções inválida.');
    foreach($sections as $section) if(!is_string($section) || !in_array($section,['about','services','contact'],true)) throw new InvalidArgumentException('Seção inválida.');
    $p['sections']=$sections;
    $p['logo']=sb_image($in['logo']??null);$p['photo']=sb_image($in['photo']??null);
    return $p;
}
function sb_styles(): string { return <<<'CSS'
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;color:#172033;background:#f7f9fc;font:17px/1.65 Arial,sans-serif}a{color:inherit}a:focus-visible{outline:3px solid #f59e0b;outline-offset:4px}.shell{width:min(1120px,calc(100% - 40px));margin:auto}.top{padding:22px 0;background:#fff;border-bottom:1px solid #e2e8f0}.top .shell{display:flex;align-items:center;justify-content:space-between;gap:20px}.brand{font-weight:800;font-size:1.3rem;text-decoration:none;overflow-wrap:anywhere}.brand img{max-width:180px;max-height:70px;object-fit:contain}.top nav{display:flex;flex-wrap:wrap;gap:18px;font-size:.9rem}.hero{padding:90px 0;background:#0b1930;color:white}.hero .shell{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,.8fr);align-items:center;gap:54px}.hero.no-photo .shell{display:block;max-width:900px}.eyebrow{color:var(--accent);font-weight:800;letter-spacing:.12em;text-transform:uppercase}.hero h1{font-size:clamp(2.2rem,5vw,4.4rem);line-height:1.08;letter-spacing:-.04em;margin:14px 0 24px;overflow-wrap:anywhere}.hero p{color:#dbe5f5;max-width:650px}.hero img{width:100%;height:450px;object-fit:cover;border-radius:24px}.button{display:inline-block;margin-top:16px;background:var(--accent);color:var(--button-text);font-weight:700;padding:14px 24px;border-radius:10px;text-decoration:none}.section{padding:72px 0}.section h2{font-size:clamp(1.6rem,3vw,2.4rem);line-height:1.2;margin:0 0 24px}.prose{max-width:800px;white-space:pre-line;overflow-wrap:anywhere}.services{background:white}.cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}.cards article{padding:28px;border:1px solid #dbe2ed;border-radius:16px;overflow-wrap:anywhere}.cards span{font-size:2rem;color:var(--accent);font-weight:800}.contact{background:#eaf0f8}.contact a{margin-right:18px;overflow-wrap:anywhere}footer{padding:30px 0;font-size:.9rem;color:#475569;border-top:1px solid #dbe2ed}.consultoria .hero{background:#f0eee8;color:#172033}.consultoria .hero p{color:#475569}.consultoria h1,.consultoria h2{font-family:Georgia,serif;letter-spacing:-.03em}.consultoria .button{border-radius:0}.local .hero{background:linear-gradient(130deg,#102f2a,#17473b)}.local .hero img{border-radius:100px 16px 100px 16px}.local .cards article{background:#f0f7f2}.local .contact{background:#e6f1e9}@media(max-width:700px){.top .shell{align-items:start;flex-direction:column}.top nav{gap:12px}.hero{padding:52px 0}.hero .shell{grid-template-columns:1fr;gap:30px}.hero img{height:300px}.cards{grid-template-columns:1fr}.section{padding:44px 0}.shell{width:calc(100% - 32px)}}@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}}.contact-form{max-width:720px}.contact-form label{display:grid;gap:8px;margin:16px 0;font-weight:700}.contact-form input,.contact-form textarea{box-sizing:border-box;width:100%;padding:12px;border:1px solid #94a3b8;border-radius:8px;font:inherit;background:#fff;color:#172033}.contact-form .contact-consent{display:flex;align-items:start;gap:10px;font-size:.9rem;font-weight:400}.contact-form .contact-consent input{width:auto;margin-top:6px}.contact-form .contact-privacy{font-size:.85rem}.contact-honey{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden}.contact-form .button{border:0;font:700 16px Arial,sans-serif;cursor:pointer}
CSS;
}
function sb_html(array $p,bool $preview=false): string {
    $e=fn($v)=>sb_escape($v);
    $image=function(string $key)use($p,$preview){return !$p[$key]?'':($preview?$p[$key]['data']:'assets/'.$key.'.'.$p[$key]['ext']);};
    $logo=$p['logo']?'<img src="'.$e($image('logo')).'" alt="'.$e($p['brand']).'">':$e($p['brand']);
    $photo=$p['photo']?'<img src="'.$e($image('photo')).'" alt="Imagem de apresentação de '.$e($p['brand']).'">':'';
    $link=$p['phone']!==''?'https://wa.me/'.$p['phone']:($p['email']!==''?'mailto:'.$p['email']:'#contact');
    $cta=$p['cta']!==''?$p['cta']:'Vamos conversar';
    $showCta=$p['phone']!=='' || $p['email']!=='' || in_array('contact',$p['sections'],true);
    $sections='';$nav='';$labels=['about'=>'Sobre','services'=>'Serviços','contact'=>'Contato'];
    foreach($p['sections'] as $key){
        $nav.='<a href="#'.$key.'">'.$labels[$key].'</a>';
        if($key==='about') $sections.='<section id="about" class="section"><div class="shell"><h2>Sobre '.$e($p['brand']).'</h2><p class="prose">'.$e($p['about']).'</p></div></section>';
        if($key==='services'){
            $cards='';foreach([1,2,3] as $n)if($p['service'.$n]!=='')$cards.='<article><span>0'.$n.'</span><p>'.$e($p['service'.$n]).'</p></article>';
            $sections.='<section id="services" class="section services"><div class="shell"><h2>Como podemos ajudar</h2><div class="cards">'.$cards.'</div></div></section>';
        }
        if($key==='contact'){
            $contacts=$p['email']!==''?'<a href="mailto:'.$e($p['email']).'">'.$e($p['email']).'</a>':'';
            if($p['instagram']!=='')$contacts.='<a href="'.$e($p['instagram']).'" target="_blank" rel="noopener noreferrer">Rede social</a>';
            $sections.='<section id="contact" class="section contact"><div class="shell"><h2>Fale com a gente</h2><p>'.$contacts.'</p><a class="button" href="'.$e($link).'">'.$e($cta).'</a></div></section>';
        }
    }
    $rgb=sscanf($p['color'],'#%02x%02x%02x');$contrast=(($rgb[0]*299+$rgb[1]*587+$rgb[2]*114)/1000)>155?'#071b35':'#ffffff';
    $heroButton=$showCta?'<a class="button" href="'.$e($link).'">'.$e($cta).'</a>':'';
    $contactMarker=in_array('contact',$p['sections'],true)?'<!--CONTACT_FORM-->':'';
    if($preview && $contactMarker!=='')$contactMarker='<section class="section contact"><div class="shell"><h2>Envie uma mensagem</h2><form class="contact-form"><label>Nome<input disabled placeholder="Nome do visitante"></label><label>E-mail<input disabled placeholder="E-mail do visitante"></label><label>Mensagem<textarea disabled rows="4" placeholder="Mensagem do visitante"></textarea></label><p class="contact-privacy">As mensagens serão recebidas no painel do seu site após a instalação.</p><button disabled class="button">Enviar mensagem</button></form></div></section>';
    $style=$preview?'<style>'.sb_styles().'</style>':'<link rel="stylesheet" href="style.css">';
    return '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="'.$e($p['intro']).'"><title>'.$e($p['brand']).'</title>'.$style.'</head><body class="'.$p['template'].'" style="--accent:'.$p['color'].';--button-text:'.$contrast.'"><header class="top"><div class="shell"><a class="brand" href="#inicio">'.$logo.'</a><nav aria-label="Navegação principal">'.$nav.'</nav></div></header><main><section id="inicio" class="hero '.($photo===''?'no-photo':'').'"><div class="shell"><div><span class="eyebrow">'.$e($p['brand']).'</span><h1>'.$e($p['headline']).'</h1><p>'.$e($p['intro']).'</p>'.$heroButton.'</div>'.$photo.'</div></section>'.$sections.$contactMarker.'</main><footer><div class="shell">'.$e($p['footer']!==''?$p['footer']:$p['brand'].' — Todos os direitos reservados.').'</div></footer></body></html>';
}
function sb_zip(array $p): string {
    $path=tempnam(sys_get_temp_dir(),'site-export-');
    if($path===false)throw new RuntimeException('Não foi possível preparar a entrega.');
    try{
        $zip=new ZipArchive();if($zip->open($path,ZipArchive::OVERWRITE)!==true)throw new RuntimeException('ZIP indisponível.');
        require_once __DIR__.'/client-site-export.php';
        foreach(sb_server_files($p) as $name=>$content)$zip->addFromString($name,$content);
        $zip->addFromString('style.css',sb_styles());
        foreach(['logo','photo'] as $key)if($p[$key])$zip->addFromString('assets/'.$key.'.'.$p[$key]['ext'],$p[$key]['bytes']);
        $zip->addFromString('LEIA-ME.txt',"SEU SITE COMPLETO\r\nLeia INSTALACAO.md. O pacote inclui PHP, painel próprio, formulário de contato, banco.sql, CSS, imagens e configuração de exemplo.\r\nRequer PHP 8.2+ e MySQL/MariaDB. Crie o banco na sua hospedagem, importe o SQL, preencha app/config.php e execute instalar.php para definir seu administrador.\r\nNenhuma credencial do criador ou de pagamento acompanha os arquivos. Guarde este ZIP e faça backup do banco e dos arquivos. O suporte de publicação é opcional.\r\n");
        $zip->close();$bytes=file_get_contents($path);if($bytes===false)throw new RuntimeException('Falha ao gerar ZIP.');return $bytes;
    }finally{if(is_file($path))unlink($path);}
}
function sb_terms(array $o): string {
    return "TERMO DE CONTRATAÇÃO — CRIADOR DE PÁGINAS\nVersão: ".BUILDER_TERMS_VERSION."\nPedido: ".$o['id']."\nCliente: ".$o['customer_name']."\nE-mail confirmado: ".$o['email']."\nProjeto: ".$o['project_title']."\n\n1. OBJETO\nLicença de uso do criador para personalização, pelo próprio cliente, de uma página responsiva com código de servidor PHP e banco MySQL/MariaDB a partir dos modelos disponibilizados. A entrega contém PHP, CSS, imagens, banco.sql, configuração de exemplo e instruções de instalação em ZIP. Inclui formulário de contato com armazenamento de mensagens e painel próprio para editar textos e contatos e consultar mensagens. Requer hospedagem compatível com PHP 8.2+ e MySQL/MariaDB, contratada pelo cliente. Não inclui WordPress, loja virtual, funcionalidades sob medida, domínio nem hospedagem.\n\n2. VALORES\nPágina: ".sb_money($o['base_cents'])."\nDesconto da página: ".sb_money($o['discount_cents'])."\nSuporte opcional: ".sb_money($o['support_cents'])."\nTotal: ".sb_money($o['total_cents'])."\nO cupom, quando informado e válido, aplica-se apenas à página. O suporte não recebe desconto.\n\n3. SUPORTE OPCIONAL\n".($o['support_cents']>0?"Contratado: até 2 horas de ajuda para publicação, por R$ 150,00, mediante agendamento com o responsável. As 2 horas não incluem desenvolvimento de novas funcionalidades. Domínio e hospedagem são contratados pelo cliente.":"Não contratado. A compra inclui somente os arquivos da página personalizada pelo cliente.")."\n\n4. CONTEÚDO E CONFERÊNCIA\nO cliente insere e confere os textos, imagens, contatos e demais conteúdos, declarando possuir autorização para utilizá-los. A prévia deve ser revisada antes do pagamento. A plataforma gera os arquivos a partir das escolhas do cliente.\n\n5. ENTREGA E CÓPIA DE SEGURANÇA\nApós a confirmação do pagamento pelo provedor, o ZIP será enviado ao e-mail confirmado e o link de download ficará disponível na página do pedido por 7 dias contados da aprovação. Os arquivos são armazenados temporariamente para permitir essa entrega. Depois do prazo, serão excluídos do sistema e não haverá cópia disponível para reenvio. O cliente deve baixar o ZIP e manter seu próprio backup. Falhas de entrega devem ser comunicadas ao atendimento. Esta política não afasta os direitos legalmente assegurados ao consumidor.\n\n6. DADOS E DOCUMENTO ELETRÔNICO\nSão preservados os dados necessários ao pedido, pagamento, suporte e comprovação deste aceite. O conteúdo dos arquivos do site é temporário; o termo aceito eletronicamente permanece no arquivo administrativo. O código de confirmação por e-mail é pessoal e de uso único. Este aceite é assinatura eletrônica simples, sem certificado digital ICP-Brasil. O PDF é uma representação do termo e das evidências registradas, não um PDF assinado com certificado.\n\n7. PAGAMENTO E CUPOM\nA assinatura deste termo não comprova pagamento nem libera arquivos. A entrega ocorre apenas após pagamento aprovado. O cupom fica reservado para este pedido no início do checkout e passa a utilizado após aprovação. Tentativas de cobrança duplicadas são encaminhadas ao atendimento e não geram nova entrega.\n\n8. ATENÇÃO — ARREPENDIMENTO E REEMBOLSO\nBase legal: art. 49 do Código de Defesa do Consumidor (Lei nº 8.078/1990) e art. 5º do Decreto nº 7.962/2013, normas fiscalizadas pelos órgãos de defesa do consumidor, como o Procon.\nO consumidor pode exercer o arrependimento em 7 dias, observada a contratação e o recebimento do produto ou serviço. Para este sistema, a janela considera a data mais recente entre disponibilização após pagamento e envio da entrega, até o fim do sétimo dia no horário de Brasília. Falha ou atraso de entrega deve ser comunicado para análise, sem perda dos direitos legais.\nApós esse prazo, não há devolução por simples arrependimento prevista nesta oferta. PERMANECEM os direitos legais relativos a vícios, falhas, cobrança indevida e descumprimento da oferta. O teste, o download e o aceite não eliminam esses direitos.\nA solicitação pode ser feita na página do pedido ou pelo atendimento, sem motivo obrigatório. No arrependimento tempestivo, a devolução será integral, incluindo suporte opcional contratado, sem multa. A solicitação é registrada imediatamente e encaminhada para execução pelo responsável; o crédito depende do provedor e da instituição financeira.\nAo solicitar o cancelamento, os links de download serão bloqueados imediatamente. Isso não apaga arquivos já baixados. A página de acompanhamento e o atendimento permanecem acessíveis após a expiração dos downloads.\n\n9. ATENDIMENTO\nContato: ".(function_exists('memory_env')?memory_env('BUILDER_CONTACT_EMAIL','felippefardin@gmail.com'):'felippefardin@gmail.com').". Solicitações sobre compra, entrega, suporte e direitos do consumidor serão tratadas por esse canal.\n\nDeclaro que revisei a prévia e os valores e concordo com este termo, incluindo a conservação temporária dos arquivos e a necessidade de backup.\n";
}

function sb_test_html(string $html): string {
    $html=str_replace(' disabled','',$html);
    $script=<<<'HTML'
<div id="demo-status" role="status" style="position:fixed;bottom:0;left:0;right:0;background:#10233d;color:#fff;padding:12px;z-index:99999;font:14px Arial">TESTE GRATUITO — navegue e simule o formulário. Dados não são enviados ou armazenados. Painel PHP e SQL requerem instalação.</div>
<script>
document.querySelectorAll('.contact-form input').forEach((e,i)=>{e.required=true;if(i===1)e.type='email';});
document.querySelectorAll('.contact-form textarea').forEach(e=>e.required=true);
document.querySelectorAll('form').forEach(f=>f.addEventListener('submit',e=>{e.preventDefault();if(f.reportValidity()){document.getElementById('demo-status').textContent='Simulação concluída! Nenhuma mensagem foi enviada ou armazenada.';f.reset();}}));
document.querySelectorAll('a').forEach(a=>{if(!a.getAttribute('href').startsWith('#'))a.addEventListener('click',e=>{e.preventDefault();document.getElementById('demo-status').textContent='Contato configurado: '+a.getAttribute('href')+' — abertura externa desativada neste teste.';});});
</script>
HTML;
    return str_replace('</body>',$script.'</body>',$html);
}
