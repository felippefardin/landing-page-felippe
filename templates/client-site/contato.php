<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Allow: POST');http_response_code(405);exit;}
verify_csrf();
try{
 if(!rate('contact',10,3600))throw new InvalidArgumentException('Limite de mensagens atingido. Tente novamente mais tarde.');
 if(($_POST['website']??'')!=='')throw new InvalidArgumentException('Não foi possível enviar a mensagem.');
 $name=text_input('nome',100,true);$email=text_input('email',254,true);$message=text_input('mensagem',3000,true);
 if(mb_strlen($name)<2||mb_strlen($message)<10||!filter_var($email,FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException('Confira nome, e-mail e mensagem antes de enviar.');
 if(($_POST['aceite']??'')!=='1')throw new InvalidArgumentException('Leia o aviso sobre o uso dos dados para atendimento.');
 q('INSERT INTO site_messages (nome,email,mensagem,status,created_at) VALUES (?,?,?,\'new\',?)',[$name,$email,$message,gmdate('Y-m-d H:i:s')]);
 $_SESSION['contact_flash']='Mensagem recebida! Obrigado pelo contato.';
}catch(InvalidArgumentException $ex){$_SESSION['contact_flash']=$ex->getMessage();}
catch(Throwable $ex){error_log('Contact save: '.get_class($ex));$_SESSION['contact_flash']='Não foi possível salvar sua mensagem. Tente novamente ou use os outros contatos do site.';}
header('Location: index.php#mensagem');exit;
