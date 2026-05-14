<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Conheça os melhores produtos e ofertas exclusivas para você! Compre fácil e rápido com atendimento via WhatsApp.">
    <meta name="keywords" content="produtos, ofertas, descontos, loja online, comprar, promoção">
    <meta name="author" content="Sua Empresa">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/acessibilidade.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css//video-produto1.css">
    <link rel="shortcut icon" href="img/shortcut icon.png">
    <title>Tech Tecnologia</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RM3GH72GH4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-RM3GH72GH4');
    </script>
</head>
<body>

    <!-- VLibras widget -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <style>
        .foto-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: stretch;
        }

        .foto-container .foto {
            width: 35%;
            min-width: 200px;
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, z-index 0s;
            position: relative;
            z-index: 1;
        }

        .foto-container .foto:hover {
            transform: scale(2.00);
            z-index: 10;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        /* DARK MODE - Seção Oferta Landing Page */
        body.dark-mode .oferta-landing-page {
            background-color: #1e1e1e !important;
            color: #e0e0e0;
        }

        body.dark-mode .oferta-landing-page strong {
            color: #fff;
        }

        body.dark-mode .oferta-landing-page strong[style*="color: #d10000"] {
            color: #ff5555 !important;
        }

        .footer-links-mercado {
            margin-top: 30px;
            text-align: center;
        }

        .footer-links-mercado h3 {
            color: #00f0ff;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .botoes-mercado {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .botao-mercado {
            background-color: #0077cc;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 0 8px #00f0ff;
        }

        .botao-mercado:hover {
            background-color: #00f0ff;
            color: black;
            transform: scale(1.05);
            box-shadow: 0 0 15px #00f0ff, 0 0 25px #00f0ff;
        }

        .btn-sobre-mim {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            padding: 6px 12px;
            background-color: #222;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid #555;
            cursor: pointer;
            box-shadow: 0 0 8px #00f0ff;
            transition: all 0.3s ease;
        }
        /* Container Geral */
.oferta-landing-page {
    padding: 80px 20px;
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-oferta {
    max-width: 1100px;
    margin: 0 auto;
}

.header-oferta {
    text-align: center;
    margin-bottom: 50px;
}

.badge {
    background: #0077cc22;
    color: #0077cc;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
}

.header-oferta h2 {
    font-size: 36px;
    color: #222;
    margin: 15px 0;
}

/* Grid de Cards */
.grid-servicos {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 25px;
    margin-bottom: 50px;
}

.card-servico {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    text-align: left;
    border-bottom: 3px solid transparent;
}

.card-servico:hover {
    transform: translateY(-10px);
    border-bottom: 3px solid #0077cc;
}

.icon-box {
    font-size: 30px;
    color: #0077cc;
    margin-bottom: 20px;
}

.card-servico h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #333;
}

.card-servico p {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
}

/* Área de Preço e CTA */
.cta-container {
    text-align: center;
    background: #222;
    padding: 40px;
    border-radius: 20px;
    color: #fff;
}

.preco-destaque p {
    font-size: 18px;
    opacity: 0.9;
    margin-bottom: 10px;
}

.valor {
    font-size: 60px;
    font-weight: 800;
    color: #00f0ff;
}

.moeda, .centavos {
    font-size: 24px;
    color: #00f0ff;
    vertical-align: super;
}

.btn-primary {
    display: inline-block;
    margin-top: 25px;
    padding: 15px 40px;
    background: #0077cc;
    color: #fff;
    text-decoration: none;
    border-radius: 50px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-primary:hover {
    background: #00f0ff;
    color: #000;
    box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
}

/* Dark Mode Adjustments */
body.dark-mode .oferta-landing-page {
    background: #121212 !important;
}
body.dark-mode .header-oferta h2 { color: #fff; }
body.dark-mode .card-servico { background: #1e1e1e; }
body.dark-mode .card-servico h3 { color: #fff; }
body.dark-mode .card-servico p { color: #bbb; }
    </style>

    <button id="toggle-dark-mode" class="modo-toggle" aria-label="Alternar modo escuro">
        <i class="fa fa-moon" id="icon-dark"></i>
        <span id="modo-label">Modo Dark</span>
    </button>

    <header>
        <a href="index.php" class="btn-voltar">
            <i class="fa fa-arrow-left"></i> Voltar
        </a>
        <div class="header-conteudo">
            <img src="img/minhalogo.png" alt="Logo Mercado" class="logo">
            <div class="header-textos">
                <h1>Tech Tecnologia</h1>
                <p class="slogan">Inovação em código, excelência em resultados</p>
            </div>
            <div class="container-botao cabecalho">
                <a href="#formulario" class="btn-destaque">
                    Conhecer Agora <i class="fa fa-arrow-right seta-responsiva"></i>
                </a>
            </div>
        </div>
    </header>
<!-- 
    <section class="video-destaque">
        <div class="video-conteudo">
            <h2>Assista ao Nosso Vídeo</h2>
            <p>Veja como podemos transformar sua ideia em um projeto profissional e eficiente.</p>
            <div class="video-wrapper">
                <iframe width="560" height="315" src="video/midia2.mp4" title="Vídeo Promocional" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </section> -->

<section class="oferta-landing-page">
    <div class="container-oferta">
        <div class="header-oferta">
            <span class="badge">Serviços Profissionais</span>
            <h2>Suporte TI e Manutenção Especializada</h2>
            <p>Garantimos o desempenho, a segurança e a estabilidade que você e sua empresa precisam.</p>
        </div>

        <div class="grid-servicos">
            <!-- Card 1 -->
            <div class="card-servico">
                <div class="icon-box"><i class="fas fa-desktop"></i></div>
                <h3>Formatação & OS</h3>
                <p>Instalação de Windows e Linux com otimização completa do sistema.</p>
            </div>
            <!-- Card 2 -->
            <div class="card-servico">
                <div class="icon-box"><i class="fas fa-shield-virus"></i></div>
                <h3>Segurança Digital</h3>
                <p>Remoção de vírus, malwares e instalação de defesas robustas.</p>
            </div>
            <!-- Card 3 -->
            <div class="card-servico">
                <div class="icon-box"><i class="fas fa-microchip"></i></div>
                <h3>Hardware & Upgrade</h3>
                <p>Limpeza interna, troca de peças e upgrades (SSD/Memória).</p>
            </div>
            <!-- Card 4 -->
            <div class="card-servico">
                <div class="icon-box"><i class="fas fa-network-wired"></i></div>
                <h3>Redes & Suporte</h3>
                <p>Configuração de roteadores, impressoras e suporte remoto ágil.</p>
            </div>
        </div>

        <div class="cta-container">
            <div class="preco-destaque">
                <p>Formatação completa a partir de</p>
                <span class="moeda">R$</span>
                <span class="valor">150</span>
                <span class="centavos">,00</span>
            </div>
            <p class="obs-texto">Atendemos Notebooks, Desktops e Contratos Empresariais.</p>
            <a href="#formulario" class="btn-primary">Solicitar Orçamento Grátis</a>
        </div>
    </div>
</section>

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

    <footer class="footer border">
        <div class="footer-container">
            <div class="footer-social">
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
                    <img src="img/facebook.png" alt="Facebook">
                </a>
                <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer">
                    <img src="img/instagram.png" alt="Instagram">
                </a>
                <a href="https://www.twitter.com" target="_blank" rel="noopener noreferrer">
                    <img src="img/twitter.png" alt="Twitter">
                </a>
                <a href="https://www.linkedin.com/in/felippefardin/" target="_blank" rel="noopener noreferrer">
                    <img src="img/linkedin.png" alt="LinkedIn">
                </a>
                <a href="https://www.youtube.com" target="_blank" rel="noopener noreferrer">
                    <img src="img/youtube.png" alt="YouTube">
                </a>
            </div>

            <div class="footer-info">
                <p>Endereço: <br>
                    Vila Velha/ES <br>
                </p>
                <a href="sobre.php" class="btn-sobre-mim">
                    <i class="fas fa-user"></i> Sobre Mim
                </a>
            </div>
        </div>

        <div class="footer-links-mercado">
            <h3>Receba seus pagamentos com a MERCADO PAGO</h3>
            <div class="botoes-mercado">
                <a href="https://mpago.li/3177rXL" target="_blank" class="botao-mercado">
                    <i class="fas fa-credit-card"></i> MINI NFC 2
                </a>
                <a href="https://mpago.li/1AUKHmw" target="_blank" class="botao-mercado">
                    <i class="fas fa-credit-card"></i> POINT AIR 2
                </a>
                <a href="https://mpago.li/2q5guMR" target="_blank" class="botao-mercado">
                    <i class="fas fa-credit-card"></i> POINT SMART 2
                </a>
                <a href="https://mpago.li/1giRaVp" target="_blank" class="botao-mercado">
                    <i class="fas fa-mobile"></i> APP MERCADO PAGO
                </a>
                <a href="https://mpago.li/2qVdLDW" target="_blank" class="botao-mercado">
                    <i class="fas fa-credit-card"></i> POINT PRO 3
                </a>
            </div>
        </div>

        <div class="footer-copy">
            <p>&copy; 2025 Sua Empresa. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Botão flutuante do WhatsApp -->
    <a href="https://wa.me/5527999642716"
       class="whatsapp-float-btn"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Fale conosco no WhatsApp">
        <img src="img/whatsapp-icon.png" alt="WhatsApp">
    </a>

    <script>
        document.getElementById('form-contato').addEventListener('submit', function(e) {
            e.preventDefault(); // impede o envio imediato do formulário

            const checkbox = document.getElementById('lgpd');
            const mensagemLGPD = document.getElementById('mensagem-lgpd');
            const botao = document.querySelector('.botao-enviar');

            if (!checkbox.checked) {
                mensagemLGPD.style.display = 'block';
                return;
            } else {
                mensagemLGPD.style.display = 'none';
            }

            botao.innerHTML = "Enviando... ⏳";
            botao.disabled = true;

            setTimeout(() => {
                botao.innerHTML = "🚀 Enviar agora";
                botao.disabled = false;
                this.submit(); // envia o formulário após 3 segundos
            }, 3000);
        });
    </script>

    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
    <script src="js/acessibilidade.js"></script>
    <script src="script.js"></script>

</body>
</html>

