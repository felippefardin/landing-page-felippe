<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mercado");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    

    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['nome'];
            header("Location: admin.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Mercado</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'senha_atualizada'): ?>
    <p style="color: green;">Senha atualizada com sucesso! Faça login abaixo.</p>
<?php endif; ?>

        <?php if (!empty($erro)) : ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Seu e-mail" required>
            <input type="password" name="senha" placeholder="Sua senha" required>
            <button type="submit">Entrar</button>
            <p><a href="formulario_teste.php">Esqueci minha senha?</a></p>
        </form>

        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        
    </div>
</body>
</html>
