<?php

$conn = new mysqli("localhost", "root", "", "felippe");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];

        // Verifica se o e-mail já existe
        $sql_check = "SELECT * FROM email_cliente WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            // E-mail já existe
            $msg = "Este e-mail já está cadastrado.";
        } else {
            // Insere novo e-mail
            $sql_insert = "INSERT INTO email_cliente (email) VALUES (?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("s", $email);

            if ($stmt_insert->execute()) {
                $msg = "Cadastro realizado com sucesso!";
            } else {
                $msg = "Erro ao cadastrar e-mail.";
            }

            $stmt_insert->close();
        }

        $stmt_check->close();
        $conn->close();
    } else {
        $msg = "O campo de e-mail está vazio.";
    }
} else {
    $msg = "Acesso inválido.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>E-mail Recebido</title>
  <style>
    body {
      background-color: #f0f8ff;
      font-family: Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .sucesso-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 500px;
    }
    .sucesso-container h1 {
      color: <?= ($msg === "Cadastro realizado com sucesso!") ? '#28a745' : '#dc3545' ?>;
      font-size: 28px;
      margin-bottom: 20px;
    }
    .sucesso-container p {
      font-size: 16px;
      margin-bottom: 30px;
      color: #333;
    }
    .sucesso-container a {
      text-decoration: none;
      background-color: #007bff;
      color: #fff;
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: bold;
      margin: 5px;
      display: inline-block;
      transition: background-color 0.2s ease-in-out;
    }
    .sucesso-container a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="sucesso-container">
    <?php if ($msg === "Cadastro realizado com sucesso!"): ?>
        <h1>✅ E-mail recebido com sucesso!</h1>
    <?php else: ?>
        <h1>⚠️ Atenção</h1>
    <?php endif; ?>
    <p><?= htmlspecialchars($msg) ?></p>
    <a href="index.php">← Voltar ao site</a>
  </div>
</body>
</html>
