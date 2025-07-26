<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer/PHPMailer.php';
require 'PHPMailer/PHPMailer/SMTP.php';

function exibirMensagem($tipo, $mensagem, $voltar = 'admin.php') {
    $cor = $tipo === 'sucesso' ? '#d4edda' : '#f8d7da';
    $texto = $tipo === 'sucesso' ? '#155724' : '#721c24';
    $icone = $tipo === 'sucesso' ? '✔️' : '❌';

    echo "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Resultado do Envio</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .mensagem {
                background-color: $cor;
                color: $texto;
                padding: 30px;
                border-radius: 8px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                width: 90%;
                max-width: 500px;
            }
            .mensagem h2 {
                margin-bottom: 10px;
            }
            .mensagem .icone {
                font-size: 48px;
                margin-bottom: 10px;
            }
            .mensagem a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
            .mensagem a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='mensagem'>
            <div class='icone'>$icone</div>
            <h2>" . ($tipo === 'sucesso' ? "E-mail enviado com sucesso!" : "Erro ao enviar o e-mail") . "</h2>
            <p>$mensagem</p>
            <a href='$voltar'>" . ($tipo === 'sucesso' ? "Voltar ao painel" : "Tentar novamente") . "</a>
        </div>
    </body>
    </html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailDestino = $_POST['email_destino'];
    $mensagem = $_POST['mensagem'];

    $mail = new PHPMailer(true);

    try {        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'felippefardin@gmail.com'; 
        $mail->Password = 'uooy pktv klcx ktnb';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('felippefardin@gmail.com', 'Suporte');
        $mail->addAddress($emailDestino);

        $mail->isHTML(false);
        $mail->Subject = 'Resposta ao seu contato';
        $mail->Body    = $mensagem;

        if (!empty($_FILES['anexo']['name'])) {
            $mail->addAttachment($_FILES['anexo']['tmp_name'], $_FILES['anexo']['name']);
        }

        $mail->send();
        exibirMensagem('sucesso', 'Sua resposta foi enviada para ' . htmlspecialchars($emailDestino) . '.');
    } catch (Exception $e) {
        exibirMensagem('erro', 'Detalhes do erro: ' . $mail->ErrorInfo, 'javascript:history.back()');
    }
}
?>
