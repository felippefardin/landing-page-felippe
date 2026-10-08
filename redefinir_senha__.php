<?php
require_once __DIR__ . '/src/bootstrap.php';
$conn = db();

if (!isset($_SESSION['redefinir_id'])) {
    header("Location: login.php");
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novaSenha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
    $id = $_SESSION['redefinir_id'];

    $conn->query("UPDATE usuarios SET senha = '$novaSenha' WHERE id = $id");
    unset($_SESSION['redefinir_id']);
    header("Location: login.php?senha_redefinida=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel='icon' type='image/png' sizes='32x32' href='img/favicon-32x32.png?v=20261007'>
    <link rel='icon' type='image/png' sizes='192x192' href='img/favicon-192x192.png?v=20261007'>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico?v=20261007'>
    <link rel='apple-touch-icon' sizes='180x180' href='img/apple-touch-icon.png?v=20261007'>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
    <link rel="stylesheet" href="css/public-modern.css?v=20260814c">
    <script src="js/public-shell.js?v=20260814b" defer></script>
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form method="POST">
        <label>Nova Senha:</label><br>
        <input type="password" name="nova_senha" required><br><br>
        <button type="submit">Redefinir</button>
    </form>
</body>
</html>
