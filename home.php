<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel Principal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #121212;
      color: #f0f0f0;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      padding: 20px;
      background: linear-gradient(90deg, #00c9ff, #92fe9d);
      text-align: center;
    }

    header h1 {
      color: #000;
      font-size: 2rem;
    }

    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px 20px;
    }

    .painel-botoes {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      max-width: 800px;
      margin-bottom: 30px;
    }

    .botao-painel {
      background-color: #1f1f1f;
      color: #fff;
      border: 1px solid #444;
      border-radius: 10px;
      padding: 20px 30px;
      font-size: 16px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 0 10px #00f5ff88;
      transition: all 0.3s ease;
    }

    .botao-painel:hover {
      background-color: #333;
      transform: scale(1.05);
      box-shadow: 0 0 15px #00f5ff;
    }

    .botao-logout {
      margin-top: 10px;
      padding: 10px 20px;
      background-color: #b00020;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: bold;
    }

    .botao-logout:hover {
      background-color: #ff1744;
    }

    footer {
      background-color: #1a1a1a;
      text-align: center;
      padding: 20px;
      color: #aaa;
    }

    @media (max-width: 600px) {
      .botao-painel {
        width: 100%;
        justify-content: center;
        text-align: center;
      }
    }
  </style>
</head>
<body>

<header>
<h1>Bem vindo ao Painel Principal, <?= ucwords(strtolower($_SESSION['usuario'])) ?>!</h1>
</header>

<main>
  <div class="painel-botoes">
    <a href="admin.php" class="botao-painel"><i class="fas fa-bell"></i> Mensagens não respondidas</a>
    <a href="cadastrosatuais.php" class="botao-painel"><i class="fas fa-list"></i> Cadastros atuais</a>
    <a href="dashboard.php" class="botao-painel"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="email-recebido.php" class="botao-painel"><i class="fas fa-envelope-open-text"></i> E-mails recebidos e e-mail marketing</a>
    <a href="perfil.php" class="botao-painel"><i class="fas fa-user-circle"></i> Perfil</a>
    <a href="respondidas.php" class="botao-painel"><i class="fas fa-reply"></i> Mensagens respondidas</a>
  </div>

  <form action="logout.php" method="POST">
    <button type="submit" class="botao-logout">Sair</button>
  </form>
</main>

<footer>
  <p>&copy; 2025 Tech Tecnologia | Todos os direitos reservados.</p>
</footer>

</body>

</html>
