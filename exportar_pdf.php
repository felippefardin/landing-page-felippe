<?php
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

$conn = new mysqli("localhost", "root", "", "mercado");
$resultado = $conn->query("SELECT * FROM emails ORDER BY data_envio DESC");

while ($linha = $resultado->fetch_assoc()) {
    $html .= "<tr>
        <td>{$linha['nome']}</td>
        <td>{$linha['email']}</td>
        <td>{$linha['whatsapp']}</td>
        <td>{$linha['mensagem']}</td>
        <td>{$linha['data_envio']}</td>
    </tr>";
}

$html .= '</tbody></table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("contatos.pdf", ["Attachment" => true]);
