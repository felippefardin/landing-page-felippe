<?php
require dirname(__DIR__).'/app/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}
verify_csrf();$_SESSION=[];session_destroy();header('Location: login.php');exit;
