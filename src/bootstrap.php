<?php
declare(strict_types=1);

function load_env_file(string $path): void
{
    if (!is_file($path) || !is_readable($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");
        if ($name !== '' && getenv($name) === false) {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
        }
    }
}

load_env_file(dirname(__DIR__) . '/.env');

$legacyConfig = dirname(__DIR__) . '/config.php';
if (is_file($legacyConfig)) {
    require_once $legacyConfig;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function env_value(string $name, ?string $default = null): ?string
{
    $value = getenv($name);
    return $value === false ? $default : $value;
}

function db(): mysqli
{
    static $connection;
    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli(
        env_value('DB_HOST', defined('DB_HOST') ? (string) DB_HOST : 'localhost'),
        env_value('DB_USER', defined('DB_USER') ? (string) DB_USER : 'root'),
        env_value('DB_PASS', defined('DB_PASS') ? (string) DB_PASS : ''),
        env_value('DB_NAME', defined('DB_NAME') ? (string) DB_NAME : 'felippe')
    );
    $connection->set_charset('utf8mb4');
    return $connection;
}

function require_auth(): void
{
    if (empty($_SESSION['usuario'])) {
        header('Location: login.php?session=expired');
        exit;
    }

    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (!is_string($submitted) || !hash_equals(csrf_token(), $submitted)) {
        http_response_code(403);
        exit('Requisi&ccedil;&atilde;o inv&aacute;lida. Atualize a p&aacute;gina e tente novamente.');
    }
}

function smtp_config(): array
{
    $username = env_value('SMTP_USERNAME', defined('SMTP_USERNAME') ? (string) SMTP_USERNAME : null);
    $password = env_value('SMTP_PASSWORD', defined('SMTP_PASSWORD') ? (string) SMTP_PASSWORD : null);
    if (!$username || !$password) {
        throw new RuntimeException('SMTP n&atilde;o configurado no servidor.');
    }

    return [
        'host' => env_value('SMTP_HOST', defined('SMTP_HOST') ? (string) SMTP_HOST : 'smtp.gmail.com'),
        'port' => (int) env_value('SMTP_PORT', defined('SMTP_PORT') ? (string) SMTP_PORT : '587'),
        'username' => $username,
        'password' => $password,
        'from_email' => env_value('SMTP_FROM_EMAIL', defined('SMTP_FROM_EMAIL') ? (string) SMTP_FROM_EMAIL : $username),
        'from_name' => env_value('SMTP_FROM_NAME', defined('SMTP_FROM_NAME') ? (string) SMTP_FROM_NAME : 'Tech Tecnologia'),
    ];
}
