<?php
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

$html = '
    <h2>Lista de E-mails Cadastrados</h2>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>E-mail</th>
                <th>Data de Envio</th>
            </tr>
        </thead>
        <tbody>
';

$conn = new mysqli("localhost", "root", "", "felippe");
$resultado = $conn->query("SELECT * FROM email_cliente ORDER BY data_envio DESC");

while ($linha = $resultado->fetch_assoc()) {
    $html .= "<tr>
        <td>{$linha['id']}</td>
        <td>{$linha['email']}</td>
        <td>" . date('d/m/Y H:i', strtotime($linha['data_envio'])) . "</td>
    </tr>";
}

$html .= '</tbody></table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("emails.pdf", ["Attachment" => true]);
?>
