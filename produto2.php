<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tech Tecnologia - Inovação em código, excelência em resultados. Suporte TI e Web Design.">
    <meta name="keywords" content="produtos, ofertas, descontos, loja online, comprar, promoção, suporte ti, manutenção, landing page">
    <meta name="author" content="Tech Tecnologia">
    <meta name="robots" content="index, follow">

    <!-- Links de Estilo Externos -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/acessibilidade.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/video-produto1.css">
    <link rel="shortcut icon" href="img/shortcut icon.png">
    
    <title>Tech Tecnologia</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RM3GH72GH4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-RM3GH72GH4');
    </script>

    <style>
        /* CONFIGURAÇÕES GERAIS E MODERNIZAÇÃO */
        :root {
            --primary: #0077cc;
            --secondary: #00f0ff;
            --dark-bg: #1a1a1a;
            --light-bg: #f8f9fa;
        }

        /* Foto Container */
        .foto-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: stretch;
            padding: 20px;
        }

        .foto-container .foto {
            width: 30%;
            min-width: 200px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .foto-container .foto:hover { transform: scale(1.05); }

        /* Estilização das Seções Modernas (Cards) */
        .oferta-landing-page {
            padding: 80px 20px;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', sans-serif;
        }

        .container-oferta {
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        .badge {
            background: #0077cc22;
            color: var(--primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .grid-servicos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }

        .card-servico {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.3s;
            text-align: left;
            border-bottom: 3px solid transparent;
        }

        .card-servico:hover {
            transform: translateY(-10px);
            border-bottom: 3px solid var(--primary);
        }

        .icon-box { font-size: 32px; color: var(--primary); margin-bottom: 15px; }

        /* CTA Container */
        .cta-container {
            background: #222;
            padding: 50px 40px;
            border-radius: 20px;
            color: #fff;
            margin-top: 40px;
        }

        .valor { font-size: 60px; font-weight: 800; color: var(--secondary); }

        /* Footer e Botões Mercado Pago */
        .footer-links-mercado { margin-top: 30px; text-align: center; }
        .botoes-mercado { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
        .botao-mercado {
            background-color: var(--primary);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            box-shadow: 0 0 8px var(--secondary);
        }

        .botao-mercado:hover {
            background-color: var(--secondary);
            color: black;
            transform: scale(1.05);
        }

        .iframe-wrapper {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        /* Botão de Download com Super Destaque */
.btn-destaque-download {
    display: inline-flex;
    align-items: center;
    gap: 15px;
    margin-top: 30px;
    padding: 22px 50px;
    background: #00f0ff; /* Cor Neon para contraste */
    color: #000 !important;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 800;
    font-size: 22px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.4s ease;
    box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
    animation: pulsar-botao 2s infinite; /* Efeito de atenção */
}

.btn-destaque-download:hover {
    background: #ffffff;
    transform: scale(1.08);
    box-shadow: 0 0 40px rgba(0, 240, 255, 0.8);
    color: #0077cc !important;
}

.btn-destaque-download i {
    font-size: 26px;
}

/* Animação de Pulsação */
@keyframes pulsar-botao {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 240, 255, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(0, 240, 255, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 240, 255, 0);
    }
}

/* Ajuste para mobile */
@media (max-width: 600px) {
    .btn-destaque-download {
        font-size: 18px;
        padding: 15px 30px;
        width: 90%;
        justify-content: center;
    }
}

        /* Dark Mode */
        body.dark-mode .oferta-landing-page { background: #1a1a1a !important; color: #fff; }
        body.dark-mode .card-servico { background: #252525; color: #fff; }
        body.dark-mode .card-servico p { color: #aaa; }
    </style>
</head>

<body>
    <!-- VLibras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="header-topo">
            <button id="toggle-dark-mode" class="modo-toggle">
                <i class="fa fa-moon" id="icon-dark"></i> <span id="modo-label">Modo Dark</span>
            </button>
            <a href="index.php" class="btn-voltar"><i class="fa fa-arrow-left"></i> Voltar</a>
        </div>
        
        <div class="header-conteudo">
            <img src="img/minhalogo.png" alt="Logo Tech Tecnologia" class="logo">
            <div class="header-textos">
                <h1>Tech Tecnologia</h1>
                <p class="slogan">Inovação em código, excelência em resultados</p>
            </div>
            <div class="container-botao cabecalho">
                <a href="#formulario" class="btn-destaque">Conhecer Agora <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </header>

    <!-- Seção Cartilha (Modernizada) -->
    <section class="oferta-landing-page">
        <div class="container-oferta">
            <div class="header-oferta">
                <span class="badge">Educação Digital</span>
                <h2>Cartilha de Segurança na Internet</h2>
                <p>Guia essencial para navegar com tranquilidade. Ideal para idosos e iniciantes no mundo digital.</p>
            </div>

            <div class="grid-servicos">
                <div class="card-servico">
                    <div class="icon-box"><i class="fas fa-user-shield"></i></div>
                    <h3>Proteção</h3>
                    <p>Como criar senhas seguras e proteger seus dados pessoais.</p>
                </div>
                <div class="card-servico">
                    <div class="icon-box"><i class="fas fa-search-location"></i></div>
                    <h3>Identifique Golpes</h3>
                    <p>Reconheça links falsos e tentativas de fraude por e-mail ou WhatsApp.</p>
                </div>
                <div class="card-servico">
                    <div class="icon-box"><i class="fas fa-mouse-pointer"></i></div>
                    <h3>Navegação</h3>
                    <p>Dicas para usar navegadores e redes sociais sem riscos.</p>
                </div>
            </div>

           <div class="cta-container">
    <p style="font-size: 1.2rem; opacity: 0.9;">Acesse o conteúdo completo gratuitamente</p>
    <div style="margin: 10px 0;">
        <span class="valor">PDF</span> <span class="periodo" style="color: #00f0ff;">Digital Gratuito</span>
    </div>
    
    <!-- Botão com a nova classe de destaque -->
    <a href="download_cartilha.php" target="_blank" class="btn-destaque-download">
        <i class="fas fa-file-download"></i> Baixar Cartilha PDF Agora
    </a>

    <?php
    $contadorArquivo = "contador_downloads.txt";
    $downloads = file_exists($contadorArquivo) ? (int)file_get_contents($contadorArquivo) : 0;
    echo "<p class='obs-texto' style='margin-top: 25px; font-weight: bold;'> Junte-se aos $downloads usuários que já baixaram!</p>";
    ?>
</div>

            <div class="iframe-wrapper">
                <iframe src="docs/CartilhaSeguranca.pdf.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
        </div>
    </section>

    <!-- Formulário de Contato -->
    <section class="foto-e-formulario" id="foto-e-formulario">
        <div class="formulario" id="formulario">
            <h2>Vamos conversar? </h2>
            <p class="sub-texto">Preencha e receba uma resposta</p>
            <form id="form-contato" action="processa_formulario.php" method="POST">
                <div class="input-grupo com-icone">
                    <label for="nome">Nome completo</label>
                    <div class="input-wrapper">
                        <input type="text" name="nome" placeholder="Seu nome completo">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="mensagem"></div>
                </div>

                <div class="input-grupo com-icone">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="Digite seu melhor email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="mensagem"></div>
                </div>

                <div class="input-grupo com-icone">
                    <label for="whatspp">Whatsapp</label>
                    <div class="input-wrapper">
                        <input type="text" name="whatsapp" placeholder="Digite seu whatsapp">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="mensagem"></div>
                </div>

                <div class="input-grupo com-icone">
                    <label for="mensagem">Mensagem</label>
                    <div class="input-wrapper">
                        <input type="text" name="mensagem" placeholder="Digite sua mensagem">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div class="mensagem"></div>
                </div>

                <div class="input-grupo">
                    <label>
                        <input type="checkbox" id="lgpd" name="lgpd" required>
                        Declaro que li e aceito os <a href="termos-lgpd.html" target="_blank">Termos da LGPD</a>.
                    </label>
                    <div class="mensagem" id="mensagem-lgpd" style="color: red; display: none;">
                        Você precisa aceitar os termos para continuar.
                    </div>
                </div>

                <button type="submit" class="botao-enviar">Enviar agora</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer border">
        <div class="footer-container">
            <div class="footer-social">
                <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
                <a href="https://www.linkedin.com/in/felippefardin/"><img src="img/linkedin.png" alt="LinkedIn"></a>
            </div>
            <div class="footer-info">
                <p>Vila Velha/ES</p>
                <a href="sobre.php" class="btn-sobre-mim"><i class="fas fa-user"></i> Sobre Mim</a>
            </div>
        </div>

        <div class="footer-links-mercado">
            <h3>Pagamentos com MERCADO PAGO</h3>
            <div class="botoes-mercado">
                <a href="https://mpago.li/3177rXL" class="botao-mercado"><i class="fas fa-credit-card"></i> MINI NFC 2</a>
                <a href="https://mpago.li/1AUKHmw" class="botao-mercado"><i class="fas fa-credit-card"></i> POINT AIR 2</a>
                <a href="https://mpago.li/1giRaVp" class="botao-mercado"><i class="fas fa-mobile"></i> APP MERCADO PAGO</a>
            </div>
        </div>
        <div class="footer-copy">
            <p>&copy; 2025 Tech Tecnologia. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/5527999642716" class="whatsapp-float-btn" target="_blank">
        <img src="img/whatsapp-icon.png" alt="WhatsApp">
    </a>

    <!-- Scripts -->
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>
    <script>
        // Validação simples e efeito de envio
        document.getElementById('form-contato').addEventListener('submit', function(e) {
            const checkbox = document.getElementById('lgpd');
            const botao = document.querySelector('.botao-enviar');
            if (!checkbox.checked) {
                e.preventDefault();
                document.getElementById('mensagem-lgpd').style.display = 'block';
                return;
            }
            botao.innerHTML = "Enviando... ⏳";
            botao.disabled = true;
        });
    </script>
    <script src="js/acessibilidade.js"></script>
    <script src="script.js"></script>
</body>
</html>