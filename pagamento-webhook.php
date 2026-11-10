<?php
declare(strict_types=1);
require_once __DIR__.'/src/builder-commerce.php';
header('Content-Type: application/json');header('Cache-Control: no-store');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('{"error":"method"}');}
try{
    $raw=file_get_contents('php://input',false,null,0,65537);if(!is_string($raw)||strlen($raw)>65536){http_response_code(413);exit('{}');}
    $body=json_decode($raw,true,32,JSON_THROW_ON_ERROR);
    $id=is_string($_GET['data_id']??null)?$_GET['data_id']:'';
    if(!bc_webhook_valid($id,(string)($_SERVER['HTTP_X_REQUEST_ID']??''),(string)($_SERVER['HTTP_X_SIGNATURE']??''),memory_env('MP_WEBHOOK_SECRET'))){http_response_code(401);exit('{"error":"signature"}');}
    if(($body['type']??'')!=='payment'){http_response_code(200);exit('{}');}
    if((string)($body['data']['id']??'')!==$id){http_response_code(400);exit('{}');}
    $payment=bc_api('GET','/v1/payments/'.rawurlencode($id));bc_apply_payment(bc_db(),$payment);
    // Delivery is durable and processed by the CLI worker, keeping this response short.
    http_response_code(200);echo '{"received":true}';
}catch(Throwable $e){error_log('Builder webhook failed: '.get_class($e));http_response_code(503);echo '{"error":"retry"}';}
