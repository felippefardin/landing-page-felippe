<?php
$conn = new mysqli("localhost", "root", "", "mercado");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=emails.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "Nome\tE-mail\tMensagem\tData de Envio\n";

$result = $conn->query("SELECT nome, email, mensagem, data_envio FROM emails ORDER BY data_envio DESC");

while ($row = $result->fetch_assoc()) {
    echo "{$row['nome']}\t{$row['email']}\t" . str_replace(["\r", "\n"], " ", $row['mensagem']) . "\t{$row['data_envio']}\n";
}
?>
