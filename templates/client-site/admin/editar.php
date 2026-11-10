<?php
declare(strict_types=1);
require dirname(__DIR__).'/app/bootstrap.php';admin();$error='';$p=[];
$fields=['brand'=>['Nome do negócio',80],'headline'=>['Título principal',140],'intro'=>['Apresentação',450],'about'=>['Sobre o negócio',1800],'service1'=>['Serviço 1',240],'service2'=>['Serviço 2',240],'service3'=>['Serviço 3',240],'cta'=>['Texto do botão',60],'footer'=>['Rodapé',180],'email'=>['E-mail',254],'phone'=>['WhatsApp com DDI, apenas números',20],'instagram'=>['Rede social — endereço https://',250]];
try{
 $p=settings();
 if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();foreach($fields as $key=>[$label,$max])$p[$key]=text_input($key,$max,in_array($key,['brand','headline'],true));
  if($p['email']!==''&&!filter_var($p['email'],FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException('E-mail inválido.');
  if($p['phone']!==''&&!preg_match('/^\d{10,15}$/D',$p['phone']))throw new InvalidArgumentException('WhatsApp inválido. Use DDI e DDD.');
  if($p['instagram']!==''&&(!filter_var($p['instagram'],FILTER_VALIDATE_URL)||!preg_match('~^https://~i',$p['instagram'])))throw new InvalidArgumentException('Rede social inválida.');
  $color=text_input('color',7,true);if(!preg_match('/^#[0-9a-f]{6}$/iD',$color))throw new InvalidArgumentException('Cor inválida.');$p['color']=$color;
  q('UPDATE site_settings SET data=? WHERE id=1',[json_encode($p,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);$_SESSION['admin_flash']='Textos e contatos salvos.';header('Location: index.php');exit;
 }
}catch(InvalidArgumentException $ex){$error=$ex->getMessage();}catch(Throwable $ex){$error='Não foi possível carregar ou salvar os textos.';}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Editar meu site</title><link rel="stylesheet" href="admin.css"></head><body><main><header><h1>Editar textos e contatos</h1><a href="index.php">Voltar ao painel</a></header><?php if($error):?><p role="alert"><?=e($error)?></p><?php endif;?><form method="post"><?=csrf_field()?><?php foreach($fields as $key=>[$label,$max]):?><label><?=e($label)?><?php if($max>250):?><textarea name="<?=$key?>" maxlength="<?=$max?>" rows="4"><?=e($p[$key]??'')?></textarea><?php else:?><input name="<?=$key?>" maxlength="<?=$max?>" value="<?=e($p[$key]??'')?>" <?=in_array($key,['brand','headline'],true)?'required':''?>><?php endif;?></label><?php endforeach;?><label>Cor de destaque<input type="color" name="color" value="<?=e($p['color']??'#2563eb')?>"></label><button>Salvar alterações</button></form><p>Para substituir imagens, envie os novos arquivos com os mesmos nomes e formatos na pasta assets. Faça backup antes.</p></main></body></html>
