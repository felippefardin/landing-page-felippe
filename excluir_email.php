<?php
require_once __DIR__ . '/src/bootstrap.php';
require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    http_response_code(405);
    exit('Método não permitido.');
}
verify_csrf();
$conn = db();

$id = (int) $_POST['id'];
$stmt = $conn->prepare("DELETE FROM email_cliente WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: email-recebido.php");
exit;
?>
