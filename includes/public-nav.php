<link rel="stylesheet" href="css/public-header-standard.css?v=20261110-horizontal">
<nav class="site-nav public-nav" aria-label="Navegação principal">
  <a class="site-nav__brand public-nav__brand" href="index.php#inicio"><span>Felippe Andreata |</span><span>Desenvolvimento Web</span></a>
  <div class="site-nav__links public-nav__links">
    <a href="index.php#inicio">Início</a>
    <a href="index.php#servicos">Serviços</a>
    <a href="projetos.php">Projetos</a>
    <a href="sobre.php">Sobre</a>
    <a href="index.php#formulario">Contato</a>
    <?php if(!empty($showBuilderLink)):?><a class="nav-builder-anchor" href="#criador-de-paginas" aria-label="Ver a caixa do criador de sites" title="Conheça o criador de sites"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2.5" y="3.5" width="19" height="17" rx="2"></rect><path d="M3 8h18M9 12l-3 2.5L9 17m6-5 3 2.5-3 2.5m-2-6-2 7"></path></svg><span>Criar site</span></a><?php endif;?>
  </div>
</nav>
