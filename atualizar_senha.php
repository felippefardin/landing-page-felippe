<?php
session_start();
$conn = new mysqli("localhost", "root", "", "felippe");

if (!isset($_SESSION['recupera_email']) || !isset($_POST['nova_senha'])) {
    die("Requisição inválida.");
}

$email = $_SESSION['recupera_email'];
$senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

// Atualiza a senha no banco
$conn->query("UPDATE usuarios SET senha = '$senha' WHERE email = '$email'");

// Marca o código de recuperação como usado
$conn->query("UPDATE recuperacao_senha SET usado = 1 WHERE email = '$email'");

// Envia e-mail de confirmação
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/PHPMailer.php';
require 'PHPMailer/PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'felippefardin@gmail.com';
    $mail->Password = 'uooy pktv klcx ktnb'; // Troque pela sua senha de app do Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('felippefardin@gmail.com', 'Suporte');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Senha Alterada com Sucesso';
    $mail->Body = "<p>Sua senha foi alterada com sucesso em nosso sistema.</p>
                   <p>Se você não solicitou essa alteração, entre em contato imediatamente.</p>";

    $mail->send();
} catch (Exception $e) {
    error_log("Erro ao enviar e-mail de confirmação: " . $mail->ErrorInfo);
}

session_destroy();
header("Location: login.php?msg=senha_atualizada");
exit;
