<?php
session_start();

$conn = new mysqli("localhost", "root", "", "felippe");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$erro = "";

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Tech Tecnologia</title>
    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

    <div class="container">
        <h2>Login</h2>

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
