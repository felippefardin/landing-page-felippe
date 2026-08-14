<?php
require_once __DIR__ . '/src/bootstrap.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer/PHPMailer.php';
require 'PHPMailer/PHPMailer/SMTP.php';

// Conexão com banco
$conn = db();

// Dados do formulário
$email = $_POST['usuario'] ?? '';
$email = trim($email);

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Endereço de e-mail inválido.");
}

// Gera código e validade
$codigo = random_int(100000, 999999);
$expira = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// Remove códigos antigos e insere novo
$stmt = $conn->prepare("DELETE FROM recuperacao_senha WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO recuperacao_senha (email, codigo, expira_em) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $codigo, $expira);
$stmt->execute();

// Configura o envio com PHPMailer
$mail = new PHPMailer(true);

try {
    // Configurações SMTP
    $mail->isSMTP();
    $smtp = smtp_config();
    $mail->Host = $smtp['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtp['username'];
    $mail->Password = $smtp['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $smtp['port'];

    $mail->setFrom($smtp['from_email'], $smtp['from_name']);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->Subject = 'Seu código de verificação';

    // Aqui está sua mensagem personalizada:
    $mail->Body = "
        <h2>Recuperação de Senha</h2>
        <p>Olá,</p>
        <p>Você solicitou a recuperação da sua senha no nosso sistema.</p>
        <p>Use o código abaixo para confirmar sua identidade:</p>
        <h3 style='color: #007bff;'>$codigo</h3>
        <p>Este código é válido por 10 minutos.</p>
        <hr>
        <p>Se você não solicitou essa alteração, ignore este e-mail.</p>
        <p>Atenciosamente,<br>Equipe Mercado</p>
    ";

    $mail->send();

    // Redireciona só se o e-mail foi enviado com sucesso
    $_SESSION['recupera_email'] = $email;
    $_SESSION['validado'] = false;
    header("Location: verificar_codigo.php");
    exit;

} catch (Exception $e) {
    // Se der erro, mostra mensagem simples
    echo "Erro ao enviar e-mail: " . $mail->ErrorInfo;
}
