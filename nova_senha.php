<?php
session_start();
if (!isset($_SESSION['recupera_email'])) {
    header("Location: esqueci_senha.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nova Senha - Tech Tecnologia</title>
    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .forca-wrapper {
            height: 10px;
            width: 100%;
            background: #e0e0e0;
            border-radius: 5px;
            margin-top: 5px;
        }

        #barra_forca {
            height: 100%;
            width: 0%;
            border-radius: 5px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Defina sua nova senha</h2>

        <form method="POST" action="atualizar_senha.php" autocomplete="off" novalidate>
            <label for="nova_senha" class="sr-only">Nova senha</label>

            <div class="password-wrapper">
                <input type="password" name="nova_senha" id="nova_senha" placeholder="Nova senha" required oninput="avaliarForca()">
                <button type="button" class="toggle-password" aria-label="Mostrar senha" onclick="toggleSenha()">
                    <i id="icone-olho" class="fa fa-eye"></i>
                </button>
            </div>

            <div class="forca-wrapper">
                <div id="barra_forca"></div>
            </div>

            <button type="submit" class="btn-login">Atualizar Senha</button>
        </form>

        <p class="signup-text">
            <a href="login.php">Voltar para o login</a>
        </p>
    </div>

    <script>
        function avaliarForca() {
            const senha = document.getElementById("nova_senha").value;
            const barra = document.getElementById("barra_forca");

            let forca = 0;
            if (senha.length >= 6) forca += 1;
            if (/[A-Z]/.test(senha)) forca += 1;
            if (/[0-9]/.test(senha)) forca += 1;
            if (/[^A-Za-z0-9]/.test(senha)) forca += 1;

            const cores = ['red', 'orange', 'gold', 'green'];
            const larguras = ['25%', '50%', '75%', '100%'];

            barra.style.width = larguras[forca - 1] || '0%';
            barra.style.backgroundColor = cores[forca - 1] || 'transparent';
        }

        function toggleSenha() {
            const senhaInput = document.getElementById('nova_senha');
            const icone = document.getElementById('icone-olho');

            if (senhaInput.type === "password") {
                senhaInput.type = "text";
                icone.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                senhaInput.type = "password";
                icone.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>
</html>
