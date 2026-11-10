<?php
declare(strict_types=1);
require_once __DIR__.'/src/builder-commerce.php';
header('Cache-Control: no-store, private');header('Referrer-Policy: no-referrer');header('X-Content-Type-Options: nosniff');
try{
    $db=bc_db();$id=is_string($_GET['id']??null)?$_GET['id']:'';$token=is_string($_GET['token']??null)?$_GET['token']:'';$db->begin_transaction();$o=bc_order($db,$id,true);bc_download_access($o,$token);
    $archive=memory_sql($db,'SELECT zip_data FROM builder_archives WHERE order_id=? AND purge_at>?','ss',[$id,memory_now()])->get_result()->fetch_assoc();
    if(!$archive || !hash_equals($o['zip_hash'],hash('sha256',$archive['zip_data'])))throw new RuntimeException('Arquivo não disponível.');
    memory_sql($db,'UPDATE builder_orders SET downloads=downloads+1 WHERE id=?','s',[$id]);$db->commit();
    header('Content-Type: application/zip');header('Content-Disposition: attachment; filename="meu-site.zip"');header('Content-Length: '.strlen($archive['zip_data']));echo $archive['zip_data'];
}catch(Throwable $e){if(isset($db))$db->rollback();http_response_code(403);header('Content-Type: text/plain; charset=utf-8');echo 'O arquivo não está disponível. Confira o link, a aprovação do pagamento e o prazo de 7 dias. Fale com o atendimento se precisar de ajuda.';}
