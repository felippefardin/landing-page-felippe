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


</style>

<!-- Botão Acessibilidade - Modo Dark -->
<button id="toggle-dark-mode" class="modo-toggle">
  <i class="fa fa-moon" id="icon-dark"></i> <span id="modo-label">Modo Dark</span>
</button>

<!-- Botão Voltar -->
<a href="index.html" class="btn-voltar">
  <i class="fa fa-arrow-left"></i> Voltar
</a>

<header>
  <div class="header-conteudo">
    <img src="img/Slogo felippe.png" alt="Logo Tech Tecnologia" class="logo">

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
  <img src="img/felippe2.png" alt="Exemplo de trabalho" class="foto">
  <img src="img/felippecartoon.png" alt="Cartilha digital" class="foto">
  <img src="img/felippe1.png" alt="Cartilha digital" class="foto">
</section>

<footer class="footer border">
  <div class="footer-container">
    <div class="footer-social">
      <a href="https://www.facebook.com" target="_blank"><img src="img/facebook.png" alt="Facebook"></a>
      <a href="https://www.instagram.com" target="_blank"><img src="img/instagram.png" alt="Instagram"></a>
      <a href="https://www.twitter.com" target="_blank"><img src="img/twitter.png" alt="Twitter"></a>
      <a href="https://www.linkedin.com/in/felippefardin/" target="_blank"><img src="img/linkedin.png" alt="LinkedIn"></a>
      <a href="https://www.youtube.com" target="_blank"><img src="img/youtube.png" alt="YouTube"></a>
    </div>
    <div class="footer-info">
      <p>Endereço:<br>Vila Velha / ES</p>
    </div>
  </div>
  <div class="footer-copy">
    <p>&copy; Tech Tecnologia. Todos os direitos reservados.</p>
  </div>
</footer>

<a href="https://wa.me/5527999642716" class="whatsapp-float-btn" target="_blank" aria-label="Fale conosco no WhatsApp">
  <img src="img/whatsapp-icon.png" alt="WhatsApp">
</a>

<script src="js/acessibilidade.js"></script>
<script src="script.js"></script>
</body>
</html>
