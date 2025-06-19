<?php
// conexao.php

require_once 'config.php'; // Inclui as constantes do banco

// Cria conexão usando mysqli
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

// Se chegou aqui, a conexão foi bem-sucedida
echo "Conexão bem-sucedida com o banco de dados!";
?>
