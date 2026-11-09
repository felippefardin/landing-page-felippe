<?php
require_once __DIR__ . "/src/reviews.php";
csrf_token();
$arquivo = "contador.txt";
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, 0);
}
$visitas = (int)file_get_contents($arquivo);
$visitas++;
file_put_contents($arquivo, $visitas);
?>


<!DOCTYPE html>
<html lang="pt-BR">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Desenvolvemos landing pages, sites e e-commerces que geram resultados. Inove com performance, segurança e design personalizado.">
    <meta name="author" content="Tech Tecnologia">
    <link rel="canonical" href="https://felippeandreatadev.page.gd/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Landing Pages, Sites e Lojas Virtuais | Tech Tecnologia">
    <meta property="og:description" content="Landing pages, sites e e-commerces com foco em resultados, performance e segurança.">
    <link rel="canonical" href="https://felippeandreatadev.page.gd/">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="4be8f947ce798c28" />
    <link rel="stylesheet" href="css/style.css?v=20261014-contato">
    <link rel="stylesheet" href="css/acessibilidade.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/modal-captacao.css">
    <link rel="stylesheet" href="css/index-modern.css?v=20260814">
    <link rel='icon' type='image/png' sizes='32x32' href='img/favicon-32x32.png?v=20261007'>
    <link rel='icon' type='image/png' sizes='192x192' href='img/favicon-192x192.png?v=20261007'>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico?v=20261007'>
    <link rel='apple-touch-icon' sizes='180x180' href='img/apple-touch-icon.png?v=20261007'>
    <title>Landing Pages, Sites e Lojas Virtuais | Tech Tecnologia</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RM3GH72GH4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-RM3GH72GH4');
    </script>
    <script src="js/acessibilidade-global.js?v=20261109-header2" defer></script>
    <link rel="stylesheet" href="css/public-modern.css?v=20260814c">
    <script src="js/public-shell.js?v=20260814b" defer></script>
    <link rel="stylesheet" href="css/home-sections.css?v=20261109">
    <link rel="stylesheet" href="css/avaliacoes.css?v=20261109">
    <script src="js/avaliacoes.js?v=20261109-manual" defer></script>
<style id="home-wide-layout">
body.home-wide{--home-gutter:clamp(16px,2.2vw,48px);--home-gap:24px}
body.home-wide #conteudo-principal{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:var(--home-gap);width:calc(100% - 2 * var(--home-gutter));max-width:none;margin:24px auto 40px;padding:0;box-sizing:border-box}
body.home-wide #conteudo-principal>section{grid-column:1/-1;box-sizing:border-box;width:100%;max-width:none;min-width:0;margin:0;padding:clamp(24px,2.5vw,44px);border-radius:20px;scroll-margin-top:100px}
body.home-wide #conteudo-principal>section.produto{grid-column:span 1;display:flex;padding:24px}
body.home-wide #conteudo-principal .produto-container{display:grid;grid-template-columns:minmax(0,.95fr) minmax(0,1.05fr);align-items:center;gap:24px;width:100%;min-width:0;height:100%;padding:0;border:0;box-shadow:none;background:transparent;color:inherit;border-radius:0}
body.home-wide #conteudo-principal .produto-imagem,body.home-wide #conteudo-principal .produto-info{min-width:0;width:100%;max-width:none;margin:0}
body.home-wide #conteudo-principal .produto-imagem{display:flex;align-items:center;justify-content:center;min-height:280px}
body.home-wide #conteudo-principal .produto-imagem img{display:block;width:100%!important;max-width:400px!important;height:280px!important;max-height:280px!important;object-fit:contain!important;border-radius:14px;margin:0 auto}
body.home-wide #conteudo-principal .produto-info{align-items:flex-start;text-align:left;gap:16px;padding:8px 0;align-self:stretch;justify-content:center}
body.home-wide #conteudo-principal .produto-info h2{margin:0;font-size:clamp(20px,1.45vw,28px);line-height:1.3}
body.home-wide #conteudo-principal .produto-info p{margin:0;line-height:1.6}
body.home-wide #conteudo-principal .btn-ver-mais{display:inline-flex;justify-content:center;align-items:center;max-width:100%;min-height:44px;box-sizing:border-box;padding:11px 18px;margin-top:8px;white-space:normal;text-align:center;line-height:1.4}
body.home-wide #conteudo-principal .benefit-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:24px;align-items:stretch}
body.home-wide #conteudo-principal .benefit-grid>article{height:100%;min-width:0;box-sizing:border-box;margin:0;padding:28px;text-align:left}
body.home-wide #conteudo-principal .trust-strip{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:24px;margin:24px 0 0;padding:20px;box-sizing:border-box}
body.home-wide #conteudo-principal .work-steps{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:24px;padding:0;margin:28px 0;align-items:stretch}
body.home-wide #conteudo-principal .work-steps>li{min-width:0;height:100%;box-sizing:border-box}
body.home-wide #conteudo-principal .sobre-intro,body.home-wide #conteudo-principal .work-section>p{max-width:850px;margin-inline:auto}
body.home-wide #conteudo-principal .wrapper-horizontal{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:32px;width:100%;align-items:stretch}
body.home-wide #conteudo-principal .foto-container,body.home-wide #conteudo-principal .formulario{width:100%;min-width:0;max-width:none;box-sizing:border-box;margin:0}
body.home-wide #conteudo-principal .foto-container{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;padding:0;align-content:center}
body.home-wide #conteudo-principal .foto-container .foto{width:100%;max-width:none;height:auto;object-fit:contain}
body.home-wide .header-conteudo{box-sizing:border-box;width:calc(100% - 2 * var(--home-gutter));max-width:none;margin-inline:auto;gap:clamp(32px,5vw,100px);padding:36px clamp(8px,2vw,36px)}
body.home-wide .header-textos{min-width:0;flex:1}
body.home-wide .header-textos h1{max-width:25ch;font-size:clamp(32px,3.3vw,66px);line-height:1.12}
body.home-wide .header-textos p{max-width:70ch}
body.home-wide .site-nav.public-nav{box-sizing:border-box;padding-inline:var(--home-gutter)}
body.home-wide footer .footer-container{width:calc(100% - 2 * var(--home-gutter));max-width:none;box-sizing:border-box;margin-inline:auto}
@media(max-width:1199px){body.home-wide #conteudo-principal>section.produto{grid-column:1/-1}body.home-wide #conteudo-principal .work-steps{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:767px){body.home-wide #conteudo-principal{grid-template-columns:minmax(0,1fr);gap:20px}body.home-wide #conteudo-principal>section{padding:24px 18px}body.home-wide #conteudo-principal>section.produto{padding:20px}body.home-wide #conteudo-principal .produto-container{grid-template-columns:minmax(0,1fr);gap:20px}body.home-wide #conteudo-principal .produto-imagem{min-height:0}body.home-wide #conteudo-principal .produto-imagem img{height:auto!important;max-height:340px!important}body.home-wide #conteudo-principal .produto-info{align-items:center;text-align:center}body.home-wide #conteudo-principal .benefit-grid,body.home-wide #conteudo-principal .work-steps,body.home-wide #conteudo-principal .trust-strip,body.home-wide #conteudo-principal .wrapper-horizontal{grid-template-columns:minmax(0,1fr)}body.home-wide .header-conteudo{padding:28px 0;gap:24px}body.home-wide .header-textos h1{font-size:clamp(30px,7vw,44px);max-width:none}body.home-wide #conteudo-principal .formulario{padding:20px 14px}body.home-wide #conteudo-principal .trust-strip{gap:14px}}
body.home-wide #conteudo-principal .work-wrap{width:100%;max-width:none;margin:0}
body.home-wide #conteudo-principal .work-faq{width:100%;max-width:none}
</style>
<link rel="stylesheet" href="css/memoria-desconto.css?v=20261109-memory1">
<script src="js/memoria-desconto.js?v=20261109-memory1" defer></script>
</head>
<body class="home-wide">
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
      padding: 0 10px;
    }

    .foto-container .foto {
      width: 100%;
      max-width: 300px;
      height: auto;
      border-radius: 10px;
      object-fit: cover;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }

    .foto-container .foto:hover {
      transform: scale(1.05);
    }

    .produto-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 40px;
      padding: 40px 20px;
      flex-wrap: wrap;
      background-color: #fff;
      color: black;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .produto-container.reverse {
      flex-direction: row-reverse;
    }

    .produto-imagem,
    .produto-info {
      flex: 1;
      min-width: 280px;
    }

    .produto-info {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
      text-align: left;
      gap: 15px;
    }

    @media (max-width: 768px) {
      .produto-container,
      .produto-container.reverse {
        flex-direction: column;
        gap: 20px;
      }

      .produto-info {
        align-items: center;
        text-align: center;
      }

      .produto-imagem img {
        max-width: 100%;
        height: auto;
      }

      .header-conteudo {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .header-textos h1 {
        font-size: 1.5rem;
      }

      .header-textos p {
        font-size: 1rem;
      }

      .formulario {
        padding: 0 15px;
      }

      .input-grupo label {
        font-size: 14px;
      }

      .botao-enviar {
        width: 100%;
      }

      .modal-box {
        width: 90%;
      }

      footer .footer-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
    }

    /* Estilo fluorescente para o botão Sobre Mim */
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
      box-shadow: 0 0 8px #00f0ff; /* brilho suave */
      transition: all 0.3s ease;
    }

    .btn-sobre-mim:hover,
    .btn-sobre-mim.glow-click {
      box-shadow: 0 0 15px #00f0ff, 0 0 30px #00f0ff;
      transform: scale(1.05);
    }

    .footer-social {
      margin-top: 20px; /* Você pode ajustar esse valor conforme desejar */
    }

    .btn-sobre-mim:hover {
      cursor: pointer;
    }

    .footer-social a {
      display: inline-block;
      margin: 0 10px;
    }

    .footer-social img {
      width: 30px;
      height: 30px;
      transition: transform 0.2s ease, filter 0.3s ease;
      filter: drop-shadow(0 0 5px #00f5ff) drop-shadow(0 0 10px #00f5ff);
      cursor: pointer;
    }

    .footer-social img:hover {
      transform: scale(1.1);
      filter: drop-shadow(0 0 15px #00f5ff) drop-shadow(0 0 30px #00f5ff);
    }

    .glow-click {
      animation: glowFlash 0.3s ease;
    }

    @keyframes glowFlash {
      0% {
        filter: drop-shadow(0 0 10px #fff) drop-shadow(0 0 20px #0ff);
      }

      100% {
        filter: drop-shadow(0 0 5px #00f5ff) drop-shadow(0 0 10px #00f5ff);
      }
    }

    /* Facebook */
    .footer-social a.facebook img {
      filter: drop-shadow(0 0 5px #1877f2);
    }

    .footer-social a.facebook img:hover,
    .footer-social a.facebook img.glow-click {
      filter: drop-shadow(0 0 10px #1877f2) drop-shadow(0 0 20px #1877f2);
    }

    /* Instagram */
    .footer-social a.instagram img {
      filter: drop-shadow(0 0 5px #e1306c);
    }

    .footer-social a.instagram img:hover,
    .footer-social a.instagram img.glow-click {
      filter: drop-shadow(0 0 10px #e1306c) drop-shadow(0 0 20px #e1306c);
    }

    /* Twitter */
    .footer-social a.twitter img {
      filter: drop-shadow(0 0 5px #1da1f2);
    }

    .footer-social a.twitter img:hover,
    .footer-social a.twitter img.glow-click {
      filter: drop-shadow(0 0 10px #1da1f2) drop-shadow(0 0 20px #1da1f2);
    }

    /* LinkedIn */
    .footer-social a.linkedin img {
      filter: drop-shadow(0 0 5px #0a66c2);
    }

    .footer-social a.linkedin img:hover,
    .footer-social a.linkedin img.glow-click {
      filter: drop-shadow(0 0 10px #0a66c2) drop-shadow(0 0 20px #0a66c2);
    }

    /* YouTube */
    .footer-social a.youtube img {
      filter: drop-shadow(0 0 5px #ff0000);
    }

    .footer-social a.youtube img:hover,
    .footer-social a.youtube img.glow-click {
      filter: drop-shadow(0 0 10px #ff0000) drop-shadow(0 0 20px #ff0000);
    }

    html body.public-page footer.footer .footer-social img {
      width: 24px;
      height: 24px;
      margin-top: 8px;
    }

    /* Margem para descer os ícones */
    .footer-social {
      margin-top: 25px;
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

    .acessibilidade-container {
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 9999;
      display: flex;
      gap: 10px;
    }

    .acessibilidade-container button {
      width: 45px;
      height: 45px;
      border: none;
      border-radius: 10px;
      background: #0d6efd;
      color: white;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .acessibilidade-container button:hover {
      transform: scale(1.05);
      background: #0b5ed7;
    }

    /* Modal de captação: visibilidade e camadas controladas pela própria index */
    #emailModal[hidden] {
      display: none !important;
    }

    #emailModal.modal-overlay.is-open {
      display: flex !important;
      position: fixed;
      inset: 0;
      z-index: 100100;
      align-items: center;
      justify-content: center;
      padding: 18px;
      box-sizing: border-box;
      background: rgba(0,0,0,.72);
    }

    #emailModal .modal-box {
      position: relative;
      width: min(100%, 440px);
      max-height: 90vh;
      overflow-y: auto;
      box-sizing: border-box;
    }
    </style>

    <button id="toggle-dark-mode" class="modo-toggle">
      <i class="fa fa-moon" id="icon-dark"></i> <span id="modo-label">Modo Dark</span>
    </button>

    <div class="acessibilidade-container">
        <button id="aumentar-fonte" aria-label="Aumentar tamanho da fonte">
            A+
        </button>

        <button id="diminuir-fonte" aria-label="Diminuir tamanho da fonte">
            A-
        </button>

        <button id="resetar-fonte" aria-label="Resetar tamanho da fonte">
            A
        </button>
    </div>

    <?php require __DIR__ . '/includes/public-nav.php'; ?>

    <header id="inicio" class="site-header">
        <div class="header-conteudo">
            <img src="img/logotipofelippe.png"
                 alt="Logo Tech Tecnologia"
                 class="logo">

            <div class="header-textos">
                <h1>Felippe Andreata | Desenvolvedor Web | Cybersecurity | Tecnologia</h1>

                <p class="slogan">
                    Transforme sua ideia em um projeto digital que valoriza sua marca. Desenvolvo landing pages, sites e soluções web para aproximar seu negócio de novos clientes.
                </p>

                <div class="header-beneficios">
                    <span>
                        <i class="fas fa-check-circle"></i>
                        Desenvolvimento Web
                    </span>

                    <span>
                        <i class="fas fa-check-circle"></i>
                        Sites Responsivos
                    </span>

                    <span>
                        <i class="fas fa-check-circle"></i>
                        Projetos Sob Medida
                    </span>
                </div>

                <div class="container-botao cabecalho">
                    <a href="#formulario" class="btn-destaque">
                        Solicitar orçamento gratuito
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main id="conteudo-principal">
        <section class="sobre" id="sobre">
            <h2>Sobre a Tech Tecnologia</h2>
            <p class="sobre-intro">Programação sob medida para transformar sua ideia em uma presença digital profissional, com foco em desempenho, usabilidade e oportunidades para seu negócio.</p>

            <div class="benefit-grid">
                <article><i class="fas fa-laptop-code" aria-hidden="true"></i><h3>Sites que geram oportunidades</h3><p>Landing pages e sites responsivos, rápidos e alinhados à sua marca.</p></article>
                <article><i class="fas fa-screwdriver-wrench" aria-hidden="true"></i><h3>Programação sob medida</h3><p>Desenvolvimento de funcionalidades e soluções web de acordo com as necessidades do seu projeto.</p></article>
                <article><i class="fas fa-shield-halved" aria-hidden="true"></i><h3>Evolução do seu projeto</h3><p>Atualizações de código, melhorias de desempenho e boas práticas de segurança para seu site.</p></article>
            </div>

            <div class="trust-strip" aria-label="Diferenciais">
                <span><strong>Atendimento direto</strong> com o responsável</span>
                <span><strong>Orçamento claro</strong> antes do serviço</span>
                <span><strong>Desenvolvimento personalizado</strong> para seu negócio</span>
            </div>
        </section>

        <section class="produto" id="servicos">
            <div class="produto-container">
                <div class="produto-imagem">
                    <img src="img/imagemdestaque.png?v=20261109-nova" alt="Seu negócio merece um site à altura: landing pages, sites e sistemas web" class="imagem-produto imagem-destaque-web" width="1374" height="1145" loading="lazy" decoding="async" style="display:block;width:100%;max-width:440px;height:auto;max-height:none;object-fit:contain;margin-inline:auto;border-radius:14px;">
                </div>

                <div class="produto-info">
                    <h2>Criação de Landing Page Exclusiva para Seu Negócio</h2>
                    <p>Clique e veja mais</p>
                    <a href="produto2.php" class="btn-ver-mais">Conhecer o serviço de Landing Page</a>
                </div>
            </div>
        </section>

        <section class="produto">
            <div class="produto-container reverse">
                <div class="produto-imagem">
                    <img src="img/cartilha.png" alt="Página de vendas otimizada por Tech Tecnologia" class="imagem-produto">
                </div>

                <div class="produto-info">
                    <h2>Dicas de segurança</h2>
                    <p>Confira orientações importantes para proteger seus dados online.</p>
                    <a href="produto1.php" class="btn-ver-mais">Ver dicas de segurança</a>
                </div>
            </div>
        </section>

        <!-- <section class="produto">
          <div class="produto-container reverse">
            <div class="produto-imagem">
              <img src="img/foldermanuntencaoesuportedois.png" alt="Serviços de programação e desenvolvimento web" class="imagem-produto">
            </div>

            <div class="produto-info">
              <h2>Programação e Desenvolvimento Web</h2>

              <p>
                Desenvolvimento de sites e soluções web, com foco em
                desempenho, segurança e usabilidade do seu projeto.
              </p>

              <ul style="margin-left: 20px; line-height: 1.8;">
                <li>Desenvolvimento de sites profissionais</li>
                <li>Criação de funcionalidades personalizadas</li>
                <li>Integração de formulários e serviços web</li>
                <li>Atualização e manutenção de código</li>
                <li>Otimização de desempenho do site</li>
                <li>Acompanhamento do projeto web</li>
              </ul>

              <p>
                Solicite um orçamento e transforme sua ideia em um projeto web profissional.
              </p>

              <a href="produto3.php" class="btn-ver-mais">
                Solicitar Orçamento
              </a>
            </div>
          </div>
        </section> -->

        <section class="memory-promo" id="desafio-memoria" aria-labelledby="memory-promo-title">
  <div><span class="memory-eyebrow">UM DESAFIO PARA SUA MEMÓRIA</span><h2 id="memory-promo-title">Encontre os pares. Ganhe 50% no seu próximo projeto.</h2><p>São 50 cartas e 25 pares, em posições diferentes a cada partida. Complete o tabuleiro em até 80 jogadas para conquistar seu voucher.</p><p class="memory-summary">3 partidas por e-mail confirmado · Cupom válido por 7 dias · Uma utilização</p></div>
  <div class="memory-promo-action"><div class="memory-card-preview" aria-hidden="true"><span>?</span><span>◆</span><span>◆</span></div><button class="memory-primary" id="memory-launch" type="button" disabled>Abrir jogo da memória</button><p>Gratuito. Desconto apenas no desenvolvimento.</p></div>
</section>
<dialog id="memory-dialog" class="memory-dialog" aria-labelledby="memory-title" data-no-translate translate="no">
  <div class="memory-shell">
    <div class="memory-heading"><div><span class="memory-eyebrow">DESAFIO TECH TECNOLOGIA</span><h2 id="memory-title">Jogo da memória</h2></div><button type="button" class="memory-close" id="memory-close" aria-label="Fechar jogo">×</button></div>
    <p class="memory-status" id="memory-status" role="status" aria-live="polite"></p>
    <div id="memory-account"></div>
    <form id="memory-email-form" class="memory-form">
      <h3>Confirme seu e-mail para jogar</h3><p>Enviamos um código de acesso. Se você vencer, o voucher chega nesse mesmo endereço.</p>
      <label for="memory-email">Seu e-mail</label><input id="memory-email" type="email" autocomplete="email" maxlength="254" required>
      <label class="memory-check"><input id="memory-consent" type="checkbox" required><span>Li as regras e concordo com o uso do meu e-mail para confirmação, registro das chances e envio do voucher. Isso não me cadastra em publicidade.</span></label>
      <button type="submit" class="memory-primary">Receber código</button>
    </form>
    <form id="memory-code-form" class="memory-form" hidden><h3>Confira sua caixa de entrada</h3><label for="memory-code">Código de 6 dígitos</label><input id="memory-code" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required><p>O código vale por 10 minutos. Confira também a pasta de spam.</p><button type="submit" class="memory-primary">Confirmar e-mail</button><button id="memory-back" type="button" class="memory-secondary">Voltar / solicitar outro código</button></form>
    <div id="memory-play" hidden>
      <div class="memory-score" aria-label="Progresso da partida"><span><strong id="memory-pairs">0 / 25</strong>Pares encontrados</span><span><strong id="memory-moves">0 / 80</strong>Jogadas usadas</span><span><strong id="memory-attempts">0 / 3</strong>Partidas usadas</span></div>
      <div id="memory-result" class="memory-result" hidden></div>
      <div id="memory-board" class="memory-board" role="group" aria-label="Tabuleiro com 50 cartas. Use Tab ou as setas para navegar."></div>
      <div class="memory-controls"><button type="button" id="memory-start" class="memory-primary">Começar partida</button><button type="button" id="memory-abandon" class="memory-secondary" hidden>Encerrar esta partida</button><button type="button" id="memory-change" class="memory-text">Trocar e-mail</button><button type="button" id="memory-resend" class="memory-secondary" hidden>Reenviar voucher por e-mail</button><button type="button" id="memory-refresh" class="memory-text">Atualizar jogo</button></div>
    </div>
    <details class="memory-rules"><summary>Regras, validade e privacidade</summary><ul><li>Encontre os 25 pares entre 50 cartas em até 80 jogadas. Virar duas cartas conta como uma jogada. A posição das cartas muda em cada partida.</li><li>Até 3 partidas por e-mail nesta campanha. Fechar a janela ou atualizar a página preserva a partida. Encerrar uma partida consome essa chance. Não há limite de tempo.</li><li>Ao vencer, você recebe um cupom individual de 50%, válido por 7 dias a partir da vitória, para uma única contratação confirmada dentro desse prazo.</li><li>O desconto vale somente para o desenvolvimento de landing page, site ou sistema web, conforme escopo e valor da proposta. Não inclui domínio, hospedagem, licenças nem serviços externos. Não é cumulativo e não pode ser convertido em dinheiro.</li><li>Apresente o cupom e o e-mail confirmado ao Felippe antes de contratar. O cupom será validado no painel. O jogo não gera uma contratação automática.</li><li>Armazenamos seu e-mail, verificação, chances, progresso e situação do cupom para operar a campanha e prevenir abuso. O serviço de e-mail recebe o endereço e as mensagens necessárias. Nenhuma informação do jogo é enviada à IA.</li></ul><a href="termos-lgpd.html" target="_blank" rel="noopener">Privacidade e contato</a></details>
  </div>
</dialog>

<section class="work-section" id="como-funciona" aria-labelledby="work-title">
            <div class="work-wrap">
                <span class="work-eyebrow">Da ideia à entrega</span>
                <h2 id="work-title">Como funciona a contratação</h2>
                <p class="work-intro">Um processo claro para você acompanhar cada etapa do seu projeto.</p>
                <ol class="work-steps">
                    <li><span class="work-number" aria-hidden="true">01</span><h3>Conversa inicial</h3><p>Você conta sua ideia, seu público e o que precisa resolver. Juntos, definimos as prioridades.</p></li>
                    <li><span class="work-number" aria-hidden="true">02</span><h3>Proposta e planejamento</h3><p>Você recebe uma proposta com escopo, investimento e prazo para avaliar antes de começar.</p></li>
                    <li><span class="work-number" aria-hidden="true">03</span><h3>Desenvolvimento</h3><p>O projeto ganha forma, com apresentação das etapas e alinhamento dos ajustes previstos na proposta.</p></li>
                    <li><span class="work-number" aria-hidden="true">04</span><h3>Entrega e próximos passos</h3><p>Após a validação, combinamos a publicação, as orientações de uso e as condições de suporte.</p></li>
                </ol>
                <a class="work-link" href="#formulario">Vamos conversar sobre seu projeto <span aria-hidden="true">→</span></a>
            </div>
        </section>

        <?php require __DIR__ . "/includes/reviews-public.php"; ?>

        <section class="work-section" id="perguntas-frequentes" aria-labelledby="faq-title">
            <div class="work-wrap">
                <span class="work-eyebrow">Antes de começar</span>
                <h2 id="faq-title">Perguntas frequentes</h2>
                <p class="work-intro">Tire suas dúvidas e dê o próximo passo com mais clareza.</p>
                <div class="work-faq">
                    <details><summary>Quanto custa desenvolver um site ou sistema?</summary><p>O investimento depende das páginas, funcionalidades e integrações necessárias. Entre em contato para receber uma proposta adequada ao seu projeto.</p></details>
                    <details><summary>Qual é o prazo de entrega?</summary><p>O prazo é definido na proposta, de acordo com a complexidade do projeto e a disponibilidade dos materiais necessários. As etapas são alinhadas antes do início.</p></details>
                    <details><summary>O que preciso fornecer para começar?</summary><p>Uma descrição do que você precisa, referências e, quando disponíveis, logotipo, textos e imagens. Na conversa inicial, combinamos os materiais e acessos necessários.</p></details>
                    <details><summary>O site vai funcionar no celular?</summary><p>A adaptação para celulares, tablets e computadores faz parte do desenvolvimento das páginas. Os dispositivos e navegadores atendidos são alinhados no escopo.</p></details>
                    <details><summary>Domínio e hospedagem estão incluídos?</summary><p>Esses itens são definidos na proposta. Podemos avaliar a estrutura que você já utiliza e identificar o que será necessário para publicar o projeto.</p></details>
                    <details><summary>Posso solicitar alterações durante o desenvolvimento?</summary><p>Sim. Os ajustes seguem o escopo e as condições combinadas. Novas funcionalidades podem exigir uma revisão do orçamento e do prazo antes da execução.</p></details>
                    <details><summary>Há manutenção e suporte após a entrega?</summary><p>As condições de suporte são combinadas na proposta. Melhorias, atualizações e manutenção contínua também podem ser avaliadas conforme sua necessidade.</p></details>
                </div>
                <p class="work-last">Ainda ficou com alguma dúvida? <a class="work-link" href="#formulario">Fale comigo</a></p>
            </div>
        </section>

        <section class="foto-e-formulario" id="foto-e-formulario">
            <div class="wrapper-horizontal">
                <div class="foto-container">
                    <img src="img/planodesuporte.png" alt="Imagem institucional da Tech Tecnologia" class="foto" loading="lazy">
                    <!-- <img src="img/manutencaoesuporte.png" alt="Foto 2" class="foto"> -->
                    <img src="img/conteudoseguros.png" alt="Orientações para uso seguro da tecnologia" class="foto" loading="lazy">
                </div>

                <div class="formulario" id="formulario">
                    <h2>Vamos conversar? </h2>
                    <p class="sub-texto">Preencha e receba uma resposta</p>

                    <form id="form-contato" action="processa_formulario.php" method="POST">
                        <div class="input-grupo com-icone">
                            <label for="nome">Nome completo</label>
                            <div class="input-wrapper">
                                <input type="text" id="nome" name="nome" placeholder="Seu nome completo" autocomplete="name" required>
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="mensagem"></div>
                        </div>

                        <div class="input-grupo com-icone">
                            <label for="email">Email</label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" placeholder="Digite seu melhor e-mail" autocomplete="email" required>
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="mensagem"></div>
                        </div>

                        <div class="input-grupo com-icone">
                            <label for="whatsapp">WhatsApp</label>
                            <div class="input-wrapper">
                                <input type="tel" id="whatsapp" name="whatsapp" placeholder="(27) 99999-9999" autocomplete="tel" inputmode="tel" required>
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="mensagem"></div>
                        </div>

                        <div class="input-grupo com-icone">
                            <label for="mensagem">Mensagem</label>
                            <div class="input-wrapper">
                                <textarea id="mensagem" name="mensagem" placeholder="Conte brevemente como podemos ajudar" rows="5" required></textarea>
                                <i class="fas fa-comment-dots"></i>
                            </div>
                            <div class="mensagem"></div>
                        </div>

                        <div class="input-grupo">
                            <label>
                                <input type="checkbox" id="lgpd" name="lgpd" required>
                                Declaro que li e aceito os <a href="termos-lgpd.html" target="_blank" rel="noopener noreferrer">Termos da LGPD</a>.
                            </label>
                            <div class="mensagem" id="mensagem-lgpd" style="color: red; display: none;">
                                Você precisa aceitar os termos para continuar.
                            </div>
                        </div>

                        <button type="submit" class="botao-enviar">Enviar agora</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- <section id="avaliacao">
      <h2>Avalie nosso trabalho</h2>
      <form action="salvar_avaliacao.php" method="POST">
        <label for="nome">Seu nome:</label>
        <input type="text" name="nome" required><br>

        <label>Nota:</label>
        <div class="estrelas">
          <input type="radio" name="nota" value="5" id="estrela5"><label for="estrela5">★</label>
          <input type="radio" name="nota" value="4" id="estrela4"><label for="estrela4">★</label>
          <input type="radio" name="nota" value="3" id="estrela3"><label for="estrela3">★</label>
          <input type="radio" name="nota" value="2" id="estrela2"><label for="estrela2">★</label>
          <input type="radio" name="nota" value="1" id="estrela1"><label for="estrela1">★</label>
        </div><br>

        <label for="mensagem">Comentário:</label><br>
        <textarea name="mensagem" rows="5" required></textarea><br>

        <button type="submit">Enviar Avaliação</button>
      </form>
    </section> -->

    <footer class="footer border">
        <div class="footer-container">
            <div class="footer-social">
                <a href="https://www.facebook.com" class="facebook" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <img src="img/facebook.png" alt="Facebook">
                </a>
                <a href="https://www.instagram.com" class="instagram" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <img src="img/instagram.png" alt="Instagram">
                </a>
                <a href="https://www.twitter.com" class="twitter" target="_blank" rel="noopener noreferrer" aria-label="X / Twitter">
                    <img src="img/twitter.png" alt="X / Twitter">
                </a>
                <a href="https://www.linkedin.com/in/felippefardin/" target="_blank" rel="noopener noreferrer">
                    <img src="img/linkedin.png" alt="LinkedIn">
                </a>
                <a href="https://www.youtube.com" class="youtube" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <img src="img/youtube.png" alt="YouTube">
                </a>
            </div>

            <img src="img/monograma.png" alt="Monograma Felippe Andreata" class="footer-monogram">

            <div class="footer-info">
                <p>Endereço: <br>
                    Vila Velha/ES <br>
                </p>

                <a href="sobre.php" class="btn-sobre-mim">
                    <i class="fas fa-user"></i> Sobre Mim
                </a>
            </div>

            <!-- <a style="color: black; text-decoration: none;" href="https://wa.me/5527998433504" target="_blank">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="WhatsApp" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                Telefone Nutricionista
            </a> -->
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
            <p>&copy; 2026 Tech Tecnologia. Todos os direitos reservados.</p>
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

    <!-- Modal de Captura de E-mail -->
    <div id="emailModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="emailModalTitulo" hidden>
        <div class="modal-box">
            <button type="button" class="modal-close" aria-label="Fechar cadastro de descontos" data-modal-close>&times;</button>
            <h2 id="emailModalTitulo">Seu próximo site com condições especiais</h2>
            <p>Cadastre seu e-mail e receba descontos exclusivos no desenvolvimento do seu site.</p>
            <form id="emailForm" method="POST" action="email-sucesso.php">
                <label class="sr-only" for="email-oferta">Seu e-mail</label>
                <input type="email" id="email-oferta" name="email" placeholder="Digite seu e-mail" autocomplete="email" required>
                <button type="submit">Quero receber descontos</button>
            </form>
        </div>
    </div>


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

            botao.innerHTML = "Enviando...";
            botao.disabled = true;

            botao.textContent = "Enviando...";
            botao.disabled = true;
            this.submit();
        });

        document.addEventListener("DOMContentLoaded", function() {
            const produtos = document.querySelectorAll(".produto-container");

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("aparecendo");
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });

            produtos.forEach(produto => {
                observer.observe(produto);
            });
        });
    </script>

    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script> new window.VLibras.Widget('https://vlibras.gov.br/app'); </script>
    <script src="js/acessibilidade.js?v=2"></script>
    <script src="script.js"></script>

    <!-- Controle único do modal: aguarda o HTML, abre em 2s e fecha pelo X, fundo ou Esc -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('emailModal');
            if (!modal) return;

            const fechar = modal.querySelector('[data-modal-close]');
            let focoAnterior = null;

            function abrirModal() {
                focoAnterior = document.activeElement;
                modal.removeAttribute('hidden');
                modal.classList.add('is-open');
                fechar?.focus();
            }

            function fecharModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('hidden', '');
                focoAnterior?.focus?.();
            }

            fechar?.addEventListener('click', fecharModal);

            modal.addEventListener('click', function (event) {
                if (event.target === modal) fecharModal();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.hasAttribute('hidden')) {
                    fecharModal();
                }
            });

            setTimeout(abrirModal, 2000);
        });
    </script>

</body>
</html>
<link rel="stylesheet" href="css/assistente.css?v=20261109-gemini">
<script src="js/assistente.js?v=20261109-gemini" defer></script>
