<?php
require_once __DIR__ . '/src/bootstrap.php';
$conn = db();

$mensagem = "";
$tipoMensagem = ""; // success ou error

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['tentativas_codigo'] = (int) ($_SESSION['tentativas_codigo'] ?? 0) + 1;
    if ($_SESSION['tentativas_codigo'] > 5) {
        http_response_code(429);
        exit('Muitas tentativas. Solicite um novo código.');
    }
    $email = $_SESSION['recupera_email'] ?? '';
    $codigo = $_POST['codigo'] ?? '';

    $sql = "SELECT * FROM recuperacao_senha 
            WHERE email = ? AND codigo = ? AND usado = 0 AND expira_em > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensagem = "Código válido! Você será redirecionado em instantes...";
        $tipoMensagem = "success";
        $_SESSION['validado'] = true;
        $_SESSION['validado_em'] = time();
        $_SESSION['tentativas_codigo'] = 0;
        // O redirecionamento será feito via JS no final da página
    } else {
        $mensagem = "Código inválido ou expirado.";
        $tipoMensagem = "error";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verificar Código - Mercado</title>
    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <?php if ($tipoMensagem === "success") : ?>
    <script>
        setTimeout(() => {
            window.location.href = "nova_senha.php";
        }, 2000);
    </script>
    <?php endif; ?>
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
    <link rel="stylesheet" href="css/public-modern.css?v=20260814c">
    <script src="js/public-shell.js?v=20260814b" defer></script>
</head>
<body>
    <div class="container">
        <h2>Digite o Código</h2>

        <?php if (!empty($mensagem)) : ?>
            <div class="alert <?= $tipoMensagem === 'success' ? 'success' : 'error' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if ($tipoMensagem !== "success") : ?>
        <form method="POST" autocomplete="off" novalidate>
            <label for="codigo" class="sr-only">Código de 6 dígitos</label>
            <input type="text" id="codigo" name="codigo" placeholder="Código de 6 dígitos" required maxlength="6" pattern="\d{6}" autofocus>
            <button type="submit" class="btn-login">Verificar</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
