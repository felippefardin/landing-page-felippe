<?php
session_start();
if (!isset($_SESSION['recupera_email'])) {
    header("Location: esqueci_senha.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; max-width: 400px; margin: auto; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        button {  padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Defina sua nova senha</h2>
    <form method="POST" action="atualizar_senha.php">
        <input type="password" name="nova_senha" id="nova_senha" placeholder="Nova senha" required oninput="avaliarForca()">
        <div id="forca_senha" style="height: 10px; width: 100%; background: #e0e0e0; border-radius: 5px; margin-top: 5px;">
            <div id="barra_forca" style="height: 100%; width: 0%; background: red; border-radius: 5px;"></div>
        </div>
        <button type="submit">Atualizar Senha</button>
    </form>

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
    </script>
</body>
</html>
