<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';$error='';
try{
 if(q('SELECT id FROM site_admins WHERE id=1')->fetch()){http_response_code(403);exit('Instalação concluída. Acesse admin/login.php. Não é permitido criar outro administrador pelo instalador.');}
 if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();if(!rate('setup',5,900))throw new InvalidArgumentException('Aguarde antes de tentar novamente.');
  $token=text_input('token',128,true);if(strlen((string)($cfg['setup_token']??''))<32||!hash_equals($cfg['setup_token'],$token))throw new InvalidArgumentException('Chave de instalação inválida. Consulte seu arquivo app/config.php.');
  $user=text_input('usuario',80,true);$pass=$_POST['senha']??'';
  if(!preg_match('/^[a-zA-Z0-9._-]{3,80}$/D',$user))throw new InvalidArgumentException('Use um usuário com 3 a 80 letras, números, ponto, hífen ou sublinhado.');
  if(!is_string($pass)||strlen($pass)<12||strlen($pass)>72)throw new InvalidArgumentException('Use senha de 12 a 72 caracteres.');
  q('INSERT INTO site_admins (id,username,password_hash,created_at) VALUES (1,?,?,?)',[$user,password_hash($pass,PASSWORD_DEFAULT),gmdate('Y-m-d H:i:s')]);
  session_regenerate_id(true);$_SESSION['admin_id']=1;$_SESSION['csrf']=bin2hex(random_bytes(32));header('Location: admin/index.php');exit;
 }
}catch(InvalidArgumentException $ex){$error=$ex->getMessage();}catch(Throwable $ex){$error='Confira a importação do banco.sql e a configuração do banco. Se a instalação já foi concluída, acesse o painel.';}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Instalar meu site</title><link rel="stylesheet" href="admin/admin.css"></head><body><main class="auth"><h1>Instalar meu site</h1><p>Crie seu administrador. A chave de instalação está em app/config.php.</p><?php if($error):?><p role="alert"><?=e($error)?></p><?php endif;?><form method="post"><?=csrf_field()?><label>Chave de instalação<input name="token" maxlength="128" required autocomplete="off"></label><label>Usuário<input name="usuario" maxlength="80" required autocomplete="username"></label><label>Senha <small>12 a 72 caracteres</small><input name="senha" type="password" minlength="12" maxlength="72" required autocomplete="new-password"></label><button>Concluir instalação</button></form><p>Depois de concluir, remova instalar.php da hospedagem.</p></main></body></html>
