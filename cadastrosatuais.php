<?php
require_once __DIR__ . '/src/bootstrap.php';

require_auth();
$conn = db();

$mensagem = isset($_GET['mensagem']) ? $_GET['mensagem'] : '';
$erro = isset($_GET['erro']) ? $_GET['erro'] : '';

// Excluir usuário.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    verify_csrf();

    $idExcluir = (int) $_POST['excluir_id'];
    $stmtExcluir = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
    $stmtExcluir->bind_param('i', $idExcluir);

    if ($stmtExcluir->execute()) {
        $mensagem = 'Usuário excluído com sucesso.';
        header('Location: cadastrosatuais.php?mensagem=' . urlencode($mensagem));
        exit;
    } else {
        $erro = 'Erro ao excluir usuário.';
        header('Location: cadastrosatuais.php?erro=' . urlencode($erro));
        exit;
    }
}

// Atualizar usuário.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    verify_csrf();

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
        $mensagem = 'Usuário atualizado com sucesso.';
        header(
            'Location: cadastrosatuais.php?mensagem='
            . urlencode($mensagem)
            . '&editar_id='
            . $id
        );
        exit;
    } else {
        $erro = 'Erro ao atualizar usuário.';
        header(
            'Location: cadastrosatuais.php?erro='
            . urlencode($erro)
            . '&editar_id='
            . $id
        );
        exit;
    }
}

// Buscar dados para editar.
$editarUsuario = null;

if (isset($_GET['editar_id'])) {
    $idEditar = intval($_GET['editar_id']);
    $sqlBuscar = "SELECT * FROM usuarios WHERE id = $idEditar";
    $resultBuscar = $conn->query($sqlBuscar);

    if ($resultBuscar && $resultBuscar->num_rows === 1) {
        $editarUsuario = $resultBuscar->fetch_assoc();
    } else {
        $erro = 'Usuário não encontrado para edição.';
    }
}

// Buscar todos os usuários.
$sql = 'SELECT id, nome, email, celular FROM usuarios ORDER BY id DESC';
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários Cadastrados - Mercado</title>

    <link rel="icon" type="image/png" sizes="32x32"
          href="img/favicon-32x32.png?v=20261007">
    <link rel="icon" type="image/png" sizes="192x192"
          href="img/favicon-192x192.png?v=20261007">
    <link rel="shortcut icon" type="image/x-icon"
          href="favicon.ico?v=20261007">
    <link rel="apple-touch-icon" sizes="180x180"
          href="img/apple-touch-icon.png?v=20261007">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script
        src="js/acessibilidade-global.js?v=20261110-reviews-layout"
        data-accessibility-fixed
        defer>
    </script>

    <link rel="stylesheet" href="css/layout-full.css?v=20261112-1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }

        h1 {
            color: #00a859;
        }

        .tabela-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            padding: 14px 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #00a859;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background: #f2f2f2;
        }

        /* A célula continua fazendo parte da estrutura da tabela. */
        body.layout-unified table td.acoes {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
            border: 1px solid #ddd;
        }

        /* O alinhamento flex fica dentro da célula. */
        .acoes-grupo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Remove a caixa que aparecia ao redor de Excluir. */
        body.layout-unified table td.acoes .form-excluir {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: auto !important;
            max-width: none !important;
            min-width: 0;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        button.acao-btn,
        a.acao-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            min-height: 38px;
            padding: 9px 16px;
            border: 0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            text-align: center;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
            user-select: none;
            transition: filter 0.2s ease;
        }

        /* Editar e Excluir com as mesmas dimensões. */
        body.layout-unified table td.acoes .acao-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            min-width: 80px;
            height: 38px;
            margin: 0 !important;
            padding: 0 16px !important;
            border: 0 !important;
            border-radius: 6px !important;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            color: #fff !important;
        }

        .salvar-btn,
        body.layout-unified table td.acoes .salvar-btn {
            background: #007d3e !important;
            color: #fff !important;
        }

        .cancelar-btn,
        body.layout-unified table td.acoes .cancelar-btn {
            background: #c62828 !important;
            color: #fff !important;
        }

        .acao-btn:hover {
            filter: brightness(0.9);
        }

        .acao-btn:focus-visible,
        .dropbtn:focus-visible {
            outline: 3px solid #2563eb;
            outline-offset: 3px;
        }

        /* Estilos exclusivos do formulário de edição. */
        #form-editar {
            box-sizing: border-box;
            width: 100%;
            max-width: 500px;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #fff;
        }

        #form-editar label {
            display: block;
            margin-top: 12px;
            font-weight: 700;
        }

        #form-editar input[type="text"],
        #form-editar input[type="email"],
        #form-editar input[type="password"] {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: inherit;
            font-size: 14px;
        }

        .senha-container {
            position: relative;
        }

        #form-editar #senha {
            padding-right: 46px;
        }

        .toggle-senha {
            position: absolute;
            right: 4px;
            top: 5px;
            bottom: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            margin: 0;
            padding: 0;
            border: 0;
            border-radius: 4px;
            background: transparent;
            color: #333;
            cursor: pointer;
        }

        .toggle-senha:focus-visible {
            outline: 3px solid #2563eb;
        }

        .botao-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        /* Mensagens no centro superior. */
        .mensagem {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            box-sizing: border-box;
            max-width: calc(100% - 32px);
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .success {
            background: #007d3e;
            color: #fff;
        }

        .error {
            background: #c62828;
            color: #fff;
        }

        /* Menu Conta. */
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #007d3e;
            color: #fff;
            font-family: inherit;
            font-size: 20px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            min-width: 220px;
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 10;
            overflow: hidden;
        }

        .dropdown-content a {
            display: block;
            padding: 12px 16px;
            color: #111;
            text-decoration: none;
            transition: background 0.2s;
        }

        .dropdown-content a:hover,
        .dropdown-content a:focus-visible {
            background: #007d3e;
            color: #fff;
        }

        .dropdown:hover .dropdown-content,
        .dropdown:focus-within .dropdown-content {
            display: block;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            h1 {
                font-size: 26px;
            }

            th,
            td {
                padding: 12px 8px;
            }

            .acoes-grupo {
                gap: 8px;
            }

            body.layout-unified table td.acoes .acao-btn {
                min-width: 72px;
                padding-inline: 12px !important;
            }

            .botao-container .acao-btn {
                flex: 1 1 auto;
            }
        }
    </style>
</head>

<body class="layout-unified">

<div class="dropdown-container">
    <div class="dropdown">
        <button type="button" class="dropbtn">
            Conta
            <i class="fa fa-arrow-down" aria-hidden="true"></i>
        </button>

        <div class="dropdown-content">
            <a href="home.php">
                <i class="fa fa-home" aria-hidden="true"></i>
                Home
            </a>

            <a href="perfil.php">
                <i class="fa fa-user" aria-hidden="true"></i>
                Perfil
            </a>

            <a href="respondidas.php">
                <i class="fa fa-message" aria-hidden="true"></i>
                Mensagens respondidas
            </a>

            <a href="dashboard.php">
                <i class="fa fa-tachometer" aria-hidden="true"></i>
                Dashboard
            </a>

            <a href="email-recebido.php">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                E-mails recebidos
            </a>

            <a href="cadastrosatuais.php">
                <i class="fa fa-user" aria-hidden="true"></i>
                Usuários
            </a>

            <a href="logout.php">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                Sair
            </a>
        </div>
    </div>
</div>

<h1>Usuários Cadastrados</h1>

<?php if ($mensagem): ?>
    <div
        id="mensagem-sucesso"
        class="mensagem success"
        role="status"
    >
        <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<?php if ($erro): ?>
    <div
        id="mensagem-erro"
        class="mensagem error"
        role="alert"
    >
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<div class="tabela-container">
    <table aria-label="Usuários cadastrados">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">E-mail</th>
                <th scope="col">Celular</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $row['id'] ?></td>

                    <td>
                        <?= htmlspecialchars($row['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['celular'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td class="acoes">
                        <div class="acoes-grupo">
                            <a
                                href="cadastrosatuais.php?editar_id=<?= (int) $row['id'] ?>"
                                class="acao-btn salvar-btn"
                                aria-label="Editar <?= htmlspecialchars($row['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            >
                                Editar
                            </a>

                            <form
                                method="POST"
                                action="cadastrosatuais.php"
                                class="form-excluir"
                                onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');"
                            >
                                <?= csrf_field() ?>

                                <button
                                    type="submit"
                                    name="excluir_id"
                                    value="<?= (int) $row['id'] ?>"
                                    class="acao-btn cancelar-btn"
                                    aria-label="Excluir <?= htmlspecialchars($row['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nenhum usuário cadastrado.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($editarUsuario): ?>
    <h2>
        Editar Usuário
        <?= htmlspecialchars($editarUsuario['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    </h2>

    <form id="form-editar" method="POST" action="cadastrosatuais.php">
        <?= csrf_field() ?>

        <input
            type="hidden"
            name="id"
            value="<?= (int) $editarUsuario['id'] ?>"
        >

        <input type="hidden" name="editar" value="1">

        <label for="nome">Nome:</label>
        <input
            type="text"
            id="nome"
            name="nome"
            required
            value="<?= htmlspecialchars($editarUsuario['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="email">E-mail:</label>
        <input
            type="email"
            id="email"
            name="email"
            required
            value="<?= htmlspecialchars($editarUsuario['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="celular">Celular:</label>
        <input
            type="text"
            id="celular"
            name="celular"
            required
            value="<?= htmlspecialchars($editarUsuario['celular'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="senha">
            Senha:
            <small>(Preencha apenas se deseja alterar)</small>
        </label>

        <div class="senha-container">
            <input
                type="password"
                id="senha"
                name="senha"
                placeholder="Nova senha"
                autocomplete="new-password"
            >

            <button
                type="button"
                id="toggleSenha"
                class="toggle-senha"
                aria-label="Mostrar senha"
                aria-pressed="false"
            >
                <i class="fa fa-eye" aria-hidden="true"></i>
            </button>
        </div>

        <div class="botao-container">
            <button type="submit" class="acao-btn salvar-btn">
                Salvar Alterações
            </button>

            <button
                type="button"
                id="cancelar-editar"
                class="acao-btn cancelar-btn"
            >
                Cancelar
            </button>
        </div>
    </form>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cancelarEditar = document.getElementById('cancelar-editar');

    if (cancelarEditar) {
        cancelarEditar.addEventListener('click', () => {
            window.location.href = 'cadastrosatuais.php';
        });
    }

    const senhaInput = document.getElementById('senha');
    const toggleSenha = document.getElementById('toggleSenha');

    if (senhaInput && toggleSenha) {
        toggleSenha.addEventListener('click', () => {
            const mostrar = senhaInput.type === 'password';
            const icone = toggleSenha.querySelector('i');

            senhaInput.type = mostrar ? 'text' : 'password';

            toggleSenha.setAttribute(
                'aria-label',
                mostrar ? 'Ocultar senha' : 'Mostrar senha'
            );

            toggleSenha.setAttribute(
                'aria-pressed',
                mostrar ? 'true' : 'false'
            );

            icone.classList.toggle('fa-eye', !mostrar);
            icone.classList.toggle('fa-eye-slash', mostrar);
        });
    }

    ['mensagem-sucesso', 'mensagem-erro'].forEach((id) => {
        const mensagem = document.getElementById(id);

        if (mensagem) {
            mensagem.style.display = 'block';

            setTimeout(() => {
                mensagem.style.display = 'none';
            }, 2000);
        }
    });
});
</script>

</body>
</html>