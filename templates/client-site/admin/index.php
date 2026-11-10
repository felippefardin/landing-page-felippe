<?php
declare(strict_types=1);
require dirname(__DIR__).'/app/bootstrap.php';admin();$error='';$notice=$_SESSION['admin_flash']??'';unset($_SESSION['admin_flash']);
try{
 if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();$id=filter_var($_POST['id']??null,FILTER_VALIDATE_INT);$status=$_POST['status']??'';
  if(!$id||!in_array($status,['new','read','archived'],true))throw new InvalidArgumentException('Ação inválida.');
  q('UPDATE site_messages SET status=? WHERE id=?',[$status,$id]);$_SESSION['admin_flash']='Situação atualizada.';header('Location: index.php');exit;
 }
 $page=max(1,min(100000,(int)($_GET['page']??1)));$rows=q('SELECT * FROM site_messages ORDER BY id DESC LIMIT 26 OFFSET '.(($page-1)*25))->fetchAll();$next=count($rows)>25;$rows=array_slice($rows,0,25);
}catch(InvalidArgumentException $ex){$error=$ex->getMessage();}catch(Throwable $ex){$error='Não foi possível abrir as mensagens.';$rows=[];$next=false;$page=1;}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Mensagens | Painel</title><link rel="stylesheet" href="admin.css"></head><body><main><header><h1>Painel do meu site</h1><nav><a href="editar.php">Editar textos e contatos</a><a href="../index.php">Ver site</a><form method="post" action="logout.php"><?=csrf_field()?><button>Sair</button></form></nav></header><h2>Mensagens recebidas</h2><?php if($error||$notice):?><p role="status"><?=e($error?:$notice)?></p><?php endif;?>
<?php foreach($rows as $row):?><article><h3><?=e($row['nome'])?></h3><p><a href="mailto:<?=e($row['email'])?>"><?=e($row['email'])?></a> · <?=e($row['created_at'])?> UTC</p><p class="message"><?=e($row['mensagem'])?></p><form method="post" class="inline"><?=csrf_field()?><input type="hidden" name="id" value="<?=(int)$row['id']?>"><label>Situação<select name="status"><?php foreach(['new'=>'Nova','read'=>'Lida','archived'=>'Arquivada'] as $key=>$label):?><option value="<?=$key?>" <?=$row['status']===$key?'selected':''?>><?=$label?></option><?php endforeach;?></select></label><button>Atualizar</button></form></article><?php endforeach;?>
<?php if(!$rows):?><p>Ainda não há mensagens.</p><?php endif;?><nav><?php if($page>1):?><a href="?page=<?=$page-1?>">Anterior</a><?php endif;?><span>Página <?=$page?></span><?php if($next):?><a href="?page=<?=$page+1?>">Próxima</a><?php endif;?></nav><p class="small">Faça backup do banco e dos arquivos. As mensagens contêm dados de contato dos visitantes; restrinja o acesso ao painel.</p></main></body></html>
