<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "felippe");

$filtro = $conn->real_escape_string(trim($_GET['busca'] ?? ''));
$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

$where = "WHERE (respondida = 0 OR respondida IS NULL)";
if ($filtro !== '') {
    $where .= " AND (nome LIKE '%$filtro%' OR email LIKE '%$filtro%' OR whatsapp LIKE '%$filtro%')";
}
if ($dataInicio !== '') {
    $where .= " AND data_envio >= '{$dataInicio} 00:00:00'";
}
if ($dataFim !== '') {
    $where .= " AND data_envio <= '{$dataFim} 23:59:59'";
}

$limite = 5;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $limite;

$colunasValidas = ['nome', 'email', 'whatsapp', 'data_envio'];
$ordem = in_array($_GET['ordem'] ?? '', $colunasValidas) ? $_GET['ordem'] : 'data_envio';
$direcao = ($_GET['direcao'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
$novaDirecao = $direcao === 'ASC' ? 'DESC' : 'ASC';

// Atualizar como respondida
if (isset($_GET['marcar'])) {
    $idMarcar = (int)$_GET['marcar'];
    $conn->query("UPDATE emails SET respondida = 1 WHERE id = $idMarcar");
    header("Location: admin.php");
    exit;
}

// Excluir mensagem
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
    <link rel="shortcut icon" href="img/shortcut icon.png">
    <link rel="stylesheet" href="css/export-adm.css">
    <link rel="stylesheet" href="css/estilo-adm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .busca {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .busca input[type="text"],
        .busca input[type="date"] {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .busca button {
            padding: 6px 12px;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .busca button:hover {
            background-color: #005fa3;
        }

        .paginacao {
            margin-top: 20px;
            display: flex;
            gap: 6px;
        }

        .paginacao a {
            padding: 5px 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }

        .paginacao a.ativa {
            background-color: #0077cc;
            color: #fff;
            font-weight: bold;
        }
        /* Modal estilizado */
#modalResponder {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

#modalResponder .modal-conteudo {
    background: #fff;
    padding: 25px 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    position: relative;
}

#modalResponder h3 {
    margin-top: 0;
    font-size: 20px;
    color: #333;
}

#modalResponder label {
    font-weight: bold;
    margin-top: 15px;
    display: block;
    color: #444;
}

#modalResponder textarea,
#modalResponder input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    resize: vertical;
    font-size: 14px;
}

#modalResponder .botoes-modal {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

#modalResponder button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

#modalResponder button[type="submit"] {
    background-color: #28a745;
    color: white;
}

#modalResponder button[type="submit"]:hover {
    background-color: #218838;
}

#modalResponder button.cancelar {
    background-color: #6c757d;
    color: white;
}

#modalResponder button.cancelar:hover {
    background-color: #5a6268;
}

#modalResponder .btn-remover {
    background-color: #dc3545;
    color: white;
    padding: 6px 10px;
    border: none;
    border-radius: 4px;
    margin-top: 5px;
    cursor: pointer;
}

#modalResponder .btn-remover:hover {
    background-color: #c82333;
}
#modalResponder button.limpar {
    background-color: #ffc107;
    color: #212529;
}

#modalResponder button.limpar:hover {
    background-color: #e0a800;
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
            <a href="email-recebido.php"><i class="fa fa-envelope"></i> Email recebidos</a>
            <a href="cadastrosatuais.php"><i class="fa fa-user"></i> Usuários</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>
</div>

<h1>Olá, <?= ucwords(strtolower($_SESSION['usuario'])) ?>!</h1>
<h2>Mensagens Não Respondidas</h2>

<!-- Filtro -->
<form class="busca" method="GET" action="admin.php">
    <input type="text" name="busca" placeholder="Buscar por nome, e-mail ou WhatsApp" value="<?= htmlspecialchars($filtro) ?>">
    <input type="date" name="data_inicio">
    <input type="date" name="data_fim">
    <button type="submit">Buscar</button>
</form>

<!-- Exportações -->
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
            <th><a href="?ordem=nome&direcao=<?= $novaDirecao ?>">Nome</a></th>
            <th><a href="?ordem=email&direcao=<?= $novaDirecao ?>">E-mail</a></th>
            <th><a href="?ordem=whatsapp&direcao=<?= $novaDirecao ?>">WhatsApp</a></th>
            <th>Mensagem</th>
            <th><a href="?ordem=data_envio&direcao=<?= $novaDirecao ?>">Data</a></th>
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
                        <a href="admin.php?marcar=<?= $linha['id'] ?>" class="marcar" onclick="return confirm('Marcar como respondida?')">Marcar como lida</a>
                        <span>|</span>
                        <a href="#" class="responder" onclick="abrirModal('<?= htmlspecialchars($linha['email']) ?>'); return false;">Responder</a>
                        <span>|</span>
                        <a href="admin.php?excluir=<?= $linha['id'] ?>" class="excluir" onclick="return confirm('Deseja excluir esta mensagem?')">Excluir</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Paginação -->
<div class="paginacao">
    <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
        <a class="<?= $i === $pagina ? 'ativa' : '' ?>" 
           href="?pagina=<?= $i ?>&busca=<?= urlencode($filtro) ?>&data_inicio=<?= $dataInicio ?>&data_fim=<?= $dataFim ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
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

  function abrirModal(email) {
    document.getElementById('email_destino').value = email;
    document.getElementById('modalResponder').style.display = 'flex';
}

function fecharModal() {
    document.getElementById('modalResponder').style.display = 'none';
    document.getElementById('formResponder').reset();
}

function removerAnexo() {
    const input = document.getElementById("inputAnexo");
    if (input) input.value = "";
}


</script>

<!-- Modal de Resposta -->
<div id="modalResponder">
    <div class="modal-conteudo">
        <h3>Responder E-mail</h3>
        <form id="formResponder" method="POST" action="enviar_resposta.php" enctype="multipart/form-data">
            <input type="hidden" name="email_destino" id="email_destino">
            
            <label>Mensagem:</label>
            <textarea name="mensagem" rows="6" required></textarea>

            <label>Anexo (opcional):</label>
            <input type="file" name="anexo" id="inputAnexo">
            <button type="button" class="btn-remover" onclick="removerAnexo()">Excluir Anexo</button>

            <div class="botoes-modal">
                <button type="submit">Enviar</button>
                <button type="button" class="limpar" onclick="document.getElementById('formResponder').reset()">Limpar</button>
                <button type="button" class="cancelar" onclick="fecharModal()">Voltar</button>
            </div>
        </form>
    </div>
</div>


            
   

</body>
</html>
