<?php
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
    <link rel="canonical" href="https://bugcriativo.page.gd/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Landing Pages, Sites e Lojas Virtuais | Tech Tecnologia">
    <meta property="og:description" content="Landing pages, sites e e-commerces com foco em resultados, performance e segurança.">
    <meta property="og:url" content="https://bugcriativo.page.gd/">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="4be8f947ce798c28" />      
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/acessibilidade.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/modal-captacao.css">
    <link rel="stylesheet" href="css/index-modern.css?v=20260814">
    <link rel="shortcut icon" href="img/shortcut icon.png">        
    <title>Landing Pages, Sites e Lojas Virtuais | Tech Tecnologia</title>

      <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-RM3GH72GH4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-RM3GH72GH4');
</script>    
    <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
    <link rel="stylesheet" href="css/public-modern.css?v=20260814c">
    <script src="js/public-shell.js?v=20260814b" defer></script>
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

 
  
    <nav class="site-nav" aria-label="Navegação principal">
      <a class="site-nav__brand" href="#inicio">Tech Tecnologia</a>
      <div class="site-nav__links">
        <a href="#inicio">Início</a>
        <a href="#servicos">Serviços</a>
        <a href="#sobre">Sobre</a>
        <a href="#formulario">Contato</a>
        <a class="site-nav__login" href="login.php">Área do cliente</a>
      </div>
    </nav>

    <header id="inicio">

<div class="header-conteudo">

    <img src="img/minhalogo.png"
         alt="Logo Tech Tecnologia"
         class="logo">

    <div class="header-textos">

        <h1>Suporte Técnico e Criação de Sites em Vila Velha</h1>

        <p class="slogan">
            Atendimento direto para computadores, landing pages, sites e segurança digital — com suporte remoto e presencial.
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
        <p class="sobre-intro">Soluções sob medida para quem precisa resolver problemas de tecnologia ou transformar uma ideia em presença digital.</p>
        <div class="benefit-grid">
          <article><i class="fas fa-laptop-code" aria-hidden="true"></i><h3>Sites que geram oportunidades</h3><p>Landing pages e sites responsivos, rápidos e alinhados à sua marca.</p></article>
          <article><i class="fas fa-screwdriver-wrench" aria-hidden="true"></i><h3>Suporte sem complicação</h3><p>Manutenção, formatação, remoção de vírus e melhoria de desempenho.</p></article>
          <article><i class="fas fa-shield-halved" aria-hidden="true"></i><h3>Segurança e continuidade</h3><p>Atualizações, boas práticas de segurança e manutenção mensal.</p></article>
        </div>
        <div class="trust-strip" aria-label="Diferenciais">
          <span><strong>Atendimento direto</strong> com o responsável</span>
          <span><strong>Orçamento claro</strong> antes do serviço</span>
          <span><strong>Suporte remoto</strong> e presencial</span>
        </div>
      </section>

   <section class="produto" id="servicos">
  <div class="produto-container">
    <div class="produto-imagem">
      <img src="img/imagemcriacao.png" alt="Página de vendas otimizada por Tech Tecnologia" class="imagem-produto">                 
    </div>
    <div class="produto-info">
      <h2>Criação de Landing Page Exclusiva para Seu Negócio</h2>
      <p>Clique e veja mais</p> 
      <a href="produto1.php" class="btn-ver-mais">Conhecer o serviço de Landing Page</a>
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
      <a href="produto2.php" class="btn-ver-mais">Ver dicas de segurança</a>
    </div>
  </div>
</section>

<section class="produto">
  <div class="produto-container reverse">
    <div class="produto-imagem">
      <img src="img/foldermanuntencaoesuportedois.png" alt="Serviços de suporte técnico e manutenção de computadores" class="imagem-produto">
    </div>

    <div class="produto-info">
      <h2>Suporte TI e Manutenção de Computadores</h2>

      <p>
        Atendimento especializado para computadores e notebooks, com foco em
        desempenho, segurança e estabilidade da sua máquina.
      </p>

      <ul style="margin-left: 20px; line-height: 1.8;">
        <li>Formatação de Computador – R$ 150,00</li>
        <li>Remoção de vírus e limpeza do sistema</li>
        <li>Instalação de programas e drivers</li>
        <li>Backup e recuperação de arquivos</li>
        <li>Otimização e melhoria de desempenho</li>
        <li>Suporte remoto e presencial</li>
      </ul>

      <p>
        Solicite um orçamento e deixe seu computador mais rápido, seguro e funcionando corretamente.
      </p>

      <a href="produto3.php" class="btn-ver-mais">
        Solicitar Orçamento
      </a>
    </div>
  </div>
</section>

    
    
    <section class="foto-e-formulario" id="foto-e-formulario">
  <div class="wrapper-horizontal">
    <div class="foto-container">
      <img src="img/planodesuporte.png" alt="Plano de suporte técnico da Tech Tecnologia" class="foto" loading="lazy">
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
          <i class="fas fa-envelope"></i>
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
    <button type="button" class="modal-close" aria-label="Fechar oferta" data-modal-close>&times;</button>
    <h2 id="emailModalTitulo">CADASTRE SEU E-MAIL</h2>
    <p>E ganhe mais 25% de desconto na sua página web</p>
    <form id="emailForm" method="POST" action="email-sucesso.php">
      <label class="sr-only" for="email-oferta">Seu e-mail</label>
      <input type="email" id="email-oferta" name="email" placeholder="Digite seu e-mail" autocomplete="email" required>
      <button type="submit">Quero Receber</button>
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

    botao.innerHTML = "Enviando... ⏳";
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
        <script src="js/modal-email-captacao.js?v=20260814"></script>
        <script src="js/index-modern.js?v=20260814"></script>
        </body>
        </html>
        
        
    


