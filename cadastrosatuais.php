<?php
session_start();

$conn = new mysqli("localhost", "root", "", "felippe");
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$mensagem = isset($_GET['mensagem']) ? $_GET['mensagem'] : "";
$erro = isset($_GET['erro']) ? $_GET['erro'] : "";

// Excluir usuário
if (isset($_GET['excluir_id'])) {
    $idExcluir = intval($_GET['excluir_id']);
    $sqlExcluir = "DELETE FROM usuarios WHERE id = $idExcluir";
    if ($conn->query($sqlExcluir) === TRUE) {
        $mensagem = "Usuário excluído com sucesso.";
        header("Location: cadastrosatuais.php?mensagem=" . urlencode($mensagem));
        exit;
    } else {
        $erro = "Erro ao excluir usuário.";
        header("Location: cadastrosatuais.php?erro=" . urlencode($erro));
        exit;
    }
}

// Atualizar usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $celular = $conn->real_escape_string($_POST['celular']);

    if (!empty($_POST['senha'])) {
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE usuarios SET nome='$nome', email='$email', celular='$celular', senha='$senha_hash' WHERE id=$id";
    } else {
        $sqlUpdate = "UPDATE usuarios SET nome='$nome', email='$email', celular='$celular' WHERE id=$id";
    }

    if ($conn->query($sqlUpdate) === TRUE) {
        $mensagem = "Usuário atualizado com sucesso.";
        header("Location: cadastrosatuais.php?mensagem=" . urlencode($mensagem) . "&editar_id=" . $id);
        exit;
    } else {
        $erro = "Erro ao atualizar usuário.";
        header("Location: cadastrosatuais.php?erro=" . urlencode($erro) . "&editar_id=" . $id);
        exit;
    }
}

// Buscar dados para editar
$editarUsuario = null;
if (isset($_GET['editar_id'])) {
    $idEditar = intval($_GET['editar_id']);
    $sqlBuscar = "SELECT * FROM usuarios WHERE id = $idEditar";
    $resultBuscar = $conn->query($sqlBuscar);
    if ($resultBuscar->num_rows == 1) {
        $editarUsuario = $resultBuscar->fetch_assoc();
    } else {
        $erro = "Usuário não encontrado para edição.";
    }
}

// Buscar todos os usuários
$sql = "SELECT id, nome, email, celular FROM usuarios ORDER BY id DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>Usuários Cadastrados - Mercado</title>
<style>
body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
h1 { color: #00a859; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
th, td { padding: 10px; border: 1px solid #ddd; text-align: center; vertical-align: middle; }
th { background: #00a859; color: white; }
tr:nth-child(even) { background: #f2f2f2; }
.acoes { display: flex; justify-content: center; align-items: center; gap: 10px; border: none; }
button.acao-btn, a.acao-btn { border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; transition: background-color 0.3s ease; font-size: 14px; user-select: none; padding: 7px 14px; color: white; border: none; }
button.acao-btn:hover, a.acao-btn:hover { opacity: 0.9; }
.salvar-btn { background-color: #00a859; }
.salvar-btn:hover { background-color: #007d3e; }
.cancelar-btn { background-color: #d13232; }
.cancelar-btn:hover { background-color: #a82929; }
form { background: #fff; padding: 15px; margin-top: 20px; border: 1px solid #ccc; max-width: 500px; border-radius: 5px; }
label { display: block; margin-top: 10px; font-weight: bold; }
input[type=text], input[type=email], input[type=password] { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }

/* Mensagens no centro superior */
.mensagem { 
    padding: 10px 20px; 
    border-radius: 4px; 
    font-weight: bold; 
    display: none; 
    position: fixed; 
    top: 20px; 
    left: 50%; 
    transform: translateX(-50%); 
    z-index: 9999; 
    font-size: 16px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
}
.success { background-color: #4CAF50; color: white; }
.error { background-color: #d13232; color: white; }

/* Botões do formulário centralizados */
.botao-container { display: flex; justify-content: center; gap: 10px; margin-top: 15px; }
.dropbtn {
    background-color: #007d3e;
    color: white;
    padding: 10px 16px;
    font-size: 20px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
.dropbtn {
    background-color: #007d3e;
    color: white;
    padding: 10px 16px;
    font-size: 20px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
/* Dropdown menu conforme seu código anterior */
        .dropdown-container {
          display: flex;
          justify-content: flex-end;
          margin-bottom: 20px;
        }
        .dropdown {
          position: relative;
          display: inline-block;
        }
        .dropbtn {
          background-color: #007d3e;
          color: white;
          padding: 10px 16px;
          font-size: 20px;
          border: none;
          cursor: pointer;
          border-radius: 4px;
        }
        .dropdown-content {
          display: none;
          position: absolute;
          right: 0;
          background-color: #ffffff;
          min-width: 200px;
          box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
          z-index: 1;
          border-radius: 4px;
          overflow: hidden;
        }
        .dropdown-content a {
          color: black;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
          transition: background 0.2s;
        }
        .dropdown-content a:hover {
          background-color: #007d3e;
          color: white;
        }
        .dropdown:hover .dropdown-content {
          display: block;
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

<h1>Usuários Cadastrados</h1>

<?php if ($mensagem): ?>
    <div id="mensagem-sucesso" class="mensagem success"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
    <div id="mensagem-erro" class="mensagem error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Celular</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['celular']) ?></td>
                <td class="acoes">
                    <a href="cadastrosatuais.php?editar_id=<?= $row['id'] ?>" class="acao-btn salvar-btn">Editar</a>
                    <a href="cadastrosatuais.php?excluir_id=<?= $row['id'] ?>" class="acao-btn cancelar-btn" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">Nenhum usuário cadastrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($editarUsuario): ?>
    <h2>Editar Usuário <?= $editarUsuario['nome'] ?></h2>
    <form id="form-editar" method="POST" action="cadastrosatuais.php">
        <input type="hidden" name="id" value="<?= $editarUsuario['id'] ?>" />
        <input type="hidden" name="editar" value="1" />

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($editarUsuario['nome']) ?>" />

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($editarUsuario['email']) ?>" />

        <label for="celular">Celular:</label>
        <input type="text" id="celular" name="celular" required value="<?= htmlspecialchars($editarUsuario['celular']) ?>" />

        <label for="senha">Senha: <small>(Preencha apenas se deseja alterar)</small></label>
        <div style="position: relative;">
            <input type="password" id="senha" name="senha" placeholder="Nova senha" style="padding-right: 30px;" />
            <i class="fa fa-eye" id="toggleSenha" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>

        <div class="botao-container">
            <button type="submit" name="editar" class="acao-btn salvar-btn">Salvar Alterações</button>
            <button type="button" id="cancelar-editar" class="acao-btn cancelar-btn">Cancelar</button>
        </div>
    </form>

    <script>
    // Fechar formulário de edição ao clicar em "Cancelar"
    document.getElementById('cancelar-editar').addEventListener('click', function() {
        document.getElementById('form-editar').style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    </script>
<?php endif; ?>

<script>
// Mostrar/ocultar senha
const senhaInput = document.getElementById('senha');
const toggleSenha = document.getElementById('toggleSenha');
if (toggleSenha) {
    toggleSenha.addEventListener('click', () => {
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            toggleSenha.classList.remove('fa-eye');
            toggleSenha.classList.add('fa-eye-slash');
        } else {
            senhaInput.type = 'password';
            toggleSenha.classList.remove('fa-eye-slash');
            toggleSenha.classList.add('fa-eye');
        }
    });
}

// Mensagens no centro do topo e desaparecendo após 2s
window.addEventListener('DOMContentLoaded', () => {
    const sucesso = document.getElementById('mensagem-sucesso');
    const erro = document.getElementById('mensagem-erro');
    if (sucesso) { 
        sucesso.style.display = 'block'; 
        setTimeout(() => { sucesso.style.display = 'none'; }, 2000); 
    }
    if (erro) { 
        erro.style.display = 'block'; 
        setTimeout(() => { erro.style.display = 'none'; }, 2000); 
    }
});
</script>

</body>
</html>
