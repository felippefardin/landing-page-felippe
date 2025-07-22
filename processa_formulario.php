<?php
$conn = new mysqli("localhost", "root", "", "felippe");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$whatsapp = $_POST['whatsapp'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';

if ($nome && $email && $mensagem) {
    $stmt = $conn->prepare("INSERT INTO emails (nome, email, whatsapp, mensagem, data_envio) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $nome, $email, $whatsapp, $mensagem);
    $stmt->execute();
    $stmt->close();
    header("Location: obrigado.html");
    exit;
} else {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Erro no envio</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <style>
            :root {
                --bg: #ffffff;
                --text: #212529;
                --box-bg: #fff3cd;
                --box-text: #856404;
                --border-color: #000000;
                --btn-bg: #ffc107;
                --btn-text: #212529;
                --btn-hover: #e0a800;
            }

            body.dark-mode {
                --bg: #121212;
                --text: #f1f1f1;
                --box-bg: #33312b;
                --box-text: #f8e98f;
                --border-color: #f8e98f;
                --btn-bg: #f8e98f;
                --btn-text: #333;
                --btn-hover: #e0d872;
            }

            body {
                background-color: var(--bg);
                color: var(--text);
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                transition: all 0.3s ease;
            }

            .mensagem-erro {
                background-color: var(--box-bg);
                color: var(--box-text);
                padding: 30px;
                border-radius: 10px;
                border: 3px solid var(--border-color);
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }

            .mensagem-erro h1 {
                margin-bottom: 20px;
                font-size: 20px;
            }

            .mensagem-erro button {
                padding: 10px 20px;
                font-size: 16px;
                background-color: var(--btn-bg);
                color: var(--btn-text);
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .mensagem-erro button:hover {
                background-color: var(--btn-hover);
            }

            #toggle-dark-mode {
                position: fixed;
                top: 15px;
                right: 15px;
                background-color: var(--btn-bg);
                color: var(--btn-text);
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                z-index: 999;
            }

            #toggle-dark-mode:hover {
                background-color: var(--btn-hover);
            }
        </style>
    </head>
    <body>

        <!-- Botão de alternar modo -->
        <button id="toggle-dark-mode">
            <i class="fa fa-moon"></i> <span id="modo-label">Modo Dark</span>
        </button>

        <div class="mensagem-erro">
            <h1>Por favor, preencha todos os campos.</h1>
            <button onclick="history.back()">Voltar</button>
        </div>

        <script>
            // Aplica o tema salvo
            (function () {
                const temaSalvo = localStorage.getItem("theme");
                if (temaSalvo === "dark") {
                    document.body.classList.add("dark-mode");
                    document.getElementById("modo-label").textContent = "Modo Light";
                    document.querySelector("#toggle-dark-mode i").classList.replace("fa-moon", "fa-sun");
                }
            })();

            // Alterna o tema
            document.getElementById("toggle-dark-mode").addEventListener("click", function () {
                const body = document.body;
                const label = document.getElementById("modo-label");
                const icon = this.querySelector("i");

                body.classList.toggle("dark-mode");
                const darkAtivo = body.classList.contains("dark-mode");

                localStorage.setItem("theme", darkAtivo ? "dark" : "light");
                label.textContent = darkAtivo ? "Modo Light" : "Modo Dark";
                icon.classList.replace(darkAtivo ? "fa-moon" : "fa-sun", darkAtivo ? "fa-sun" : "fa-moon");
            });
        </script>

    </body>
    </html>
    <?php
}
?>

