<?php
require_once __DIR__ . '/src/bootstrap.php';
require_auth();
require_once 'dompdf/autoload.inc.php';
// Caminho correto do autoload do DOMPDF manual

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

$html = '
    <h1>Lista de Contatos</h1>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>WhatsApp</th>
                <th>Mensagem</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
';

$conn = db();
$resultado = $conn->query("SELECT * FROM emails ORDER BY data_envio DESC");

while ($linha = $resultado->fetch_assoc()) {
    $html .= "<tr>
        <td>" . htmlspecialchars($linha['nome'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($linha['email'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($linha['whatsapp'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($linha['mensagem'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>{$linha['data_envio']}</td>
    </tr>";
}

$html .= '</tbody></table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("contatos.pdf", ["Attachment" => true]);
