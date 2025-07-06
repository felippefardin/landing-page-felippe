<?php
// Dados do banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "felippe";

// Conexão
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Recebe e valida o e-mail
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("E-mail inválido.");
}

// Prepara e executa a inserção
$stmt = $conn->prepare("INSERT IGNORE INTO email_cliente (email) VALUES (?)");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    // Redireciona para a página de listagem
    header("Location: email-recebidos.php");
    exit;
} else {
    echo "Erro ao salvar o e-mail: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
