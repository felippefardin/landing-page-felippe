(function () {
  'use strict';

  const modal = document.getElementById('emailModal');
  if (!modal) return;

  const closeButton = modal.querySelector('[data-modal-close]');
  const emailInput = modal.querySelector('input[type="email"]');
  let previousFocus = null;

  function openModal() {
    if (sessionStorage.getItem('ofertaEmailExibida') === '1') return;
    previousFocus = document.activeElement;
    modal.hidden = false;
    modal.style.display = 'flex';
    sessionStorage.setItem('ofertaEmailExibida', '1');
    window.setTimeout(() => emailInput?.focus(), 50);
  }

  function closeModal() {
    modal.hidden = true;
    modal.style.display = 'none';
    previousFocus?.focus?.();
  }

  closeButton?.addEventListener('click', closeModal);
  modal.addEventListener('click', (event) => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.hidden) closeModal();
  });

  // A oferta aparece após o visitante conhecer um pouco da página.
  window.setTimeout(openModal, 10000);
})();
