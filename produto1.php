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
        <link rel="stylesheet" href="css/acessibilidade">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="css//video-produto1.css">        
        <link rel="shortcut icon" href="img/shortcut icon.png">        
     <title>Tech Tecnologia</title>
     </head>    

      <style>
      .foto-container {
   display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    align-items: stretch;
}

.foto-container .foto {
  width: 30%;
    min-width: 200px;
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.foto-container .foto:hover {
  transform: scale(1.05);
}


     </style>

<button id="toggle-dark-mode" class="modo-toggle">
  <i class="fa fa-moon" id="icon-dark"></i> <span id="modo-label">Modo Dark</span>
</button>

    <header>   
        <a href="index.html" class="btn-voltar">
  <i class="fa fa-arrow-left"></i> Voltar
</a>     
        <div class="header-conteudo">
            <img src="img/Slogo felippe.png" alt="Logo Mercado" class="logo">
    
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
       <!-- <div class="user-icon">
  <a href="login.php">
    <i class="fas fa-user"></i>
  </a>
</div> -->

    </header>

    <!-- <section class="sobre">
        <h2>Sobre a Tech Tecnologia</h2>
        <p>Sou especialista em criar landing pages modernas e eficientes, feitas para atrair e converter seus clientes. Além disso, ofereço um serviço de manutenção mensal para garantir que seu site esteja sempre atualizado, seguro e funcionando perfeitamente.</p>
  <ul>
    <li>Desenvolvimento personalizado e responsivo</li>
    <li>Manutenção contínua para melhorias e segurança</li>
    <li>Suporte dedicado e rápido sempre que precisar</li>
    <li>Foco em resultados reais para o seu negócio</li>
  </ul>
    </section> -->

    <!-- <section class="produto">
        <div class="produto-container">
            <div class="produto-imagem">
                <img src="img/computer.png" alt="Produto em Destaque" class="imagem-produto">
            </div>
            <div class="produto-info">
                <h2>Produto em Destaque</h2>
                <p>Este é o nosso produto especial, que está com uma promoção imperdível!</p> 
                <a href="produto1.php" class="btn-ver-mais">Ver mais</a> 
                
            </div>
        </div>
    </section>
    
    <section class="produto">
        <div class="produto-container reverse">
            <div class="produto-info">
                <h2>Produto em Destaque</h2>
                <p>Este é o nosso produto especial, que está com uma promoção imperdível!</p>
                <a href="#" class="btn-ver-mais">Ver mais</a>   
            </div>
            <div class="produto-imagem">
                <img src="img/computer.png" alt="Produto em Destaque" class="imagem-produto">
            </div>
        </div>
    </section>   -->

    <section class="video-destaque">
  <div class="video-conteudo">
    <h2>Assista ao Nosso Vídeo</h2>
    <p>Veja como podemos transformar sua ideia em um projeto profissional e eficiente.</p>
    
    <div class="video-wrapper">
      <iframe width="560" height="315" src="video/Qual a diferença de.mp4" title="Vídeo Promocional" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
</section>


    
    
    
    

     <section class="foto-e-formulario" id="foto-e-formulario">
  <!-- <div class="foto-container">
    <img src="img/plano-basico.png" alt="Foto 1" class="foto">
    <img src="img/plano-intermediario.png" alt="Foto 2" class="foto">
    <img src="img/plano-avancado.png" alt="Foto 3" class="foto">
  </div> -->
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

      <button type="submit" class="botao-enviar">Enviar agora</button>
    </form>
  </div>
</section>


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
         Vila Velha/ES <BR>
         </p>
       <!-- <a style="color: black; text-decoration: none;" href="https://wa.me/5527998433504" target="_blank">
        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="WhatsApp" style="width: 20px; vertical-align: middle; margin-right: 5px;">
        Telefone Nutricionista
        </a> -->
    </div>
  </div>
  <div class="footer-copy">
    <p>&copy; 2025 Sua Empresa. Todos os direitos reservados.</p>
  </div>
</footer>
    <!-- Botão flutuante do WhatsApp -->
    <a href="https://wa.me/5527996110031" 
   class="whatsapp-float-btn" 
   target="_blank" 
   rel="noopener noreferrer" 
   aria-label="Fale conosco no WhatsApp">
  <img src="img/whatsapp-icon.png" alt="WhatsApp">
</a>


      
        </script>
        <script src="js/acessibilidade.js"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXX-Y"></script>    
        <script src="script.js"></script>
        
        
    
</body>
</html>
