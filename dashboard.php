<?php
session_start();
include 'contador_visitas.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "felippe");

// Consultas para os cards
$totalMensagens = $conn->query("SELECT COUNT(*) AS total FROM emails")->fetch_assoc()['total'];
$respondidas = $conn->query("SELECT COUNT(*) AS total FROM emails WHERE respondida = 1")->fetch_assoc()['total'];
$naoRespondidas = $conn->query("SELECT COUNT(*) AS total FROM emails WHERE respondida = 0")->fetch_assoc()['total'];

// Contador de visitas
$visitasTotal = file_exists('visitas_total.txt') ? (int)file_get_contents('visitas_total.txt') : 0;
$visitasDiarias = file_exists('visitas_diarias.txt') ? (int)file_get_contents('visitas_diarias.txt') : 0;

// Para o gráfico: mensagens por data dos últimos 7 dias
$dataChart = [];
$labels = [];
$resultChart = $conn->query("
    SELECT DATE(data_envio) as data, COUNT(*) as total
    FROM emails
    WHERE data_envio >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(data_envio)
    ORDER BY DATE(data_envio)
");

while ($row = $resultChart->fetch_assoc()) {
    $labels[] = $row['data'];
    $dataChart[] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Dashboard - Painel Administrativo</title>
<link rel="shortcut icon" href="img/atalho.png" />
<style>
    * {
        margin: 0; padding: 0; box-sizing: border-box;
    }
    body, html {
        font-family: Arial, sans-serif;
        height: 100%;
        background: #f9f9f9;
        color: #333;
    }
    .container {
        display: flex;
        min-height: 100vh;
    }
    nav.sidebar {
        width: 220px;
        background-color: #007d3e;
        color: white;
        display: flex;
        flex-direction: column;
        padding: 20px;
        position: fixed;
        height: 100vh;
        top: 0; left: 0;
    }
    nav.sidebar h2 {
        font-weight: bold;
        margin-bottom: 40px;
        text-align: center;
    }
    nav.sidebar a {
        color: white;
        text-decoration: none;
        padding: 12px 15px;
        margin-bottom: 10px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    nav.sidebar a:hover, nav.sidebar a.active {
        background-color: #009a4f;
    }
    nav.sidebar a i {
        font-size: 18px;
    }

    main.content {
        margin-left: 220px;
        padding: 30px;
        flex-grow: 1;
    }

    h1 {
        margin-bottom: 30px;
        color: #007d3e;
    }

    .cards {
        display: flex;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }
    .card {
        background: white;
        padding: 25px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        flex: 1 1 200px;
        min-width: 200px;
        color: #007d3e;
        font-weight: bold;
        font-size: 18px;
        text-align: center;
    }
    .card span {
        display: block;
        font-size: 48px;
        margin-top: 10px;
        color: #00a859;
    }

    #chart-container {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <nav class="sidebar">
        <h2>Tech Tecnologia</h2>
        <a href="resetar_visitas_total.php" onclick="return confirm('Deseja realmente zerar o contador total?')">
            <i class="fa fa-trash"></i> Resetar Visitas Total
        </a>
        <a href="home.php"><i class="fa fa-home"></i> Home</a>
        <a href="perfil.php"><i class="fa fa-user"></i> Perfil</a>
        <a href="admin.php"><i class="fa fa-envelope"></i> Mensagens</a>
        <a href="respondidas.php"><i class="fa fa-message"></i> Mensagens Respondidas</a>
        <a href="dashboard.php" class="active"><i class="fa fa-chart-bar"></i> Dashboard</a>
        <a href="email-recebido.php"><i class="fa fa-envelope"></i> Emails Recebidos</a>
        <a href="cadastrosatuais.php"><i class="fa fa-user"></i> Usuários</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </nav>

    <main class="content">
        <h1>Olá, <?= ucwords(strtolower($_SESSION['usuario'])) ?>!</h1>

        <div class="cards">
            <div class="card">
                Total de Mensagens
                <span><?= $totalMensagens ?></span>
            </div>
            <div class="card">
                Respondidas
                <span><?= $respondidas ?></span>
            </div>
            <div class="card">
                Não Respondidas
                <span><?= $naoRespondidas ?></span>
            </div>
            <div class="card">
                Visitas Hoje
                <span><?= $visitasDiarias ?></span>
            </div>
            <div class="card">
                Visitas Totais
                <span><?= $visitasTotal ?></span>
            </div>
        </div>

        <div id="chart-container">
            <canvas id="mensagensChart"></canvas>
        </div>
    </main>
</div>

<script>
    const ctx = document.getElementById('mensagensChart').getContext('2d');
    const mensagensChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Mensagens recebidas',
                data: <?= json_encode($dataChart) ?>,
                backgroundColor: '#00a859',
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#007d3e',
                        font: { weight: 'bold' }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
