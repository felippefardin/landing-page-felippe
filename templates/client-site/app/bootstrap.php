<?php
declare(strict_types=1);
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('X-Frame-Options: SAMEORIGIN');
$configPath=__DIR__.'/config.php';
if(!is_file($configPath)){http_response_code(503);exit('Instalação pendente: copie app/config.example.php para app/config.php, preencha os dados e siga INSTALACAO.md.');}
$cfg=require $configPath;
if(!is_array($cfg) || strlen((string)($cfg['app_key']??''))<32){http_response_code(503);exit('Confira a chave da aplicação em app/config.php.');}
date_default_timezone_set('America/Sao_Paulo');
session_name('site_'.substr(hash('sha256',__DIR__),0,12));
session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off','samesite'=>'Lax','path'=>'/']);
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
function e(mixed $v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function db():PDO{static $db;if($db instanceof PDO)return $db;global $cfg;$db=new PDO('mysql:host='.$cfg['db_host'].';port='.($cfg['db_port']??3306).';dbname='.$cfg['db_name'].';charset=utf8mb4',$cfg['db_user'],$cfg['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);return $db;}
function q(string $sql,array $args=[]):PDOStatement{$s=db()->prepare($sql);$s->execute($args);return $s;}
function csrf():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.e(csrf()).'">';}
function verify_csrf():void{if(!is_string($_POST['csrf']??null)||!hash_equals(csrf(),$_POST['csrf'])){http_response_code(403);exit('A sessão expirou. Atualize a página e tente novamente.');}}
function admin():void{header('Cache-Control: no-store');if(empty($_SESSION['admin_id'])){header('Location: login.php');exit;}}
function settings():array{$row=q('SELECT data FROM site_settings WHERE id=1')->fetch();if(!$row)throw new RuntimeException('Importe o arquivo banco.sql.');return json_decode($row['data'],true,32,JSON_THROW_ON_ERROR);}
function rate(string $scope,int $limit,int $seconds):bool{
    global $cfg;$key=hash_hmac('sha256',$scope.'|'.($_SERVER['REMOTE_ADDR']??'unknown'),$cfg['app_key']);$bucket=(int)floor(time()/$seconds);
    q('INSERT INTO site_rate_limits (bucket_key,time_bucket,hits) VALUES (?,?,1) ON DUPLICATE KEY UPDATE hits=hits+1',[$key,$bucket]);
    $row=q('SELECT hits FROM site_rate_limits WHERE bucket_key=? AND time_bucket=?',[$key,$bucket])->fetch();
    q('DELETE FROM site_rate_limits WHERE time_bucket<? AND bucket_key=?',[$bucket-2,$key]);return (int)$row['hits']<=$limit;
}
function text_input(string $key,int $max,bool $required=false):string{$v=$_POST[$key]??'';if(!is_string($v)||mb_strlen($v)>$max||($required&&trim($v)===''))throw new InvalidArgumentException('Confira o campo '.$key.'.');return trim($v);}
