(() => {
  'use strict';
  document.querySelectorAll('[data-review-delete]').forEach(button => {
    button.addEventListener('click', event => {
      if (!window.confirm('Excluir definitivamente esta avaliação?')) event.preventDefault();
    });
  });
  const dialog = document.getElementById('review-dialog');
  if (!dialog) return;
  const form = document.getElementById('review-form');
  const result = document.getElementById('review-result');
  let previousFocus;
  function open() {
    if (dialog.open) return;
    previousFocus = document.activeElement;
    dialog.showModal();
  }
  function close() { dialog.close(); previousFocus?.focus(); }
  document.querySelectorAll('[data-review-open]').forEach(button => button.addEventListener('click', open));
  dialog.querySelectorAll('[data-review-close]').forEach(button => button.addEventListener('click', close));
  dialog.addEventListener('click', event => { if (event.target === dialog) { const r = dialog.getBoundingClientRect(); if (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom) close(); } });
  form.addEventListener('change', () => {
    const selected = Number(new FormData(form).get('estrelas') || 0);
    form.querySelectorAll('.review-star-choice').forEach(label => label.classList.toggle('is-rated', Number(label.querySelector('input').value) <= selected));
  });
  form.addEventListener('submit', async event => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const submit = form.querySelector('[type="submit"]');
    submit.disabled = true; result.textContent = 'Enviando avaliação…';
    try {
      const response = await fetch(form.action, { method: 'POST', body: new FormData(form), credentials: 'same-origin', headers: {'Accept':'application/json'} });
      const data = await response.json();
      result.textContent = data.message || 'Não foi possível enviar. Tente novamente.';
      if (response.ok && data.ok) {
        form.reset(); form.querySelectorAll('.is-rated').forEach(item => item.classList.remove('is-rated'));
      }
    } catch (_) { result.textContent = 'Não foi possível enviar. Verifique a conexão e tente novamente.'; }
    finally { submit.disabled = false; }
  });
  // Reviews open only when the visitor clicks the review button.
})();
