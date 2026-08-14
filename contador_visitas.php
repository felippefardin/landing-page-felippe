<?php
// Arquivos
$arquivoTotal = 'visitas_total.txt';
$arquivoDiario = 'visitas_diarias.txt';
$arquivoData = 'visitas_data.txt';

// Cria arquivos se não existem
if (!file_exists($arquivoTotal)) file_put_contents($arquivoTotal, 0);
if (!file_exists($arquivoDiario)) file_put_contents($arquivoDiario, 0);
if (!file_exists($arquivoData)) file_put_contents($arquivoData, date('Y-m-d'));

// Função para IP
function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
    else return $_SERVER['REMOTE_ADDR'];
}

// Verifica se é um novo dia
$dataHoje = date('Y-m-d');
$dataSalva = trim(file_get_contents($arquivoData));
if ($dataHoje !== $dataSalva) {
    file_put_contents($arquivoDiario, 0);
    file_put_contents($arquivoData, $dataHoje);
}

// Lógica de cookie por IP
$ip = getIP();
$cookie_name = 'visitante_contador_' . md5($ip);
if (!isset($_COOKIE[$cookie_name])) {
    // Conta total
    $total = (int)file_get_contents($arquivoTotal) + 1;
    file_put_contents($arquivoTotal, $total);

    // Conta diária
    $diario = (int)file_get_contents($arquivoDiario) + 1;
    file_put_contents($arquivoDiario, $diario);

    // Define cookie por 24h
    setcookie($cookie_name, '1', time() + 86400, '/');
}
?>
