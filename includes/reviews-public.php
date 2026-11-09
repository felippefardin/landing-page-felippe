<?php
$publicReviews = [];
try {
    $publicReviews = db()->query("SELECT nome,comentario,estrelas FROM site_avaliacoes WHERE status='aprovada' ORDER BY criado_em DESC,id DESC LIMIT 12")->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $e) { error_log('Public reviews: '.$e->getMessage()); }
?>
<section class="work-section work-section--soft" id="depoimentos" aria-labelledby="feedback-title">
 <div class="work-wrap">
  <span class="work-eyebrow">Experiências reais</span><h2 id="feedback-title">Avaliações</h2>
  <p class="work-intro">Conheça a opinião de quem compartilhou sua experiência.</p>
  <div class="review-grid">
  <?php foreach ($publicReviews as $review): ?>
   <article class="review-card">
    <div class="review-stars" aria-label="<?= (int)$review['estrelas'] ?> de 5 estrelas"><?= str_repeat('★',(int)$review['estrelas']) ?><span aria-hidden="true"><?= str_repeat('☆',5-(int)$review['estrelas']) ?></span></div>
    <h3><?= review_escape($review['nome']) ?></h3><p class="review-comment"><?= nl2br(review_escape($review['comentario'])) ?></p>
   </article>
  <?php endforeach; ?>
  </div>
  <?php if (!$publicReviews): ?><p>Ainda não há avaliações publicadas. Que tal compartilhar sua experiência?</p><?php endif; ?>
  <button type="button" class="review-button" data-review-open>Deixar minha avaliação</button>
 </div>
</section>
<dialog id="review-dialog" aria-labelledby="review-title">
 <div class="review-dialog-inner">
  <button type="button" class="review-close" data-review-close aria-label="Fechar avaliação">×</button>
  <h2 id="review-title">Como foi sua experiência?</h2>
  <p>Deixe sua avaliação. Seu comentário será publicado somente após aprovação.</p>
  <form id="review-form" action="enviar-avaliacao.php" method="post">
   <?= csrf_field() ?>
   <label for="review-name">Nome</label><input id="review-name" name="nome" autocomplete="name" minlength="2" maxlength="100" required>
   <fieldset class="review-rating"><legend>Sua nota</legend>
   <?php for ($rating=1;$rating<=5;$rating++): ?>
    <label class="review-star-choice"><input type="radio" name="estrelas" value="<?= $rating ?>" required aria-label="<?= $rating ?> <?= $rating===1?'estrela':'estrelas' ?>"><span aria-hidden="true">★</span><small><?= $rating ?></small></label>
   <?php endfor; ?>
   </fieldset>
   <label for="review-comment">Comentário</label><textarea id="review-comment" name="comentario" rows="4" minlength="10" maxlength="2000" required placeholder="Conte sua experiência (mínimo de 10 caracteres)"></textarea>
   <div class="review-honey" aria-hidden="true"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
   <label class="review-consent"><input type="checkbox" name="consentimento" value="1" required> Autorizo a publicação do meu nome, nota e comentário neste site após aprovação.</label>
   <p id="review-result" role="status" aria-live="polite"></p>
   <button type="submit" class="review-button">Enviar avaliação</button>
   <button type="button" class="review-secondary" data-review-close>Agora não</button>
  </form>
 </div>
</dialog>
