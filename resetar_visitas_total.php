<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    die("Acesso negado.");
}

file_put_contents('visitas_total.txt', 0);
header("Location: dashboard.php");
exit;
