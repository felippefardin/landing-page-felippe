<?php
session_start();


$conn = new mysqli ("localhost", "root", "", "felippe");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $celular = mysqli_real_escape_string($conn, $_POST['celular']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $erro = "Este e-mail já está cadastrado!";
    } else {
        $sql_insert = "INSERT INTO usuarios (nome, email, celular, senha) VALUES ('$nome', '$email', '$celular', '$senha_hash')";

        
        if ($conn->query($sql_insert) === TRUE) {
            $_SESSION['msg'] = "Cadastro realizado com sucesso!";
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar o usuário. Tente novamente.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Mercado</title>
    <link rel="stylesheet" href="cadastro.css">
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
    <link rel="stylesheet" href="css/public-modern.css?v=20260814c">
    <script src="js/public-shell.js?v=20260814b" defer></script>
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>

        <?php if ($erro) : ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        

        <a class="google-login-button" href="google-login.php" aria-label="Criar conta usando uma conta Google">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.07H12v3.92h5.38a4.6 4.6 0 0 1-2 3.02v2.55h3.24c1.9-1.75 2.98-4.33 2.98-7.42Z"/><path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.62-2.35l-3.24-2.55c-.9.6-2.05.96-3.38.96-2.6 0-4.81-1.76-5.6-4.13H3.06v2.63A10 10 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.4 13.93A6 6 0 0 1 6.09 12c0-.67.12-1.32.31-1.93V7.44H3.06A10 10 0 0 0 2 12c0 1.61.39 3.14 1.06 4.56l3.34-2.63Z"/><path fill="#EA4335" d="M12 5.94c1.47 0 2.79.51 3.83 1.5l2.87-2.88A9.64 9.64 0 0 0 12 2a10 10 0 0 0-8.94 5.44l3.34 2.63C7.19 7.7 9.4 5.94 12 5.94Z"/></svg>
            <span>Criar conta com Google</span>
        </a>
        <div class="oauth-divider"><span>ou cadastre-se com e-mail</span></div>

        <form method="POST" action="cadastro.php">
            <input type="text" name="nome" placeholder="Seu nome" required>
            <input type="email" name="email" placeholder="Seu e-mail" required>
            <input type="text" name="celular" id="celular" placeholder="Seu celular" required>
            <input type="password" name="senha" placeholder="Sua senha" required>
            <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        <p class="oauth-legal">Ao continuar com Google, você confirma que leu os <a href="termos-lgpd.html" target="_blank" rel="noopener noreferrer">Termos e a Política de Privacidade</a>.</p>
    </div>
    <script>
document.getElementById('celular').addEventListener('input', function (e) {
    let input = e.target;
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito

    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 10) {
        input.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
    } else if (value.length > 6) {
        input.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
    } else if (value.length > 2) {
        input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
    } else if (value.length > 0) {
        input.value = `(${value}`;
    }
});
</script>

</body>
</html>
