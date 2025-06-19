<?php

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;


header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=mensagens.csv");

$conn = new mysqli("localhost", "root", "", "mercado");
$resultado = $conn->query("SELECT * FROM emails ORDER BY data_envio DESC");

$output = fopen("php://output", "w");
fputcsv($output, ["Nome", "Email", "WhatsApp", "Mensagem", "Data"]);

while ($linha = $resultado->fetch_assoc()) {
    fputcsv($output, [$linha['nome'], $linha['email'], $linha['whatsapp'], $linha['mensagem'], $linha['data_envio']]);
}

fclose($output);
exit;
?>
