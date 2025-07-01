<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "felippe");

$filtro = "";
$where = "WHERE (respondida = 0 OR respondida IS NULL)";

if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
    $filtro = $conn->real_escape_string(trim($_GET['busca']));
    $where .= " AND (nome LIKE '%$filtro%' OR email LIKE '%$filtro%' OR whatsapp LIKE '%$filtro%')";
}

$limite = 5;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $limite;

$colunasValidas = ['nome', 'email', 'whatsapp', 'data_envio'];
$ordem = in_array($_GET['ordem'] ?? '', $colunasValidas) ? $_GET['ordem'] : 'data_envio';
$direcao = ($_GET['direcao'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
$novaDirecao = $direcao === 'ASC' ? 'DESC' : 'ASC';

// Atualizar para marcar mensagem como respondida
if (isset($_GET['marcar'])) {
    $idMarcar = (int)$_GET['marcar'];
    $conn->query("UPDATE emails SET respondida = 1 WHERE id = $idMarcar");
    header("Location: admin.php");
    exit;
}

if (isset($_GET['excluir'])) {
    $idExcluir = (int)$_GET['excluir'];
    $conn->query("DELETE FROM emails WHERE id = $idExcluir");
    header("Location: admin.php");
    exit;
}

$sql = "SELECT * FROM emails $where ORDER BY $ordem $direcao LIMIT $inicio, $limite";
$resultado = $conn->query($sql);

$totalRegistros = $conn->query("SELECT COUNT(*) AS total FROM emails $where")->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $limite);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Mensagens Não Respondidas</title>
    <link rel="shortcut icon" href="img/atalho.png">
    <style>
        
        

    </style>
</head>
<body>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="css/export-adm.css">
<link rel="stylesheet" href="css/estilo-adm.css">

<div class="dropdown-container">
    <div class="dropdown">
      <button class="dropbtn"> Conta  <i class="fa fa-arrow-down"></i> </button>
      <div class="dropdown-content">
        <a href="perfil.php"><i class="fa fa-user"></i>  Perfil</a>
        <a href="respondidas.php"><i class="fa fa-message"></i>  Mensagens respondidas</a>
        <a href="dashboard.php"><i class="fa fa-tachometer"></i>  Dashboard</a> 
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>  Sair</a>
      </div>
    </div>
</div>

    <h1>Olá, <?= ucwords(strtolower($_SESSION['usuario'])) ?>!</h1>
    <h2>Mensagens Não Respondidas</h2>

    <form class="busca" method="GET" action="admin.php">
        <input type="text" name="busca" placeholder="Buscar por nome, e-mail ou WhatsApp" value="<?= htmlspecialchars($filtro) ?>">
        <button type="submit">Buscar</button>
    </form>

     <div style="margin-top: 10px;">
        <form method="GET" action="exportar_excel.php" style="display:inline;">
            <button type="submit" class="exportar-button excel">Exportar Excel</button>
        </form>

        <form method="GET" action="exportar_pdf.php" style="display:inline;">
            <button type="submit" class="exportar-button pdf">Exportar PDF</button>
        </form>

        <form method="GET" action="exportar_csv.php" style="display:inline;">
            <button type="submit" class="exportar-button csv">Exportar CSV</button>
        </form>
    </div>


    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>WhatsApp</th>
                <th>Mensagem</th>
                <th>Data</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = $resultado->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($linha['nome']) ?></td>
                    <td><?= htmlspecialchars($linha['email']) ?></td>
                    <td><?= htmlspecialchars($linha['whatsapp']) ?></td>
                    <td><?= nl2br(htmlspecialchars($linha['mensagem'])) ?></td>
                    <td><?= $linha['data_envio'] ?></td>
                    <td>
                        <div class="acoes">
                            <a href="admin.php?marcar=<?= $linha['id'] ?>" class="marcar" onclick="return confirm('Marcar como respondida?')">Marcar como respondida</a>
                            <span>|</span>
                             <a href="mailto:<?= htmlspecialchars($linha['email']) ?>" class="responder">Responder</a>
                            <span>|</span>
                            <a href="admin.php?excluir=<?= $linha['id'] ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir esta mensagem?')">Excluir</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="paginacao">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
            <a class="<?= $i === $pagina ? 'ativa' : '' ?>" href="?pagina=<?= $i ?>&busca=<?= urlencode($filtro) ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

</body>
</html>
