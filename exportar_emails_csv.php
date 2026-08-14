<?php
require_once __DIR__ . '/src/bootstrap.php';
require_auth();
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=emails.csv");

$conn = db();
$resultado = $conn->query("SELECT * FROM email_cliente ORDER BY data_envio DESC");

$output = fopen("php://output", "w");
fputcsv($output, ["ID", "E-mail", "Data de Envio"]);

while ($linha = $resultado->fetch_assoc()) {
    fputcsv($output, [$linha['id'], $linha['email'], $linha['data_envio']]);
}

fclose($output);
exit;
?>
