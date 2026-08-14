<?php
declare(strict_types=1);

require_once __DIR__ . '/src/bootstrap.php';

// Remove todos os dados da sessão atual.
$_SESSION = [];

// Remove também o cookie de sessão do navegador.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'] ?? 'Lax',
    ]);
}

session_destroy();

// Evita que o botão Voltar exiba uma página autenticada armazenada em cache.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
header('Location: login.php?logout=1', true, 303);
exit;
