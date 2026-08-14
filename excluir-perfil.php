<?php
require_once __DIR__ . '/src/bootstrap.php';
require_auth();
$conn = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
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
