<?php
session_start();

$conn = new mysqli("localhost", "root", "", "felippe");
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$mensagem = "";
$erro = "";

// Excluir usuário
if (isset($_GET['excluir_id'])) {
    $idExcluir = intval($_GET['excluir_id']);
    $sqlExcluir = "DELETE FROM usuarios WHERE id = $idExcluir";
    if ($conn->query($sqlExcluir) === TRUE) {
        $mensagem = "Usuário excluído com sucesso.";
        header("Location: cadastrosatuais.php");
        exit;
    } else {
        $erro = "Erro ao excluir usuário.";
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
        header("Location: cadastrosatuais.php");
        exit;
    } else {
        $erro = "Erro ao atualizar usuário.";
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
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }

        h1 {
            color: #00a859;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #00a859;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        th a {
            color: inherit;
            text-decoration: none;
            font-weight: bold;
        }

        th a:hover {
            text-decoration: underline;
            color: #fff;
        }

        .mensagem {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-weight: bold;
        }

        .success {
            background-color: #c8e6c9;
            color: #256029;
        }

        .error {
            background-color: #ffcdd2;
            color: #c62828;
        }

        .acoes {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            border: none;
        }

        button.acao-btn,
        a.acao-btn {
            background-color: #00a859;
            color: white;
            border: none;
            padding: 7px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
            font-size: 14px;
            user-select: none;
        }

        button.acao-btn:hover,
        a.acao-btn:hover {
            background-color: #007d3e;
        }

        button.excluir-btn,
        a.excluir-btn {
            background-color: #d13232;
        }

        button.excluir-btn:hover,
        a.excluir-btn:hover {
            background-color: #a82929;
        }

        form {
            background: #fff;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #ccc;
            max-width: 500px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type=submit] {
            margin-top: 15px;
            padding: 10px 15px;
            background: #00a859;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }

        button[type=submit]:hover {
            background: #007d3e;
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
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>
</div>
    <h1>Usuários Cadastrados</h1>

    <?php if ($mensagem): ?>
        <div class="mensagem success"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>
    <?php if ($erro): ?>
        <div class="mensagem error"><?= htmlspecialchars($erro) ?></div>
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
                        <a href="cadastrosatuais.php?editar_id=<?= $row['id'] ?>" class="acao-btn">Editar</a>
                        <a href="cadastrosatuais.php?excluir_id=<?= $row['id'] ?>" class="acao-btn excluir-btn" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum usuário cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($editarUsuario): ?>
        <h2>Editar Usuário ID <?= $editarUsuario['id'] ?></h2>
        <form method="POST" action="cadastrosatuais.php">
            <input type="hidden" name="id" value="<?= $editarUsuario['id'] ?>" />
            
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($editarUsuario['nome']) ?>" />
            
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($editarUsuario['email']) ?>" />
            
            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required value="<?= htmlspecialchars($editarUsuario['celular']) ?>" />
            
            <label for="senha">Senha: <small>(Preencha apenas se deseja alterar)</small></label>
            <input type="password" id="senha" name="senha" placeholder="Nova senha" />
            
            <button type="submit" name="editar">Salvar Alterações</button>
        </form>
    <?php endif; ?>
</body>
</html>
