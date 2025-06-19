<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
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
</head>
<body>
    <div class="box">
        <h2>Confirmação de Exclusão</h2>
        <p>Tem certeza que deseja excluir seu perfil? Esta ação não pode ser desfeita.</p>
        
        <form action="excluir-perfil.php" method="POST">
            <button type="submit" class="btn-confirmar">Sim, excluir meu perfil</button>
            <a href="perfil.php" class="btn-cancelar">Cancelar</a>
        </form>
    </div>
</body>
</html>
