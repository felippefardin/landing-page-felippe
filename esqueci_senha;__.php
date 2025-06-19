<?php
session_start();
$conn = new mysqli("localhost", "root", "", "felippe");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entrada = $conn->real_escape_string($_POST['email']);

    // Consulta para verificar se o e-mail existe no banco
    $resultado = $conn->query("SELECT * FROM usuarios WHERE email = '$entrada'");
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['redefinir_id'] = $usuario['id'];

        // Gerando um código de recuperação aleatório (pode ser numérico ou alfanumérico)
        $codigo_recuperacao = mt_rand(100000, 999999); // Código de 6 dígitos

        // Inserindo o código na tabela recuperacao_senha
        $sql = "INSERT INTO recuperacao_senha (usuario_id, codigo_recuperacao, data_solicitacao)
                VALUES ('" . $usuario['id'] . "', '$codigo_recuperacao', NOW())";

        if ($conn->query($sql) === TRUE) {
            // Se a inserção for bem-sucedida, redireciona para a página de verificação
            header("Location: verificar_codigo.php");
            exit;
        } else {
            $mensagem = "Erro ao gerar o código de recuperação.";
        }

    } else {
        $mensagem = "Nenhum usuário encontrado com esse e-mail ou telefone.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <style>
        /* Estilos para a página */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .recuperar-senha {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .recuperar-senha h2 {
            margin-bottom: 24px;
        }

        .recuperar-senha input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .recuperar-senha button {
            padding: 12px 24px;
            font-size: 16px;
            background-color: #2a9d8f;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .recuperar-senha button:hover {
            background-color: #21867a;
        }

        .recuperar-senha p {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="recuperar-senha">
        <h2>Esqueci minha senha</h2>
        <?php if ($mensagem) echo "<p>$mensagem</p>"; ?>
        <form method="POST" action="">
            <label for="usuario">Digite seu e-mail:</label>
            <input type="text" id="usuario" name="email" required>
            <button type="submit">Enviar código</button>
        </form>
    </div>
</body>
</html>
