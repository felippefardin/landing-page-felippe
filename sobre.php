<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sobre o desenvolvedor - Tech Tecnologia">
  <meta name="keywords" content="desenvolvedor, tecnologia, portfólio, felippe fardin, sites, landing pages">
  <meta name="author" content="Felippe Fardin">
  <meta name="robots" content="index, follow">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/acessibilidade.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="shortcut icon" href="img/shortcut icon.png">
  <title>Sobre Mim | Tech Tecnologia</title>
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
    /* Header principal */
header {
    background: linear-gradient(90deg, #ff0000 0%, #0000ff 50%, #000000 100%);
    color: white;
    padding: 40px 20px;
    display: flex;
    justify-content: center;
}


/* Conteúdo dentro do header */
.header-conteudo {
    display: flex;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
    max-width: 1200px;
    width: 100%;
    justify-content: space-between;
}

.logo {
    width: 300px;
    height: auto;
}

.header-textos {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.header-textos h1 {
    font-size: 36px;
    margin: 0;
}

.slogan {
    font-size: 18px;
    color: #e0e0e0;
    margin: 0;
}

.container-botao {
    text-align: left;
}

/* Botão "Conhecer Agora" */
.btn-destaque {
    font-size: 35px;
    padding: 14px 28px;
    background: linear-gradient(to right, #ff0000 0%, #0000ff 50%, #000000 100%);
    color: #ffffff;
    border: 2px solid #000000;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-destaque:hover {
    background: linear-gradient(to right, #000000 0%, #0000ff 50%, #ff0000 100%);
    color: #ffffff;
    border-color: #ffffff;
}

.seta-responsiva {
    display: none;
}

/* Botão "Modo Dark" */
.modo-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #192844;
    color: #fff;
    border: none;
    padding: 12px 18px;
    border-radius: 30px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    cursor: pointer;
    z-index: 10000;
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modo-toggle:hover {
    background-color: #0e1b2e;
    transform: scale(1.05);
}

.modo-toggle i {
    font-size: 18px;
}

/* Botão Voltar */
.btn-voltar {
    position: absolute;
    top: 15px;
    left: 15px;
    font-size: 16px;
    color: #000;
    text-decoration: none;
    background-color: #fff;
    padding: 8px 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s ease, color 0.3s ease;
    z-index: 1000;
}

.btn-voltar i {
    font-size: 14px;
}

.btn-voltar:hover {
    background-color: #000;
    color: #fff;
}

@media (max-width: 1024px) {
    .header-conteudo {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px 10px;
    }

    .logo {
        max-width: 80%;
        height: auto;
    }

    .btn-destaque {
        font-size: 24px;
    }

    .seta-responsiva {
        display: inline-block;
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .btn-destaque:hover .seta-responsiva {
        transform: translateX(5px);
    }
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


</style>

<!-- Botão Acessibilidade - Modo Dark -->
<button id="toggle-dark-mode" class="modo-toggle">
  <i class="fa fa-moon" id="icon-dark"></i> <span id="modo-label">Modo Dark</span>
</button>

<!-- Botão Voltar -->
<a href="index.php" class="btn-voltar">
  <i class="fa fa-arrow-left"></i> Voltar
</a>

 <header>

<div class="header-conteudo">

    <img src="img/minhalogo.png"
         alt="Logo Tech Tecnologia"
         class="logo">

    <div class="header-textos">

        <h1>Suporte TI & Soluções Tecnológicas</h1>

        <p class="slogan">
            Manutenção de computadores, suporte técnico,
            desenvolvimento web e segurança digital.
        </p>

        <div class="header-beneficios">

            <span>
                <i class="fas fa-check-circle"></i>
                Formatação Profissional
            </span>

            <span>
                <i class="fas fa-check-circle"></i>
                Suporte Remoto
            </span>

            <span>
                <i class="fas fa-check-circle"></i>
                Atendimento Rápido
            </span>

        </div>

        <div class="container-botao cabecalho">

            <a href="#formulario" class="btn-destaque">
                Solicitar Orçamento
                <i class="fa fa-arrow-right"></i>
            </a>

        </div>

    </div>

</div>

</header>


<section class="sobre">
  <h2>Sobre Mim</h2>
  <p>Olá! Me chamo <strong>Felippe Fardin</strong>, sou desenvolvedor formado em Análise e desenvolvimento de sistemas
  pela Universidade Vila Velha e especializado na criação de <strong>landing pages, sites institucionais e lojas virtuais</strong>. Tenho paixão por transformar ideias em soluções digitais que realmente geram resultados.</p>

  <p>Minha missão é unir <strong>design criativo, tecnologia moderna e foco em conversão</strong> para que cada projeto entregue valor real ao cliente. Trabalho de forma personalizada, entendendo as necessidades específicas de cada negócio para entregar uma solução eficiente, responsiva e visualmente impactante.</p>

  <p>Ao longo da minha jornada, quero ajudar diversas empresas a fortalecer sua presença online com projetos que transmitem <strong>confiança, profissionalismo e inovação</strong>.</p>

  <ul>
    <li>Desenvolvimento web com HTML, CSS, JavaScript, PHP e MySQL</li>
    <li>Criação de páginas otimizadas para conversão</li>
    <li>Manutenção de sites e suporte técnico personalizado</li>
    <li>Integrações com APIs, e-mail marketing, formulários inteligentes e mais</li>
  </ul>

  <p><strong>Vamos conversar e tirar seu projeto do papel?</strong></p>
</section>



<section class="foto-container">
  <img src="img/felippe2.jpeg" alt="Exemplo de trabalho" class="foto">
  <img src="img/felippecartoon.jpeg" alt="Cartilha digital" class="foto">
  <img src="img/felippe1.jpeg" alt="Cartilha digital" class="foto">
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
<a href="https://wa.me/5527999642716" class="whatsapp-float-btn" target="_blank" aria-label="Fale conosco no WhatsApp">
  <img src="img/whatsapp-icon.png" alt="WhatsApp">
</a>

<script src="js/acessibilidade.js"></script>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script> new window.VLibras.Widget('https://vlibras.gov.br/app'); </script>
<script src="script.js"></script>
</body>
</html>
