<?php
declare(strict_types=1);
require_once __DIR__ . '/src/reviews.php';
require_auth();
$error = ''; $rows = []; $total = 0;
$page = max(1, (int)($_GET['pagina'] ?? 1));
$filter = $_GET['status'] ?? '';
if (!is_string($filter) || !in_array($filter,['','pendente','aprovada','desativada'],true)) $filter='';
try {
    review_schema();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $id = filter_var($_POST['id'] ?? null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        $action = $_POST['acao'] ?? '';
        if (!$id) throw new InvalidArgumentException('Avaliação inválida.');
        if ($action === 'excluir') {
            $stmt=db()->prepare('DELETE FROM site_avaliacoes WHERE id=?');$stmt->bind_param('i',$id);
        } elseif ($action === 'aceitar' || $action === 'desativar') {
            $status=$action === 'aceitar'?'aprovada':'desativada';
            $stmt=db()->prepare('UPDATE site_avaliacoes SET status=? WHERE id=?');$stmt->bind_param('si',$status,$id);
        } elseif ($action === 'editar') {
            [$name,$comment,$stars]=review_validate($_POST);
            $stmt=db()->prepare('UPDATE site_avaliacoes SET nome=?,comentario=?,estrelas=? WHERE id=?');$stmt->bind_param('ssii',$name,$comment,$stars,$id);
        } else throw new InvalidArgumentException('Ação inválida.');
        $stmt->execute();
        $_SESSION['review_flash']='Avaliação atualizada com sucesso.';
        header('Location: avaliacoes.php');exit;
    }
    $where = $filter !== '' ? ' WHERE status=?' : '';
    $count=db()->prepare('SELECT COUNT(*) AS total FROM site_avaliacoes'.$where);
    if ($filter !== '') $count->bind_param('s',$filter);
    $count->execute();$total=(int)$count->get_result()->fetch_assoc()['total'];
    $page=min($page,max(1,(int)ceil($total/20)));$offset=($page-1)*20;
    $stmt=db()->prepare('SELECT * FROM site_avaliacoes'.$where.' ORDER BY criado_em DESC,id DESC LIMIT 20 OFFSET ?');
    if ($filter !== '') $stmt->bind_param('si',$filter,$offset); else $stmt->bind_param('i',$offset);
    $stmt->execute();$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (InvalidArgumentException $e) { $error=$e->getMessage(); }
catch (Throwable $e) { error_log('Review moderation: '.$e->getMessage());$error='Não foi possível carregar ou salvar. Verifique a conexão e execute migrations/20261109_avaliacoes.sql no banco, caso necessário.'; }
$flash=$_SESSION['review_flash']??'';unset($_SESSION['review_flash']);
?>
<!DOCTYPE html><html lang="pt-BR"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Avaliação | Painel</title><meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="css/avaliacoes.css?v=20261110-padrao">
<script src="js/acessibilidade-global.js?v=20261110-reviews-layout" data-accessibility-fixed defer></script>
<script src="js/avaliacoes.js?v=20261109" defer></script>
</head><body class="review-admin"><header class="review-admin-header"><div class="review-admin-heading"><a class="review-admin-brand" href="home.php"><img src="img/logotipofelippe.png" alt="Felippe Andreata" width="240" height="80"></a><div><h1>Avaliações</h1><p>Gerencie os comentários enviados pelo site.</p></div></div><a class="review-back" href="home.php">&larr; Voltar ao painel</a></header>
<main id="conteudo-principal" class="review-admin-main">
<?php if ($error): ?><p class="error" role="alert"><?= review_escape($error) ?></p><?php endif; ?>
<?php if ($flash): ?><p class="success" role="status"><?= review_escape($flash) ?></p><?php endif; ?>
<form method="get" class="review-filter"><label for="review-filter">Situação</label><select name="status" id="review-filter"><?php foreach ([''=>'Todas','pendente'=>'Pendentes','aprovada'=>'Aprovadas','desativada'=>'Desativadas'] as $key=>$label): ?><option value="<?= $key ?>" <?= $filter===$key?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select><button>Filtrar</button></form>
<p class="review-count"><?= $total ?> avaliação(ões). Só as aprovadas ficam visíveis na página inicial.</p>
<?php foreach ($rows as $row): ?>
<article class="review-card">
 <h2><?= review_escape($row['nome']) ?></h2><p><strong><?= (int)$row['estrelas'] ?>/5 estrelas</strong> · <?= review_escape($row['status']) ?> · <?= review_escape($row['criado_em']) ?></p>
 <p class="review-comment"><?= nl2br(review_escape($row['comentario'])) ?></p>
 <form method="post" class="review-actions"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
  <button name="acao" value="aceitar">Aceitar / ativar</button><button name="acao" value="desativar">Desativar</button><button name="acao" value="excluir" data-review-delete>Excluir</button>
 </form>
 <details><summary>Editar avaliação</summary><form method="post" class="review-edit"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><input type="hidden" name="acao" value="editar">
  <label>Nome<input name="nome" minlength="2" maxlength="100" required value="<?= review_escape($row['nome']) ?>"></label>
  <label>Estrelas<select name="estrelas"><?php for($s=1;$s<=5;$s++): ?><option value="<?= $s ?>" <?= $s===(int)$row['estrelas']?'selected':'' ?>><?= $s ?></option><?php endfor; ?></select></label>
  <label>Comentário<textarea name="comentario" minlength="10" maxlength="2000" required rows="4"><?= review_escape($row['comentario']) ?></textarea></label><button>Salvar alterações</button>
 </form></details>
</article>
<?php endforeach; ?>
<?php if (!$rows && !$error): ?><p>Nenhuma avaliação nesta situação.</p><?php endif; ?>
<nav class="review-pagination" aria-label="Paginação"><?php if ($page>1): ?><a href="?pagina=<?= $page-1 ?>&amp;status=<?= review_escape($filter) ?>">← Anterior</a><?php endif; ?> <span>Página <?= $page ?> de <?= max(1,(int)ceil($total/20)) ?></span> <?php if($page*20<$total): ?><a href="?pagina=<?= $page+1 ?>&amp;status=<?= review_escape($filter) ?>">Próxima →</a><?php endif; ?></nav>
</main></body></html>
