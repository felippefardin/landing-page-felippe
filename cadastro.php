<?php
session_start();

$conn = new mysqli ("localhost", "root", "", "felippe");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $celular = mysqli_real_escape_string($conn, $_POST['celular']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $erro = "Este e-mail já está cadastrado!";
    } else {
        $sql_insert = "INSERT INTO usuarios (nome, email, celular, senha) VALUES ('$nome', '$email', '$celular', '$senha_hash')";

        
        if ($conn->query($sql_insert) === TRUE) {
            $_SESSION['msg'] = "Cadastro realizado com sucesso!";
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar o usuário. Tente novamente.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Mercado</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>

        <?php if ($erro) : ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST" action="cadastro.php">
            <input type="text" name="nome" placeholder="Seu nome" required>
            <input type="email" name="email" placeholder="Seu e-mail" required>
            <input type="text" name="celular" id="celular" placeholder="Seu celular" required>
            <input type="password" name="senha" placeholder="Sua senha" required>
            <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
    <script>
document.getElementById('celular').addEventListener('input', function (e) {
    let input = e.target;
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito

    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 10) {
        input.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
    } else if (value.length > 6) {
        input.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
    } else if (value.length > 2) {
        input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
    } else if (value.length > 0) {
        input.value = `(${value}`;
    }
});
</script>

</body>
</html>
