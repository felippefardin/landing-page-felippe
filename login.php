<?php
require_once __DIR__ . '/src/bootstrap.php';
$conn = db();

$erro = "";
if (isset($_GET['google']) && $_GET['google'] === 'erro') {
    $erro = (string) ($_SESSION['google_login_error'] ?? 'Não foi possível entrar com Google. Tente novamente.');
    unset($_SESSION['google_login_error']);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Usar prepared statement para segurança
    $stmt = $conn->prepare("SELECT nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $usuario['nome'];
            header("Location: home.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel='icon' type='image/png' sizes='32x32' href='img/favicon-32x32.png?v=20261007'>
    <link rel='icon' type='image/png' sizes='192x192' href='img/favicon-192x192.png?v=20261007'>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico?v=20261007'>
    <link rel='apple-touch-icon' sizes='180x180' href='img/apple-touch-icon.png?v=20261007'>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Tech Tecnologia</title>
    <link rel="stylesheet" href="css/login.css?v=20260814g" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
</head>
<body>

    <div class="container">
        <h2>Login</h2>

        <?php if (isset($_GET['logout']) && $_GET['logout'] === '1'): ?>
            <div class="alert success" id="msg-alert" role="status">Você saiu da sua conta com segurança.</div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'senha_atualizada'): ?>
            <div class="alert success" id="msg-alert">Senha atualizada com sucesso! Faça login abaixo.</div>
        <?php endif; ?>

        <?php if (!empty($erro)) : ?>
            <div class="alert error" id="msg-alert"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off" novalidate>
            <label for="email" class="sr-only">E-mail</label>
            <input type="email" id="email" name="email" placeholder="Seu e-mail" required autofocus>

            <label for="senha" class="sr-only">Senha</label>
            <div class="password-wrapper">
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
                <button type="button" class="toggle-password" aria-label="Mostrar senha" onclick="toggleSenha()">
                    <i id="icone-olho" class="fa fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="btn-login">Entrar</button>

            <p class="links">
                <a href="formulario_teste.php">Esqueci minha senha?</a>
            </p>
        </form>

        <div class="oauth-divider"><span>ou</span></div>
        <a class="google-login-button" href="google-login.php" aria-label="Continuar com uma conta Google">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.07H12v3.92h5.38a4.6 4.6 0 0 1-2 3.02v2.55h3.24c1.9-1.75 2.98-4.33 2.98-7.42Z"/><path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.62-2.35l-3.24-2.55c-.9.6-2.05.96-3.38.96-2.6 0-4.81-1.76-5.6-4.13H3.06v2.63A10 10 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.4 13.93A6 6 0 0 1 6.09 12c0-.67.12-1.32.31-1.93V7.44H3.06A10 10 0 0 0 2 12c0 1.61.39 3.14 1.06 4.56l3.34-2.63Z"/><path fill="#EA4335" d="M12 5.94c1.47 0 2.79.51 3.83 1.5l2.87-2.88A9.64 9.64 0 0 0 12 2a10 10 0 0 0-8.94 5.44l3.34 2.63C7.19 7.7 9.4 5.94 12 5.94Z"/></svg>
            <span>Continuar com Google</span>
        </a>

        <p class="signup-text">
            Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
        </p>
        <p><a href="termos-lgpd.html" target="_blank" rel="noopener noreferrer">Termos LGPD</a></p>
    </div>

<script>
function toggleSenha() {
    const senhaInput = document.getElementById('senha');
    const icone = document.getElementById('icone-olho');

    if (senhaInput.type === "password") {
        senhaInput.type = "text";
        icone.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        senhaInput.type = "password";
        icone.classList.replace("fa-eye-slash", "fa-eye");
    }
}

// Faz a mensagem desaparecer após 3 segundos com efeito fade
window.addEventListener('DOMContentLoaded', () => {
    const msg = document.getElementById('msg-alert');
    if (msg) {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.6s ease';
            msg.style.opacity = '0';
            setTimeout(() => {
                if(msg.parentNode) msg.parentNode.removeChild(msg);
            }, 600);
        }, 3000);
    }
});
</script>

</body>
</html>
