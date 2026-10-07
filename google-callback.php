<?php
declare(strict_types=1);

require_once __DIR__ . '/src/google_oauth.php';

try {
    $state = (string) ($_GET['state'] ?? '');
    $expectedState = (string) ($_SESSION['google_oauth_state'] ?? '');
    $verifier = (string) ($_SESSION['google_oauth_verifier'] ?? '');
    $startedAt = (int) ($_SESSION['google_oauth_started_at'] ?? 0);
    unset($_SESSION['google_oauth_state'], $_SESSION['google_oauth_verifier'], $_SESSION['google_oauth_started_at']);

    if ($state === '' || $expectedState === '' || !hash_equals($expectedState, $state) || $verifier === '' || $startedAt < time() - 600) {
        throw new RuntimeException('A tentativa de login expirou. Tente novamente.');
    }
    if (!empty($_GET['error'])) {
        throw new RuntimeException('O acesso com Google foi cancelado.');
    }
    $code = trim((string) ($_GET['code'] ?? ''));
    if ($code === '') {
        throw new RuntimeException('O Google não retornou a autorização de acesso.');
    }

    $token = google_oauth_exchange_code($code, $verifier);
    $accessToken = trim((string) ($token['access_token'] ?? ''));
    if ($accessToken === '') {
        throw new RuntimeException('Não foi possível concluir a autenticação com Google.');
    }
    $profile = google_oauth_userinfo($accessToken);
    google_oauth_login_user($profile);
    header('Location: home.php');
    exit;
} catch (Throwable $e) {
    error_log('Google OAuth: ' . $e->getMessage());
    $_SESSION['google_login_error'] = $e->getMessage();
    header('Location: login.php?google=erro');
    exit;
}
