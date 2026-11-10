<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';require __DIR__.'/app/render.php';
header('Cache-Control: no-store');
try{
 $p=settings();$html=sb_html($p);
 $flash=$_SESSION['contact_flash']??'';unset($_SESSION['contact_flash']);
 $form='<section id="mensagem" class="section contact"><div class="shell"><h2>Envie uma mensagem</h2>'.($flash?'<p role="status">'.e($flash).'</p>':'').'<form action="contato.php" method="post" class="contact-form">'.csrf_field().'<label>Nome<input name="nome" maxlength="100" minlength="2" autocomplete="name" required></label><label>E-mail<input name="email" type="email" maxlength="254" autocomplete="email" required></label><label>Mensagem<textarea name="mensagem" minlength="10" maxlength="3000" rows="5" required></textarea></label><div class="contact-honey" aria-hidden="true"><label>Deixe vazio<input name="website" tabindex="-1" autocomplete="off"></label></div><p class="contact-privacy">Seu nome, e-mail e mensagem serão armazenados para responder ao contato. Não envie senhas ou dados sensíveis por este formulário.</p><label class="contact-consent"><input type="checkbox" name="aceite" value="1" required> Li o aviso sobre o uso dos dados para atendimento.</label><button type="submit" class="button">Enviar mensagem</button></form></div></section>';
 echo str_replace('<!--CONTACT_FORM-->',in_array('contact',$p['sections'],true)?$form:'',$html);
}catch(Throwable $ex){error_log('Public site: '.get_class($ex));http_response_code(503);echo 'Site em configuração. Confira a instalação e o banco de dados.';}
