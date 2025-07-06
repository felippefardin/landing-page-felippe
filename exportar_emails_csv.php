<?php
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=emails.csv");

$conn = new mysqli("localhost", "root", "", "felippe");
$resultado = $conn->query("SELECT * FROM email_cliente ORDER BY data_envio DESC");

$output = fopen("php://output", "w");
fputcsv($output, ["ID", "E-mail", "Data de Envio"]);

while ($linha = $resultado->fetch_assoc()) {
    fputcsv($output, [$linha['id'], $linha['email'], $linha['data_envio']]);
}

fclose($output);
exit;
?>
