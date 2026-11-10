<?php
declare(strict_types=1);
require_once __DIR__.'/src/builder-commerce.php';
require_once __DIR__.'/src/builder-admin.php';require_auth();
header('Cache-Control: no-store, private');header('X-Content-Type-Options: nosniff');
try{$authorized=bc_admin();}catch(Throwable $e){http_response_code(503);header('Content-Type: text/plain; charset=utf-8');exit('Não foi possível consultar a conta administrativa. Confira a conexão com o banco.');}
if(!$authorized){http_response_code(403);header('Content-Type: text/plain; charset=utf-8');exit('Acesso restrito ao administrador do site.');}
try{
    $o=bc_order(bc_admin_db(),is_string($_GET['id']??null)?$_GET['id']:'');$pdf=bc_pdf($o);
    header('Content-Type: application/pdf');$disposition=($_GET['visualizar']??'')==='1'?'inline':'attachment';header('Content-Disposition: '.$disposition.'; filename="termo-'.$o['id'].'.pdf"');header('Content-Length: '.strlen($pdf));echo $pdf;
}catch(InvalidArgumentException $e){http_response_code(404);header('Content-Type: text/plain; charset=utf-8');echo 'Documento não encontrado. Volte à lista e selecione um registro existente.';}
catch(Throwable $e){error_log('Signed PDF failed: '.get_class($e));http_response_code(503);header('Content-Type: text/plain; charset=utf-8');echo 'Não foi possível gerar o PDF. Confira o registro do aceite e a instalação do módulo PDF. Não é necessário refazer o login.';}
