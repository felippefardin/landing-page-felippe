<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_POST['id'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "felippe";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$id = (int) $_POST['id'];
$stmt = $conn->prepare("DELETE FROM email_cliente WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$conn->close();
header("Location: email-recebido.php");
exit;
?>
