<?php
declare(strict_types=1);
require dirname(__DIR__).'/app/bootstrap.php';header('Cache-Control: no-store');$error='';
if(!empty($_SESSION['admin_id'])){header('Location: index.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();try{
  if(!rate('login',5,900))throw new InvalidArgumentException('Limite de tentativas atingido. Aguarde 15 minutos.');
  $user=text_input('usuario',80,true);$pass=$_POST['senha']??'';if(!is_string($pass)||strlen($pass)>72)throw new InvalidArgumentException('Usuário ou senha inválidos.');
  $row=q('SELECT id,password_hash FROM site_admins WHERE username=?',[$user])->fetch();
  $valid=password_verify($pass,$row['password_hash']??'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi');
  if(!$row||!$valid)throw new InvalidArgumentException('Usuário ou senha inválidos.');
  session_regenerate_id(true);$_SESSION['admin_id']=(int)$row['id'];$_SESSION['csrf']=bin2hex(random_bytes(32));header('Location: index.php');exit;
 }catch(InvalidArgumentException $ex){$error=$ex->getMessage();}catch(Throwable $ex){$error='Não foi possível entrar. Confira a instalação do site.';}
}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Entrar no painel</title><link rel="stylesheet" href="admin.css"></head><body><main class="auth"><h1>Entrar no painel</h1><?php if($error):?><p role="alert"><?=e($error)?></p><?php endif;?><form method="post"><?=csrf_field()?><label>Usuário<input name="usuario" maxlength="80" autocomplete="username" required></label><label>Senha<input name="senha" type="password" maxlength="72" autocomplete="current-password" required></label><button>Entrar</button></form><p><a href="../index.php">Voltar ao site</a></p></main></body></html>
