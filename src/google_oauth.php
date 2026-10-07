<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function google_oauth_config(): array
{
    $clientId = trim((string) env_value('GOOGLE_CLIENT_ID', ''));
    $clientSecret = trim((string) env_value('GOOGLE_CLIENT_SECRET', ''));
    $redirectUri = trim((string) env_value('GOOGLE_REDIRECT_URI', ''));

    if ($clientId === '' || $clientSecret === '' || $redirectUri === '') {
        throw new RuntimeException('O login com Google ainda não foi configurado pelo administrador.');
    }

    return compact('clientId', 'clientSecret', 'redirectUri');
}

function google_oauth_authorization_url(): string
{
    $config = google_oauth_config();
    $state = bin2hex(random_bytes(32));
    $verifier = rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '=');
    $challenge = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');

    $_SESSION['google_oauth_state'] = $state;
    $_SESSION['google_oauth_verifier'] = $verifier;
    $_SESSION['google_oauth_started_at'] = time();

    return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id' => $config['clientId'],
        'redirect_uri' => $config['redirectUri'],
        'response_type' => 'code',
        'scope' => 'openid email profile',
        'state' => $state,
        'code_challenge' => $challenge,
        'code_challenge_method' => 'S256',
        'prompt' => 'select_account',
    ], '', '&', PHP_QUERY_RFC3986);
}

function google_oauth_request(string $url, array $options = []): array
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('A extensão cURL do PHP precisa estar habilitada.');
    }

    $ch = curl_init($url);
    $defaults = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ];
    curl_setopt_array($ch, array_replace($defaults, $options));
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($body === false || $error !== '' || $status < 200 || $status >= 300) {
        throw new RuntimeException('Não foi possível validar a conta com o Google.');
    }

    $decoded = json_decode($body, true);
    if (!is_array($decoded)) {
        throw new RuntimeException('O Google retornou uma resposta inválida.');
    }
    return $decoded;
}

function google_oauth_exchange_code(string $code, string $verifier): array
{
    $config = google_oauth_config();
    return google_oauth_request('https://oauth2.googleapis.com/token', [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'code' => $code,
            'client_id' => $config['clientId'],
            'client_secret' => $config['clientSecret'],
            'redirect_uri' => $config['redirectUri'],
            'grant_type' => 'authorization_code',
            'code_verifier' => $verifier,
        ], '', '&', PHP_QUERY_RFC3986),
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'],
    ]);
}

function google_oauth_userinfo(string $accessToken): array
{
    return google_oauth_request('https://openidconnect.googleapis.com/v1/userinfo', [
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Authorization: Bearer ' . $accessToken],
    ]);
}

function google_oauth_login_user(array $profile): void
{
    $sub = trim((string) ($profile['sub'] ?? ''));
    $email = strtolower(trim((string) ($profile['email'] ?? '')));
    $name = trim((string) ($profile['name'] ?? ''));
    $verified = filter_var($profile['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($sub === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$verified) {
        throw new RuntimeException('A conta Google precisa possuir um e-mail verificado.');
    }
    if ($name === '') {
        $name = strstr($email, '@', true) ?: 'Usuário';
    }

    $conn = db();
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare('SELECT id, nome, email, google_sub FROM usuarios WHERE google_sub = ? OR email = ? LIMIT 1 FOR UPDATE');
        $stmt->bind_param('ss', $sub, $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            if (!empty($user['google_sub']) && !hash_equals((string) $user['google_sub'], $sub)) {
                throw new RuntimeException('Este e-mail já está vinculado a outra conta Google.');
            }
            if (empty($user['google_sub'])) {
                $stmt = $conn->prepare("UPDATE usuarios SET google_sub = ?, auth_provider = 'google', email_verificado_em = COALESCE(email_verificado_em, NOW()) WHERE id = ?");
                $userId = (int) $user['id'];
                $stmt->bind_param('si', $sub, $userId);
                $stmt->execute();
                $stmt->close();
            }
            $userId = (int) $user['id'];
            $name = (string) $user['nome'];
        } else {
            $randomPassword = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);
            $emptyPhone = '';
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, celular, senha, auth_provider, google_sub, email_verificado_em) VALUES (?, ?, ?, ?, 'google', ?, NOW())");
            $stmt->bind_param('sssss', $name, $email, $emptyPhone, $randomPassword, $sub);
            $stmt->execute();
            $userId = (int) $conn->insert_id;
            $stmt->close();
        }
        $conn->commit();
    } catch (Throwable $e) {
        $conn->rollback();
        throw $e;
    }

    session_regenerate_id(true);
    $_SESSION['usuario'] = $name;
    $_SESSION['usuario_id'] = $userId;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['auth_provider'] = 'google';
}
