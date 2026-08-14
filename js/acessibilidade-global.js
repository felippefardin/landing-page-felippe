(function () {
  'use strict';

  const FONT_KEY = 'tamanhoFonte';
  const THEME_KEY = 'modoDark';
  const MIN_FONT = 60;
  const MAX_FONT = 150;
  const STEP = 10;

  const storedFont = Number.parseInt(localStorage.getItem(FONT_KEY) || '100', 10);
  let fontSize = Number.isFinite(storedFont)
    ? Math.min(MAX_FONT, Math.max(MIN_FONT, storedFont))
    : 100;

  function applyPreferences() {
    document.documentElement.style.fontSize = fontSize + '%';
    document.documentElement.classList.toggle('accessibility-dark', localStorage.getItem(THEME_KEY) === 'ativo');
    document.body?.classList.toggle('dark-mode', localStorage.getItem(THEME_KEY) === 'ativo');
  }

  applyPreferences();

  function button(label, title, action) {
    const element = document.createElement('button');
    element.type = 'button';
    element.textContent = label;
    element.title = title;
    element.setAttribute('aria-label', title);
    element.addEventListener('click', action);
    return element;
  }

  function updateToolbar(toolbar) {
    const status = toolbar.querySelector('.accessibility-toolbar__status');
    const dark = document.documentElement.classList.contains('accessibility-dark');
    status.textContent = fontSize + '%';
    toolbar.querySelector('[data-action="theme"]').textContent = dark ? '☀' : '☾';
    toolbar.querySelector('[data-action="theme"]').setAttribute('aria-pressed', String(dark));
  }

  function createToolbar() {
    document.querySelectorAll('.acessibilidade-container, #toggle-dark-mode, .acessibilidade-fontes')
      .forEach((item) => item.classList.add('legacy-accessibility-hidden'));

    const skip = document.createElement('a');
    skip.href = '#conteudo-principal';
    skip.className = 'accessibility-skip-link';
    skip.textContent = 'Ir para o conteúdo principal';
    document.body.prepend(skip);

    const main = document.querySelector('main') || document.querySelector('h1')?.parentElement || document.body;
    if (!main.id) main.id = 'conteudo-principal';
    if (main !== document.body && !main.hasAttribute('tabindex')) main.tabIndex = -1;

    const toolbar = document.createElement('div');
    toolbar.id = 'accessibility-toolbar';
    toolbar.className = 'accessibility-toolbar';
    toolbar.setAttribute('role', 'group');
    toolbar.setAttribute('aria-label', 'Opções de acessibilidade');

    const decrease = button('A−', 'Diminuir fonte', () => {
      fontSize = Math.max(MIN_FONT, fontSize - STEP);
      localStorage.setItem(FONT_KEY, String(fontSize));
      applyPreferences();
      updateToolbar(toolbar);
    });
    const reset = button('A', 'Restaurar fonte', () => {
      fontSize = 100;
      localStorage.setItem(FONT_KEY, '100');
      applyPreferences();
      updateToolbar(toolbar);
    });
    const increase = button('A+', 'Aumentar fonte', () => {
      fontSize = Math.min(MAX_FONT, fontSize + STEP);
      localStorage.setItem(FONT_KEY, String(fontSize));
      applyPreferences();
      updateToolbar(toolbar);
    });
    const status = document.createElement('span');
    status.className = 'accessibility-toolbar__status';
    status.setAttribute('aria-live', 'polite');
    const theme = button('☾', 'Alternar modo claro e escuro', () => {
      const dark = !document.documentElement.classList.contains('accessibility-dark');
      localStorage.setItem(THEME_KEY, dark ? 'ativo' : 'inativo');
      applyPreferences();
      updateToolbar(toolbar);
    });
    theme.dataset.action = 'theme';

    toolbar.append(decrease, reset, increase, status, theme);
    document.body.append(toolbar);
    updateToolbar(toolbar);
  }

  function loadVlibras() {
    if (!document.querySelector('[vw]')) {
      const widget = document.createElement('div');
      widget.setAttribute('vw', '');
      widget.className = 'enabled';
      widget.innerHTML = '<div vw-access-button class="active"></div><div vw-plugin-wrapper><div class="vw-plugin-top-wrapper"></div></div>';
      document.body.append(widget);
    }

    const initialize = () => {
      if (window.VLibras && !document.documentElement.dataset.vlibrasInitialized) {
        new window.VLibras.Widget('https://vlibras.gov.br/app');
        document.documentElement.dataset.vlibrasInitialized = 'true';
      }
    };

    const existing = document.querySelector('script[src*="vlibras-plugin.js"]');
    if (existing) {
      // Páginas antigas já inicializam este script no próprio HTML.
      return;
    }

    const script = document.createElement('script');
    script.src = 'https://vlibras.gov.br/app/vlibras-plugin.js';
    script.defer = true;
    script.addEventListener('load', initialize, { once: true });
    document.head.append(script);
  }

  const stylesheet = document.createElement('link');
  stylesheet.rel = 'stylesheet';
  stylesheet.href = 'css/acessibilidade-global.css?v=20260814f';
  document.head.append(stylesheet);

  document.addEventListener('DOMContentLoaded', () => {
    applyPreferences();
    createToolbar();
    loadVlibras();
  }, { once: true });
})();
