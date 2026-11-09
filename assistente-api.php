<?php
declare(strict_types=1);
require_once __DIR__.'/src/assistente.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');
function assistant_reply(int $status,array $body): void {http_response_code($status);echo json_encode($body,JSON_UNESCAPED_UNICODE);exit;}
$config=assistant_config();
// Supported model validated for this free-tier project.
$config['model']='gemini-3.5-flash-lite';
if ($_SERVER['REQUEST_METHOD']==='GET') assistant_reply(200,['available'=>$config['key']!==''&&function_exists('curl_init'),'csrf_token'=>csrf_token()]);
if ($_SERVER['REQUEST_METHOD']!=='POST') {header('Allow: GET, POST');assistant_reply(405,['error'=>'method']);}
if ((int)($_SERVER['CONTENT_LENGTH']??0)>6000) assistant_reply(413,['error'=>'length']);
$input=json_decode(file_get_contents('php://input')?:'',true);
if (!is_array($input)) assistant_reply(400,['error'=>'input']);
$token=$input['csrf_token']??null;
if (!is_string($token)||!hash_equals(csrf_token(),$token)) assistant_reply(403,['error'=>'csrf']);
if ($config['key']==='') assistant_reply(503,['error'=>'unconfigured']);
if (($input['consent']??false)!==true) assistant_reply(422,['error'=>'consent']);
$message=$input['message']??null;
if (!is_string($message)||!preg_match('//u',$message)||strlen(trim($message))<2||strlen($message)>2000) assistant_reply(422,['error'=>'length']);
$language=$input['language']??'pt-BR';if (!in_array($language,['pt-BR','en','es'],true)) $language='pt-BR';
if (time()-(int)($_SESSION['assistant_last_request']??0)<5) assistant_reply(429,['error'=>'rate']);
if ((int)($_SESSION['assistant_request_count']??0)>=30) assistant_reply(429,['error'=>'rate']);
try {
    if (!assistant_budget($config['daily_limit'])) assistant_reply(429,['error'=>'budget']);
    $_SESSION['assistant_last_request']=time();$_SESSION['assistant_request_count']=(int)($_SESSION['assistant_request_count']??0)+1;
    assistant_reply(200,['answer'=>assistant_answer(trim($message),$language,$config),'mode'=>'ai']);
} catch(Throwable $error) {error_log('Site assistant unavailable: '.get_class($error));assistant_reply(503,['error'=>'provider']);}
