(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const videoButton = document.querySelector('.abrir-video-btn');
    const video = document.querySelector('.video-embed');
    if (videoButton && video) {
      videoButton.addEventListener('click', function () {
        video.classList.toggle('oculto');
        video.style.display = video.classList.contains('oculto') ? 'none' : 'block';
      });
    }

    const products = document.querySelectorAll('.produto-container');
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('aparecendo');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });
      products.forEach(function (product) { observer.observe(product); });
    } else {
      products.forEach(function (product) { product.classList.add('aparecendo'); });
    }
  });
})();
