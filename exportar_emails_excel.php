<?php
$conn = new mysqli("localhost", "root", "", "felippe");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=emails.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "ID\tE-mail\tData de Envio\n";

$result = $conn->query("SELECT * FROM email_cliente ORDER BY data_envio DESC");

while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['email']}\t{$row['data_envio']}\n";
}
?>
