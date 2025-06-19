<?php
session_start();
$conn = new mysqli("localhost", "root", "", "felippe");

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
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
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
