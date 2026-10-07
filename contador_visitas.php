<?php

// Arquivos
$arquivoTotal = __DIR__ . '/visitas_total.txt';
$arquivoDiario = __DIR__ . '/visitas_diarias.txt';
$arquivoData = __DIR__ . '/visitas_data.txt';

// Cria arquivos se não existirem
if (!file_exists($arquivoTotal)) {
    file_put_contents($arquivoTotal, '0');
}

if (!file_exists($arquivoDiario)) {
    file_put_contents($arquivoDiario, '0');
}

if (!file_exists($arquivoData)) {
    file_put_contents($arquivoData, date('Y-m-d'));
}

// Função para obter o IP
if (!function_exists('getIP')) {
    function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

// Verifica se é um novo dia
$dataHoje = date('Y-m-d');
$dataSalva = trim(file_get_contents($arquivoData));

if ($dataHoje !== $dataSalva) {
    file_put_contents($arquivoDiario, '0');
    file_put_contents($arquivoData, $dataHoje);
}

// Identifica visitante
$ip = getIP();

$cookieName = 'visitante_contador_' . md5($ip);

// Só conta se ainda não houver cookie
if (!isset($_COOKIE[$cookieName])) {

    // Total
    $total = (int) file_get_contents($arquivoTotal);
    $total++;

    file_put_contents(
        $arquivoTotal,
        (string) $total,
        LOCK_EX
    );

    // Diário
    $diario = (int) file_get_contents($arquivoDiario);
    $diario++;

    file_put_contents(
        $arquivoDiario,
        (string) $diario,
        LOCK_EX
    );

    // Cookie válido por 24 horas
    setcookie(
        $cookieName,
        '1',
        [
            'expires' => time() + 86400,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );
}
?>