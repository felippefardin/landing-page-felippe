<?php
declare(strict_types=1);

require_once __DIR__ . '/src/google_oauth.php';

try {
    header('Cache-Control: no-store');
    header('Location: ' . google_oauth_authorization_url());
    exit;
} catch (Throwable $e) {
    $_SESSION['google_login_error'] = $e->getMessage();
    header('Location: login.php?google=erro');
    exit;
}
