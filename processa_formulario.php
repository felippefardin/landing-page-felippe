<?php
$conn = new mysqli("localhost", "root", "", "felippe");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$whatsapp = $_POST['whatsapp'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';

if ($nome && $email && $mensagem) {
    $stmt = $conn->prepare("INSERT INTO emails (nome, email, whatsapp, mensagem, data_envio) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $nome, $email, $whatsapp, $mensagem);
    $stmt->execute();
    $stmt->close();
    header("Location: obrigado.html");
    exit;
} else {
    echo "Por favor, preencha todos os campos.";
}
?>
