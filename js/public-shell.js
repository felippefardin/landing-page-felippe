(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    document.body.classList.add('public-page');

    if (!document.querySelector('.site-nav, .public-nav')) {
      const nav = document.createElement('nav');
      nav.className = 'public-nav';
      nav.setAttribute('aria-label', 'Navegação principal');
      nav.innerHTML =
        '<a class="public-nav__brand" href="index.php">Tech Tecnologia</a>' +
        '<div class="public-nav__links">' +
          '<a href="index.php">Início</a>' +
          '<a href="index.php#servicos">Serviços</a>' +
          '<a href="sobre.php">Sobre</a>' +
          '<a href="index.php#formulario">Contato</a>' +
          '<a class="public-nav__login" href="login.php">Área do cliente</a>' +
        '</div>';
      document.body.prepend(nav);
    }

    const socialMarkup =
      '<div class="public-footer__social" aria-label="Mídias sociais">' +
        social('https://www.facebook.com', 'img/facebook.png', 'Facebook') +
        social('https://www.instagram.com', 'img/instagram.png', 'Instagram') +
        social('https://www.twitter.com', 'img/twitter.png', 'X / Twitter') +
        social('https://www.linkedin.com/in/felippefardin/', 'img/linkedin.png', 'LinkedIn') +
        social('https://www.youtube.com', 'img/youtube.png', 'YouTube') +
      '</div>';

    const existingFooter = document.querySelector('footer');
    if (!existingFooter) {
      const footer = document.createElement('footer');
      footer.className = 'public-footer';
      footer.innerHTML =
        socialMarkup +
        '<p>Vila Velha/ES · Atendimento remoto e presencial</p>' +
        '<p>&copy; 2026 Tech Tecnologia. Todos os direitos reservados.</p>';
      document.body.append(footer);
    } else if (!existingFooter.querySelector('.footer-social, .public-footer__social')) {
      existingFooter.insertAdjacentHTML('afterbegin', socialMarkup);
    }
  });

  function social(url, image, label) {
    return '<a href="' + url + '" target="_blank" rel="noopener noreferrer" aria-label="' + label + '">' +
      '<img src="' + image + '" alt="">' +
    '</a>';
  }
})();
