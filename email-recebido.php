<?php
// Conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "felippe";

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}


// Filtros
$filtroEmail = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

$where = "WHERE 1";
if (!empty($filtroEmail)) {
    $emailEscapado = $conn->real_escape_string($filtroEmail);
    $where .= " AND email LIKE '%$emailEscapado%'";
}
if (!empty($dataInicio)) {
    $where .= " AND data_envio >= '{$dataInicio} 00:00:00'";
}
if (!empty($dataFim)) {
    $where .= " AND data_envio <= '{$dataFim} 23:59:59'";
}

// Paginação
$limite = 10;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $limite;

$total = $conn->query("SELECT COUNT(*) as total FROM email_cliente $where")->fetch_assoc()['total'];
$totalPaginas = ceil($total / $limite);

// Consulta
$sql = "SELECT * FROM email_cliente $where ORDER BY data_envio DESC LIMIT $inicio, $limite";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista de E-mails Capturados</title>
  <link rel="stylesheet" href="css/estilo-adm.css">
  <link rel="stylesheet" href="css/export-adm.css">
  <link rel="stylesheet" href="css/estilo-email-sucesso.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    
  </style>
</head>
<body>

<div class="dropdown-container">
  <div class="dropdown">
    <button class="dropbtn"> Conta  <i class="fa fa-arrow-down"></i> </button>
    <div class="dropdown-content">
      <a href="perfil.php"><i class="fa fa-user"></i> Perfil</a>
      <a href="admin.php"><i class="fa fa-message"></i> Mensagens não respondidas</a>               
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
  </div>
</div>

 <h1>Olá, <?= ucwords(strtolower($_SESSION['usuario'])) ?>!</h1>
    <h2>E-mails recebidos</h2>



<div class="container">
  <h1>📬 E-mails Cadastrados</h1>

  <!-- Filtros -->
  <form class="busca" method="GET" action="email-recebido.php">
    <input type="text" name="busca" placeholder="Buscar por e-mail" value="<?= htmlspecialchars($filtroEmail) ?>">
    <input type="date" name="data_inicio">
    <input type="date" name="data_fim">
    <button type="submit"><i class="fa fa-search"></i> Filtrar</button>
  </form>

  <!-- Exportações -->
  <div class="exportar-container" style="margin: 10px 0;">
    <form method="GET" action="exportar_emails_excel.php" style="display:inline;">
      <button type="submit" class="exportar-button excel"><i class="fa fa-file-excel"></i> Excel</button>
    </form>
    <form method="GET" action="exportar_emails_pdf.php" style="display:inline;">
      <button type="submit" class="exportar-button pdf"><i class="fa fa-file-pdf"></i> PDF</button>
    </form>
    <form method="GET" action="exportar_emails_csv.php" style="display:inline;">
      <button type="submit" class="exportar-button csv"><i class="fa fa-file-csv"></i> CSV</button>
    </form>
  </div>

  <?php if ($resultado->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>E-mail</th>
          <th>Data de Envio</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($row['data_envio'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <!-- Paginação -->
    <div class="paginacao">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a class="<?= $i == $pagina ? 'ativa' : '' ?>" href="?pagina=<?= $i ?>&busca=<?= urlencode($filtroEmail) ?>&data_inicio=<?= $dataInicio ?>&data_fim=<?= $dataFim ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>

  <?php else: ?>
    <p>Nenhum e-mail foi cadastrado ainda.</p>
  <?php endif; ?>
</div>
<script>
    window.addEventListener('load', function () {
    // Limpa os campos de data
    const dataInicio = document.querySelector('input[name="data_inicio"]');
    const dataFim = document.querySelector('input[name="data_fim"]');
    if (dataInicio) dataInicio.value = "";
    if (dataFim) dataFim.value = "";

    // Remove os parâmetros da URL após carregar a página
    if (window.location.search.length > 0) {
      const urlSemParametros = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, urlSemParametros);
    }
  });
</script>
</body>
</html>

<?php $conn->close(); ?>
