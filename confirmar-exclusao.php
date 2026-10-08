<?php
require_once __DIR__ . '/src/bootstrap.php';
require_auth();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel='icon' type='image/png' sizes='32x32' href='img/favicon-32x32.png?v=20261007'>
    <link rel='icon' type='image/png' sizes='192x192' href='img/favicon-192x192.png?v=20261007'>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico?v=20261007'>
    <link rel='apple-touch-icon' sizes='180x180' href='img/apple-touch-icon.png?v=20261007'>
    <meta charset="UTF-8" />
    <title>Confirmar Exclusão - Mercado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            width: 320px;
        }
        button, .btn-cancelar {
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-confirmar {
            background-color: #d9534f;
            color: white;
        }
        .btn-confirmar:hover {
            background-color: #c9302c;
        }
        .btn-cancelar {
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            display: inline-block;
            line-height: 32px;
        }
        .btn-cancelar:hover {
            background-color: #bbb;
        }
    </style>
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
</head>
<body>
    <div class="box">
        <h2>Confirmação de Exclusão</h2>
        <p>Tem certeza que deseja excluir seu perfil? Esta ação não pode ser desfeita.</p>
        
        <form action="excluir-perfil.php" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="btn-confirmar">Sim, excluir meu perfil</button>
            <a href="perfil.php" class="btn-cancelar">Cancelar</a>
        </form>
    </div>
</body>
</html>
