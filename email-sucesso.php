
<?php
require_once __DIR__ . '/src/bootstrap.php';

// Fuso horário oficial de Brasília
date_default_timezone_set('America/Sao_Paulo');

$msg = '';
$cadastroSucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $msg = 'O campo de e-mail está vazio.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Informe um endereço de e-mail válido.';

    } else {
        try {
            $conn = db();

            // Verifica se o e-mail já está cadastrado
            $sql_check = "SELECT id FROM email_cliente WHERE email = ? LIMIT 1";

            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            $emailExiste = $stmt_check->num_rows > 0;
            $stmt_check->close();

            if ($emailExiste) {
                $msg = 'Este e-mail já está cadastrado.';

            } else {
                // Obtém a data e hora de Brasília,
                // independentemente do fuso do MySQL.
                $dataEnvio = (new DateTimeImmutable(
                    'now',
                    new DateTimeZone('America/Sao_Paulo')
                ))->format('Y-m-d H:i:s');

                // Grava explicitamente a data e a hora
                $sql_insert = "INSERT INTO email_cliente
                               (email, data_envio)
                               VALUES (?, ?)";

                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("ss", $email, $dataEnvio);

                if ($stmt_insert->execute()) {
                    $msg = 'Cadastro realizado com sucesso!';
                    $cadastroSucesso = true;
                } else {
                    $msg = 'Erro ao cadastrar e-mail.';
                }

                $stmt_insert->close();
            }

            $conn->close();

        } catch (Throwable $e) {
            error_log('Falha no cadastro de e-mail: ' . $e->getMessage());
            $msg = 'Não foi possível realizar o cadastro. Tente novamente.';
        }
    }

} else {
    $msg = 'Acesso inválido.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Confirmação do cadastro de e-mail na Tech Tecnologia.">
    <meta name="robots" content="noindex, nofollow">
    <title>Confirmação de e-mail | Tech Tecnologia</title>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png?v=20261007">
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon-192x192.png?v=20261007">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico?v=20261007">
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png?v=20261007">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/public-modern.css?v=20261009">
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
    <script src="js/public-shell.js?v=20261009" defer></script>
    <style>
      /* CSS exclusivo da página: evita conflitos com o cabeçalho e rodapé globais */
      body.email-confirmacao-page {
          margin: 0;
          min-height: 100vh;
          background: #f2f6fc;
          color: #15243b;
          font-family: Arial, Helvetica, sans-serif;
      }
      .email-confirmacao-page *,
      .email-confirmacao-page *::before,
      .email-confirmacao-page *::after { box-sizing: border-box; }
      .email-confirmacao-page .email-main {
          width: 100%;
          max-width: 1200px;
          min-height: 55vh;
          margin: 0 auto;
          padding: clamp(42px, 7vw, 94px) 20px;
          display: flex;
          align-items: center;
          justify-content: center;
      }
      .email-confirmacao-page .email-card {
          width: min(100%, 570px);
          padding: clamp(28px, 5vw, 52px);
          border: 1px solid rgba(31, 77, 139, 0.12);
          border-radius: 20px;
          background: #fff;
          box-shadow: 0 16px 45px rgba(7, 33, 75, .10);
          text-align: center;
      }
      .email-confirmacao-page .email-status-icon {
          width: 88px;
          height: 88px;
          border-radius: 50%;
          margin: 0 auto 24px;
          display: flex;
          align-items: center;
          justify-content: center;
          background: #e7f7ed;
          color: #20a354;
          font-size: 42px;
      }
      .email-confirmacao-page .email-card.is-warning .email-status-icon {
          background: #fff3e7;
          color: #c16a14;
      }
      .email-confirmacao-page .email-title {
          color: #0a438f;
          font-size: clamp(24px, 3.6vw, 32px);
          line-height: 1.22;
          margin: 0 0 14px;
          font-weight: 750;
      }
      .email-confirmacao-page .email-message {
          color: #47556a;
          font-size: 17px;
          line-height: 1.65;
          margin: 0 0 30px;
      }
      .email-confirmacao-page .email-return {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 10px;
          min-height: 48px;
          padding: 12px 24px;
          border-radius: 999px;
          border: 1px solid #0b58bc;
          color: #fff;
          background: linear-gradient(100deg, #125cbe, #087ca4);
          text-decoration: none;
          font-size: 15px;
          font-weight: 700;
          transition: transform .2s ease, box-shadow .2s ease;
          box-shadow: 0 6px 18px rgba(7, 99, 175, .22);
      }
      .email-confirmacao-page .email-return:hover,
      .email-confirmacao-page .email-return:focus-visible {
          transform: translateY(-2px);
          box-shadow: 0 9px 22px rgba(7, 99, 175, .30);
      }
      .email-confirmacao-page .email-return:focus-visible {
          outline: 3px solid #70cfff;
          outline-offset: 3px;
      }
      body.dark-mode.email-confirmacao-page {
          background: #0b1425;
          color: #eef4ff;
      }
      body.dark-mode.email-confirmacao-page .email-card {
          background: #15243b;
          border-color: #334662;
          box-shadow: 0 16px 40px rgba(0,0,0,.26);
      }
      body.dark-mode.email-confirmacao-page .email-title { color: #a5dfff; }
      body.dark-mode.email-confirmacao-page .email-message { color: #d2dcec; }
      body.dark-mode.email-confirmacao-page .email-status-icon { background: #163d2e; color: #71e39f; }
      body.dark-mode.email-confirmacao-page .email-card.is-warning .email-status-icon { background: #503519; color: #ffd38d; }
      @media (max-width: 600px) {
          .email-confirmacao-page .email-main { min-height: 50vh; padding: 30px 16px 48px; }
          .email-confirmacao-page .email-card { border-radius: 16px; }
          .email-confirmacao-page .email-status-icon { width: 74px; height: 74px; font-size: 34px; }
          .email-confirmacao-page .email-return { width: 100%; }
      }
      @media (prefers-reduced-motion: reduce) {
          .email-confirmacao-page .email-return { transition: none; }
      }
    </style>
</head>
<body class="email-confirmacao-page">
    <main id="conteudo-principal" class="email-main">
        <section class="email-card <?= $cadastroSucesso ? 'is-success' : 'is-warning' ?>" aria-labelledby="email-title">
            <div class="email-status-icon" aria-hidden="true">
                <i class="fa-solid <?= $cadastroSucesso ? 'fa-check' : 'fa-circle-exclamation' ?>"></i>
            </div>
            <h1 class="email-title" id="email-title">
                <?= $cadastroSucesso ? 'E-mail recebido com sucesso!' : 'Atenção ao cadastro' ?>
            </h1>
            <p class="email-message"><?= htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <a class="email-return" href="index.php">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Voltar ao site
            </a>
        </section>
    </main>
</body>
</html>
