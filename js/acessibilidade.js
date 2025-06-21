
  const toggleBtn = document.getElementById('toggle-dark-mode');
  const icon = document.getElementById('icon-dark');
  const label = document.getElementById('modo-label');
  const body = document.body;

  // Ativa o modo salvo
  if (localStorage.getItem('modoDark') === 'ativo') {
    body.classList.add('dark-mode');
    icon.classList.replace('fa-moon', 'fa-sun');
    label.textContent = 'Modo Claro';
  }

  toggleBtn.addEventListener('click', () => {
    body.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
      icon.classList.replace('fa-moon', 'fa-sun');
      label.textContent = 'Modo Claro';
      localStorage.setItem('modoDark', 'ativo');
    } else {
      icon.classList.replace('fa-sun', 'fa-moon');
      label.textContent = 'Modo Dark';
      localStorage.removeItem('modoDark');
    }
  });

 
  document.addEventListener("DOMContentLoaded", function () {
    const botao = document.querySelector(".abrir-video-btn");
    const video = document.querySelector(".video-embed");

    botao.addEventListener("click", () => {
      video.classList.toggle("oculto");
      video.style.display = video.classList.contains("oculto") ? "none" : "block";
    });
  });

 
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-XXXXX-Y');
    
        document.addEventListener('DOMContentLoaded', function () {
            const produtos = document.querySelectorAll('.produto-container');
    
            function verificarScroll() {
                produtos.forEach(produto => {
                    const rect = produto.getBoundingClientRect();
                    if (rect.top < window.innerHeight - 100) {
                        produto.classList.add('aparecendo');
                    }
                });
            }
    
            window.addEventListener('scroll', verificarScroll);
            verificarScroll(); // Para ativar se já estiver visível
        });
   
        document.getElementById('form-contato').addEventListener('submit', function(e) {
            e.preventDefault(); // impede o envio imediato do formulário
        
            const botao = document.querySelector('.botao-enviar');
            botao.innerHTML = "Enviando... ⏳";
            botao.disabled = true;
        
            setTimeout(() => {
                botao.innerHTML = "🚀 Enviar agora";
                botao.disabled = false;
                // Aqui você pode enviar o formulário de verdade se quiser
                this.submit(); // Se quiser que ele envie depois dos 3 segundos
            }, 3000);
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



