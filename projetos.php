<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Conheça os sistemas e aplicações desenvolvidos por Felippe Fardin para gestão, produtividade e controle de rotinas.">
  <meta name="author" content="Felippe Fardin">
  <meta name="robots" content="index, follow">
  <title>Projetos | Felippe Fardin</title>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png?v=20261007">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico?v=20261007">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/public-modern.css?v=20261016">
  <link rel="stylesheet" href="css/acessibilidade-global.css?v=20260814f">
  <link rel="stylesheet" href="css/projetos.css?v=20261016">
  <link rel="stylesheet" href="css/projetos-fixes.css?v=20261016c">
  <script src="js/acessibilidade-global.js?v=20260814f" defer></script>
  <script src="js/public-shell.js?v=20261016" defer></script>
  <script src="js/projetos.js?v=20261016" defer></script>
</head>
<body>
  <?php require __DIR__ . '/includes/public-nav.php'; ?>

  <main id="conteudo-principal">
    <section class="projects-hero" aria-labelledby="projects-title">
      <div class="projects-shell projects-hero__content">
        <div>
          <span class="projects-eyebrow"><i class="fa-solid fa-code-branch" aria-hidden="true"></i> Projetos autorais</span>
          <h1 id="projects-title">Soluções criadas para organizar rotinas e transformar necessidades em sistemas.</h1>
          <p>Explore alguns dos projetos que desenvolvi. Use a busca ou os filtros para encontrar uma solução e acesse o código diretamente no GitHub.</p>
          <a class="projects-primary-button" href="#lista-projetos">Explorar projetos <i class="fa-solid fa-arrow-down" aria-hidden="true"></i></a>
        </div>
        <div class="projects-hero__visual" aria-hidden="true">
          <span><i class="fa-solid fa-wallet"></i></span>
          <span><i class="fa-solid fa-desktop"></i></span>
          <span><i class="fa-solid fa-calendar-check"></i></span>
          <span><i class="fa-solid fa-list-check"></i></span>
          <strong>6<small> projetos</small></strong>
        </div>
      </div>
    </section>

    <section class="projects-showcase" id="lista-projetos" aria-labelledby="showcase-title">
      <div class="projects-shell">
        <div class="projects-heading">
          <span class="projects-section-label">Portfólio</span>
          <h2 id="showcase-title">Projetos em destaque</h2>
          <p>Aplicações voltadas à gestão financeira, controle operacional, pessoas e produtividade.</p>
        </div>

        <div class="projects-toolbar" aria-label="Ferramentas para filtrar projetos">
          <label class="projects-search">
            <span class="sr-only">Buscar projeto</span>
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input id="project-search" type="search" placeholder="Buscar por nome ou finalidade..." autocomplete="off">
          </label>
          <div class="projects-filters" role="group" aria-label="Filtrar por categoria">
            <button class="is-active" type="button" data-filter="todos" aria-pressed="true">Todos</button>
            <button type="button" data-filter="gestao" aria-pressed="false">Gestão</button>
            <button type="button" data-filter="produtividade" aria-pressed="false">Produtividade</button>
            <button type="button" data-filter="pessoas" aria-pressed="false">Pessoas</button>
          </div>
        </div>

        <p class="projects-result" id="projects-result" aria-live="polite">6 projetos encontrados</p>

        <div class="projects-grid">
          <article class="project-card" data-category="gestao" data-search="controle contas financeiro despesas pagamentos">
            <div class="project-card__icon project-card__icon--blue"><i class="fa-solid fa-wallet" aria-hidden="true"></i></div>
            <span class="project-card__category">Gestão financeira</span>
            <h3>Controle de Contas</h3>
            <p>Aplicação para centralizar e acompanhar contas, trazendo mais organização e clareza para o controle financeiro.</p>
            <a href="https://github.com/felippefardin/app_controle_contas_2" target="_blank" rel="noopener noreferrer" aria-label="Ver o projeto Controle de Contas no GitHub">Ver no GitHub <i class="fa-brands fa-github" aria-hidden="true"></i></a>
          </article>

          <article class="project-card" data-category="gestao" data-search="controle maquinas equipamentos inventario patrimonio">
            <div class="project-card__icon project-card__icon--cyan"><i class="fa-solid fa-desktop" aria-hidden="true"></i></div>
            <span class="project-card__category">Controle operacional</span>
            <h3>Controle de Máquinas</h3>
            <p>Sistema pensado para organizar o acompanhamento de máquinas e equipamentos em um único ambiente.</p>
            <a href="https://github.com/felippefardin/controle_maquinas" target="_blank" rel="noopener noreferrer" aria-label="Ver o projeto Controle de Máquinas no GitHub">Ver no GitHub <i class="fa-brands fa-github" aria-hidden="true"></i></a>
          </article>

          <article class="project-card" data-category="produtividade" data-search="controle prazos datas vencimentos acompanhamento">
            <div class="project-card__icon project-card__icon--violet"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div>
            <span class="project-card__category">Produtividade</span>
            <h3>Controle de Prazos</h3>
            <p>Ferramenta para acompanhar datas importantes, organizar compromissos e reduzir o risco de perder prazos.</p>
            <a href="https://github.com/felippefardin/controle_prazos" target="_blank" rel="noopener noreferrer" aria-label="Ver o projeto Controle de Prazos no GitHub">Ver no GitHub <i class="fa-brands fa-github" aria-hidden="true"></i></a>
          </article>

          <article class="project-card" data-category="pessoas" data-search="gestao ferias abono pessoas recursos humanos rh">
            <div class="project-card__icon project-card__icon--orange"><i class="fa-solid fa-umbrella-beach" aria-hidden="true"></i></div>
            <span class="project-card__category">Gestão de pessoas</span>
            <h3>Gestão de Férias e Abono</h3>
            <p>Solução para apoiar a organização de férias e abonos, simplificando o acompanhamento dessas solicitações.</p>
            <a href="https://github.com/felippefardin/gestao_ferias_abono" target="_blank" rel="noopener noreferrer" aria-label="Ver o projeto Gestão de Férias e Abono no GitHub">Ver no GitHub <i class="fa-brands fa-github" aria-hidden="true"></i></a>
          </article>

          <article class="project-card" data-category="produtividade" data-search="gerenciador tarefas atividades produtividade organizacao">
            <div class="project-card__icon project-card__icon--green"><i class="fa-solid fa-list-check" aria-hidden="true"></i></div>
            <span class="project-card__category">Produtividade</span>
            <h3>Gerenciador de Tarefas</h3>
            <p>Aplicação para registrar, organizar e acompanhar tarefas, facilitando a visualização das atividades do dia a dia.</p>
            <a href="https://github.com/felippefardin/gerenciador_tarefas" target="_blank" rel="noopener noreferrer" aria-label="Ver o projeto Gerenciador de Tarefas no GitHub">Ver no GitHub <i class="fa-brands fa-github" aria-hidden="true"></i></a>
          </article>

          <article class="project-card" data-category="pessoas" data-search="programa ponto registro jornada horas colaboradores">
            <div class="project-card__icon project-card__icon--pink"><i class="fa-solid fa-clock" aria-hidden="true"></i></div>
            <span class="project-card__category">Gestão de pessoas</span>
            <h3>Programa de Ponto</h3>
            <p>Sistema direcionado ao registro e controle de ponto, apoiando o acompanhamento da jornada de trabalho.</p>
            <a href="https://github.com/felippefardin/pgm_ponto" target="_blank" rel="noopener noreferrer" aria-label="Ver o projeto Programa de Ponto no GitHub">Ver no GitHub <i class="fa-brands fa-github" aria-hidden="true"></i></a>
          </article>
        </div>

        <div class="projects-empty" id="projects-empty" hidden>
          <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
          <h3>Nenhum projeto encontrado</h3>
          <p>Tente outro termo ou selecione uma categoria diferente.</p>
        </div>
      </div>
    </section>

    <section class="projects-cta" aria-labelledby="projects-cta-title">
      <div class="projects-shell projects-cta__content">
        <div><span class="projects-section-label">Tem uma ideia?</span><h2 id="projects-cta-title">Vamos transformar sua necessidade em uma solução.</h2><p>Conte o que você precisa e receba uma orientação inicial para o seu projeto.</p></div>
        <a href="index.php#formulario">Falar sobre meu projeto <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
    </section>
  </main>

  <a href="https://wa.me/5527999642716" class="whatsapp-float-btn" target="_blank" rel="noopener noreferrer" aria-label="Fale com a Tech Tecnologia pelo WhatsApp"><img src="img/whatsapp-icon.png" alt=""></a>
</body>
</html>
