<?php
require_once __DIR__ . '/src/bootstrap.php';
$conn = db();

if (empty($_SESSION['recupera_email']) || empty($_SESSION['validado']) ||
    empty($_SESSION['validado_em']) || time() - (int) $_SESSION['validado_em'] > 600 ||
    !isset($_POST['nova_senha'])) {
    die("Requisição inválida.");
}
verify_csrf();

if (strlen((string) $_POST['nova_senha']) < 10) {
    die('A senha deve ter pelo menos 10 caracteres.');
}

$email = $_SESSION['recupera_email'];
$senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

// Atualiza a senha no banco
$stmt = $conn->prepare('UPDATE usuarios SET senha = ? WHERE email = ?');
$stmt->bind_param('ss', $senha, $email);
$stmt->execute();

// Marca o código de recuperação como usado
$stmt = $conn->prepare('UPDATE recuperacao_senha SET usado = 1 WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();

// Envia e-mail de confirmação
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/PHPMailer.php';
require 'PHPMailer/PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
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
