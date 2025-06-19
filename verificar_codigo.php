<?php
session_start();
$conn = new mysqli("localhost", "root", "", "felippe");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_SESSION['recupera_email'];
    $codigo = $_POST['codigo'];

    $sql = "SELECT * FROM recuperacao_senha 
            WHERE email = ? AND codigo = ? AND usado = 0 AND expira_em > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensagem = "Código válido! Você será redirecionado em instantes...";
        $_SESSION['validado'] = true;
        // não redirecionamos imediatamente, deixamos o JS fazer isso após 2 segundos
    } else {
        $mensagem = "Código inválido ou expirado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
    <?php if ($mensagem === "Código válido! Você será redirecionado em instantes...") : ?>
    <script>
        setTimeout(function() {
            window.location.href = "nova_senha.php";
        }, 2000);
    </script>
    <?php endif; ?>
</head>
<body>
    <h2>Digite o Código</h2>

    <?php 
    if (!empty($mensagem)) {
        $cor = ($mensagem === "Código válido! Você será redirecionado em instantes...") ? "green" : "red";
        echo "<p style='color: $cor;'>$mensagem</p>";
    }
    ?>

    <?php if ($mensagem !== "Código válido! Você será redirecionado em instantes...") : ?>
    <form method="POST">
        <input type="text" name="codigo" placeholder="Código de 6 dígitos" required>
        <button type="submit">Verificar</button>
    </form>
    <?php endif; ?>

</body>
</html>
