<?php
// Caminho do contador
$contadorArquivo = "contador_downloads.txt";

// Se o arquivo não existir, cria com valor 0
if (!file_exists($contadorArquivo)) {
    file_put_contents($contadorArquivo, 0);
}

// Incrementa o contador
$downloads = (int)file_get_contents($contadorArquivo);
$downloads++;
file_put_contents($contadorArquivo, $downloads);

// Redireciona para o PDF
header("Location: docs/CartilhaSeguranca.pdf.pdf");
exit;
?>
