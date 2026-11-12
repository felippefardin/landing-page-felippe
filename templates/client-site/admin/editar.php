<?php
declare(strict_types=1);
require dirname(__DIR__).'/app/bootstrap.php';
require dirname(__DIR__).'/app/render.php';
admin();$error='';$p=[];
$fields=['brand'=>['Nome do negócio',80],'headline'=>['Título principal',140],'intro'=>['Apresentação',450],'about'=>['Sobre o negócio',1800],'service1'=>['Serviço 1',240],'service2'=>['Serviço 2',240],'service3'=>['Serviço 3',240],'cta'=>['Texto do botão',60],'footer'=>['Rodapé',180],'email'=>['E-mail',254],'phone'=>['WhatsApp com DDI e DDD',20],'instagram'=>['Instagram (https://)',250]]+sb_extra_fields();
try{
    $p=settings();
    if($_SERVER['REQUEST_METHOD']==='POST'){
        verify_csrf();$in=[];
        foreach($fields as $key=>[$label,$max])$in[$key]=text_input($key,$max,in_array($key,['brand','headline'],true));
        foreach(['font','theme','color','background','text_color'] as $key)$in[$key]=text_input($key,30);
        $in['template']=$p['template'];$sections=$_POST['sections']??[];$positions=$_POST['position']??[];
        if(!is_array($sections)||!is_array($positions))throw new InvalidArgumentException('Organização inválida.');
        foreach($sections as $key)if(!is_string($key)||!isset(sb_section_labels()[$key]))throw new InvalidArgumentException('Seção inválida.');
        usort($sections,fn($a,$b)=>(int)($positions[$a]??99)<=>(int)($positions[$b]??99));
        $in['sections']=$sections;$validated=sb_project($in);
        foreach(sb_image_keys() as $key)$validated[$key]=$p[$key]??null;
        q('UPDATE site_settings SET data=? WHERE id=1',[json_encode($validated,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
        $_SESSION['admin_flash']='Conteúdo, aparência e seções salvos.';header('Location: index.php');exit;
    }
}catch(InvalidArgumentException $ex){$error=$ex->getMessage();$p=array_replace($p,$in??[]);}catch(Throwable $ex){$error='Não foi possível carregar ou salvar o site.';}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Editar meu site</title><link rel="stylesheet" href="admin.css"></head><body><main><header><h1>Conteúdo e aparência</h1><a href="index.php">Voltar ao painel</a></header><?php if($error):?><p role="alert"><?=e($error)?></p><?php endif;?>
<form method="post"><?=csrf_field()?>
<fieldset><legend>Seções e ordem</legend><?php foreach(sb_section_labels() as $key=>$label):$index=array_search($key,$p['sections']??[],true);?><label><span><input type="checkbox" name="sections[]" value="<?=$key?>" <?=$index!==false?'checked':''?>> <?=e($label)?></span><select name="position[<?=$key?>]" aria-label="Posição de <?=e($label)?>"><?php foreach(range(1,8) as $n):?><option value="<?=$n?>" <?=$index!==false&&$index+1===$n?'selected':''?>>Posição <?=$n?></option><?php endforeach;?></select></label><?php endforeach;?></fieldset>
<details open><summary>Textos e contatos</summary><?php foreach($fields as $key=>[$label,$max]):?><label><?=e($label)?><?php if($max>250):?><textarea name="<?=$key?>" maxlength="<?=$max?>" rows="3"><?=e($p[$key]??'')?></textarea><?php else:?><input name="<?=$key?>" maxlength="<?=$max?>" value="<?=e($p[$key]??'')?>" <?=in_array($key,['brand','headline'],true)?'required':''?>><?php endif;?></label><?php endforeach;?></details>
<fieldset><legend>Aparência</legend><?php foreach(['color'=>['Destaque','#2563eb'],'background'=>['Fundo','#f7f9fc'],'text_color'=>['Texto','#172033']] as $key=>[$label,$default]):?><label><?=$label?><input type="color" name="<?=$key?>" value="<?=e($p[$key]??$default)?>"></label><?php endforeach;?>
<label>Fonte<select name="font"><?php foreach(['system'=>'Arial','serif'=>'Georgia','humanist'=>'Trebuchet','mono'=>'Courier'] as $key=>$label):?><option value="<?=$key?>" <?=($p['font']??'system')===$key?'selected':''?>><?=$label?></option><?php endforeach;?></select></label>
<label>Tema<select name="theme"><?php foreach(['light'=>'Claro','dark'=>'Escuro','auto'=>'Dispositivo'] as $key=>$label):?><option value="<?=$key?>" <?=($p['theme']??'light')===$key?'selected':''?>><?=$label?></option><?php endforeach;?></select></label></fieldset>
<button>Salvar alterações</button></form><p>Use somente depoimentos reais e autorizados. Para substituir imagens já incluídas, envie novos arquivos com os mesmos nomes e formatos na pasta assets. Faça backup antes.</p></main></body></html>
