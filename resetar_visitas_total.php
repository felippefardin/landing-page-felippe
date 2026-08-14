<?php
require_once __DIR__ . '/src/bootstrap.php';
require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}
verify_csrf();

file_put_contents('visitas_total.txt', 0);
header("Location: dashboard.php");
exit;
