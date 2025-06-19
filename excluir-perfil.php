<?php
session_start();
$conn = new mysqli("localhost", "root", "", "felippe");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_SESSION['usuario'];

    $queryDelete = "DELETE FROM usuarios WHERE nome = ?";
    $stmt = $conn->prepare($queryDelete);
    $stmt->bind_param("s", $usuario);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: login.php?excluido=1");
        exit;
    } else {
        echo "Erro ao excluir o perfil. Tente novamente.";
    }
} else {
    // Acesso direto proibido
    header("Location: perfil.php");
    exit;
}
