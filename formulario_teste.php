<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recuperação de Senha - Mercado</title>
    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
