<?php
session_start();
$conn = new mysqli("localhost", "root", "", "felippe");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioNome = $_SESSION['usuario'];

// Buscar dados do usuário no banco
$query = "SELECT * FROM usuarios WHERE nome = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $usuarioNome);
$stmt->execute();
$result = $stmt->get_result();
$dadosUsuario = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novoNome = $_POST['nome'];
    $novoEmail = $_POST['email'];
    $novoWhatsapp = $_POST['whatsapp'];

    if (!empty($_POST['senha'])) {
        $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $queryUpdate = "UPDATE usuarios SET nome = ?, email = ?, whatsapp = ?, senha = ? WHERE nome = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("sssss", $novoNome, $novoEmail, $novoWhatsapp, $novaSenha, $usuarioNome);
    } else {
        $queryUpdate = "UPDATE usuarios SET nome = ?, email = ?, whatsapp = ? WHERE nome = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("ssss", $novoNome, $novoEmail, $novoWhatsapp, $usuarioNome);
    }

    if ($stmtUpdate->execute()) {
        $_SESSION['usuario'] = $novoNome;
        header("Location: perfil.php?editado=1");
        exit;
    } else {
        $erro = "Erro ao atualizar as informações.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Mercado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #00a859;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #007d42;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
        .sucesso {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-excluir {
            display: inline-block;
            margin-top: 15px;
            background-color: #d9534f;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }
        .btn-excluir:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Perfil de <?= htmlspecialchars($dadosUsuario['nome'] ?? '') ?></h2>

        <?php if (isset($erro)) : ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['editado']) && $_GET['editado'] == 1) : ?>
            <p class="sucesso">Perfil editado com sucesso!</p>
        <?php endif; ?>

        <form method="POST" action="perfil.php">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($dadosUsuario['nome'] ?? '') ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($dadosUsuario['email'] ?? '') ?>" required>

            <label for="whatsapp">WhatsApp:</label>
            <input type="text" name="whatsapp" value="<?= htmlspecialchars($dadosUsuario['whatsapp'] ?? '') ?>" required>

            <label for="senha">Nova Senha (se desejar mudar):</label>
            <input type="password" name="senha" id="senha">

            <button type="submit">Salvar Alterações</button>
        </form>

        <p><a href="admin.php">Voltar ao painel</a></p>

        <a href="confirmar-exclusao.php" class="btn-excluir">Excluir Perfil</a>
    </div>
</body>
</html>
