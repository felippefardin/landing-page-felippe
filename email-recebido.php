<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/PHPMailer.php';
require 'PHPMailer/PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer/SMTP.php';

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "felippe";

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

// Envio de e-mail marketing
$mensagemEnviada = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assunto'], $_POST['mensagem'])) {
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];
    $destinatarios = [];
    $imagemCid = '';

    if (!empty($_FILES['imagem']['tmp_name'])) {
        $imagemPath = $_FILES['imagem']['tmp_name'];
        $imagemName = $_FILES['imagem']['name'];
    }

    $res = $conn->query("SELECT email FROM email_cliente");
    while ($row = $res->fetch_assoc()) {
        $destinatarios[] = $row['email'];
    }

    $destinatarios = array_unique($destinatarios);

    foreach ($destinatarios as $email) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'felippefardin@gmail.com';
            $mail->Password = 'uooy pktv klcx ktnb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('felippefardin@gmail.com', 'Tech Tecnologia');
            $mail->addAddress($email);

            if (!empty($imagemPath)) {
                $cid = uniqid();
                $mail->addEmbeddedImage($imagemPath, $cid, $imagemName);
                $mensagem .= "<br><img src='cid:$cid' alt='Imagem'>";
            }

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = "<div style='font-family:Arial,sans-serif;font-size:16px;color:#333;'>$mensagem</div>";
            $mail->AltBody = strip_tags($mensagem);

            $mail->send();
        } catch (Exception $e) {
            // Erros podem ser logados se necessário
        }
    }
    $mensagemEnviada = true;
}

// Filtros
$filtroEmail = $_GET['busca'] ?? '';
$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

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
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$inicio = ($pagina - 1) * $limite;

$total = $conn->query("SELECT COUNT(*) as total FROM email_cliente $where")->fetch_assoc()['total'];
$totalPaginas = ceil($total / $limite);

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
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      width: 90%;
      max-width: 400px;
    }
    .modal-content button {
  margin: 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer; 
}

    
    .btn-confirmar {
      background-color: #d9534f;
      color: white;
      cursor: pointer;
    }
    .btn-cancelar {
      background-color: #ccc;
      cursor: pointer;
    }
    .excluir-btn {
      background: red;
      color: white;
      padding: 5px 10px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
    }
    #mensagem-enviada {
      background: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 10px;
      display: none;
      text-align: center;
    }
    /* Estilo para miniatura da imagem */
    #previewImagem img {
      max-width: 150px;
      max-height: 150px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="dropdown-container">
    <div class="dropdown">
        <button class="dropbtn">Conta <i class="fa fa-arrow-down"></i></button>
        <div class="dropdown-content">
            <a href="home.php"><i class="fa fa-home"></i> Home</a>
            <a href="perfil.php"><i class="fa fa-user"></i> Perfil</a>
            <a href="respondidas.php"><i class="fa fa-message"></i> Mensagens respondidas</a>
            <a href="dashboard.php"><i class="fa fa-tachometer"></i> Dashboard</a>            
            <a href="cadastrosatuais.php"><i class="fa fa-user"></i> Usuários</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>
</div>

<h1>Olá, <?= ucwords(strtolower($_SESSION['usuario'])) ?>!</h1>
<h2>E-mails recebidos</h2>

<div class="container">
  <div id="mensagem-enviada">Campanha de e-mail enviada com sucesso.</div>
  <h1>📬 E-mails Cadastrados</h1>

  <form class="busca" method="GET" action="email-recebido.php">
    <input type="text" name="busca" placeholder="Buscar por e-mail" value="<?= htmlspecialchars($filtroEmail) ?>">
    <input type="date" name="data_inicio">
    <input type="date" name="data_fim">
    <button type="submit"><i class="fa fa-search"></i> Filtrar</button>
  </form>

  <h3>Enviar E-mail Marketing</h3>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="assunto" placeholder="Assunto" required><br>
    <textarea name="mensagem" placeholder="Mensagem com HTML (ex: <b>Olá</b>, veja a imagem abaixo...):" required style="width:100%;height:150px;"></textarea><br>
    
    <!-- Input de arquivo com id para o JS -->
    <input type="file" name="imagem" id="imagemInput" accept="image/*"><br>
    
    <!-- Container para a miniatura -->
    <div id="previewImagem"></div>
    
    <button type="submit" style="margin-top:10px;padding:10px 20px;background:#007d3e;color:white;border:none;border-radius:5px;cursor:pointer;">Enviar</button>
  </form>

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
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($row['data_envio'])) ?></td>
            <td><button class="excluir-btn" onclick="abrirModal(<?= $row['id'] ?>)">Excluir</button></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

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

<!-- Modal de Confirmação -->
<div id="modal" class="modal">
  <div class="modal-content">
    <p>Tem certeza que deseja excluir este e-mail?</p>
    <form method="POST" action="excluir_email.php">
      <input type="hidden" name="id" id="emailIdExcluir">
      <button type="submit" class="btn-confirmar">Sim, excluir</button>
      <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
    </form>
  </div>
</div>

<script>
function abrirModal(id) {
  document.getElementById('emailIdExcluir').value = id;
  document.getElementById('modal').style.display = 'flex';
}
function fecharModal() {
  document.getElementById('modal').style.display = 'none';
}

<?php if ($mensagemEnviada): ?>
document.addEventListener('DOMContentLoaded', function () {
  const msg = document.getElementById('mensagem-enviada');
  msg.style.display = 'block';
  setTimeout(() => msg.style.display = 'none', 3000);
});
<?php endif; ?>

// Script para mostrar a miniatura da imagem selecionada antes do envio
const inputImagem = document.getElementById('imagemInput');
const preview = document.getElementById('previewImagem');

inputImagem.addEventListener('change', function() {
  preview.innerHTML = ''; // limpa preview anterior

  const file = this.files[0];
  if (file) {
    const img = document.createElement('img');
    img.alt = 'Pré-visualização da imagem';
    img.title = file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
      img.src = e.target.result;
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  }
});
</script>

</body>
</html>

<?php $conn->close(); ?>
