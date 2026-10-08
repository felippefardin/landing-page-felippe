<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel='icon' type='image/png' sizes='32x32' href='img/favicon-32x32.png?v=20261007'>
    <link rel='icon' type='image/png' sizes='192x192' href='img/favicon-192x192.png?v=20261007'>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico?v=20261007'>
    <link rel='apple-touch-icon' sizes='180x180' href='img/apple-touch-icon.png?v=20261007'>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recuperação de Senha - Mercado</title>
    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
    <link rel="stylesheet" href="css/public-modern.css?v=20260814c">
    <script src="js/public-shell.js?v=20260814b" defer></script>
</head>
<body>
    <div class="container">
        <h2>Recuperação de Senha</h2>

        <form action="enviar_codigo.php" method="POST" autocomplete="off" novalidate>
            <label for="usuario" class="sr-only">E-mail</label>
            <input type="email" id="usuario" name="usuario" placeholder="Seu e-mail" required autofocus>

            <button type="submit" class="btn-login">Enviar Código</button>
        </form>

        <p class="signup-text">
            <a href="login.php">Voltar para o login</a>
        </p>
    </div>
</body>
</html>
