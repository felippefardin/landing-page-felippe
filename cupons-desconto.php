<?php
declare(strict_types=1);
require_once __DIR__.'/src/memoria-desconto.php';
require_auth();
function mh(mixed $v): string {return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function md(?string $v): string {return $v?(new DateTimeImmutable($v,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('d/m/Y H:i'):'—';}
$authorized=false;$ready=true;$notice='';$rows=[];$counts=[];
try {$db=db();$authorized=memory_admin();}catch(Throwable $e){$ready=false;}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!$authorized) {http_response_code(403);exit('Acesso não autorizado.');}
    verify_csrf();$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);$action=$_POST['action']??'';
    try {
        if (!$id || !in_array($action,['use','cancel','resend'],true)) throw new MemoryProblem('Ação inválida.');
        if ($action==='resend') {$sent=memory_send_coupon($db,$id);$notice=$sent?'Voucher reenviado.':'O envio falhou. O cupom continua salvo. Confira o SMTP e tente novamente em 1 minuto.';}
        else {
            $db->begin_transaction();$row=memory_player($db,$id,true);
            if (memory_coupon_state($row)!=='active') throw new MemoryProblem('Somente cupons ativos e dentro da validade podem ser utilizados ou desativados.');
            if ($action==='use') memory_sql($db,"UPDATE promo_memory_players SET coupon_status='used',redeemed_at=?,redeemed_by=? WHERE id=?",'ssi',[memory_now(),memory_env('DISCOUNT_ADMIN_EMAIL','felippefardin@gmail.com'),$id]);
            else memory_sql($db,"UPDATE promo_memory_players SET coupon_status='cancelled' WHERE id=?",'i',[$id]);
            $db->commit();$notice=$action==='use'?'Cupom marcado como utilizado.':'Cupom desativado.';
        }
    }catch(MemoryProblem $e){$db->rollback();$notice=$e->publicMessage;}
    catch(Throwable $e){$db->rollback();$notice='Não foi possível concluir a operação.';}
    $_SESSION['memory_admin_notice']=$notice;header('Location: cupons-desconto.php');exit;
}
$notice=$_SESSION['memory_admin_notice']??'';unset($_SESSION['memory_admin_notice']);
$filter=is_string($_GET['status']??null)?$_GET['status']:'all';
$labels=['all'=>'Todos','pending'=>'Participantes sem prêmio','active'=>'Ativos','used'=>'Utilizados','expired'=>'Vencidos','cancelled'=>'Desativados'];
if(!isset($labels[$filter]))$filter='all';
$page=max(1,min(100000,(int)($_GET['page']??1)));$offset=($page-1)*50;$hasNext=false;
$search=is_string($_GET['q']??null)?substr(trim($_GET['q']),0,254):'';
if($authorized){try{
    $now=memory_now();
    $counts=memory_sql($db,"SELECT COUNT(*) total,SUM(coupon_status='active' AND expires_at>?) active,SUM(coupon_status='used') used,SUM(coupon_status='active' AND expires_at<=?) expired FROM promo_memory_players WHERE campaign=?",'sss',[$now,$now,MEMORY_CAMPAIGN])->get_result()->fetch_assoc();
    $where='campaign=?';$types='s';$values=[MEMORY_CAMPAIGN];
    if($filter==='pending')$where.=' AND coupon IS NULL';
    elseif($filter==='expired'){$where.=" AND coupon_status='active' AND expires_at<=?";$types.='s';$values[]=$now;}
    elseif($filter==='active'){$where.=" AND coupon_status='active' AND expires_at>?";$types.='s';$values[]=$now;}
    elseif(in_array($filter,['used','cancelled'],true)){$where.=' AND coupon_status=?';$types.='s';$values[]=$filter;}
    if($search!==''){$where.=' AND (email LIKE ? OR coupon LIKE ?)';$types.='ss';$values[]='%'.$search.'%';$values[]='%'.$search.'%';}
    $rows=memory_sql($db,'SELECT * FROM promo_memory_players WHERE '.$where.' ORDER BY id DESC LIMIT 51 OFFSET '.$offset,$types,$values)->get_result()->fetch_all(MYSQLI_ASSOC);
    $hasNext=count($rows)>50;$rows=array_slice($rows,0,50);
}catch(Throwable $e){$ready=false;}}
if ($ready && !$authorized) http_response_code(403);
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Cupons de desconto | Tech Tecnologia</title><link rel="stylesheet" href="css/cupons-desconto.css?v=20261109"><script src="js/acessibilidade-global.js?v=20261109" defer></script></head><body class="coupons-admin">
<main><nav><a href="admin.php">← Voltar ao painel</a><a href="index.php#desafio-memoria">Abrir jogo no site</a></nav><h1>Cupons de desconto</h1><p>Jogo da memória · 25 pares · 80 jogadas · 3 partidas · 50% por 7 dias</p>
<?php if(!$ready):?><p role="alert" class="notice">O módulo ainda não está disponível. Importe a migração do jogo no banco e confira a configuração.</p>
<?php elseif(!$authorized):?><section class="notice"><h2>Confirme seu e-mail de administrador</h2><p>Além do login do site, confirme o e-mail do proprietário pelo código enviado na janela do jogo. Não é necessário iniciar uma partida.</p><p><a href="index.php#desafio-memoria">Confirmar meu e-mail na index</a></p><p>Administrador configurado: <?=mh(memory_env('DISCOUNT_ADMIN_EMAIL','felippefardin@gmail.com'))?>.</p></section>
<?php else:?>
<?php if($notice):?><p class="notice" role="status"><?=mh($notice)?></p><?php endif;?>
<div class="coupon-metrics"><?php foreach(['total'=>'Participantes','active'=>'Cupons ativos','used'=>'Utilizados','expired'=>'Vencidos'] as $key=>$label):?><div><strong><?=mh($counts[$key]??0)?></strong><span><?=mh($label)?></span></div><?php endforeach;?></div>
<form method="get" class="coupon-filters"><label>Buscar por e-mail ou cupom<input name="q" value="<?=mh($search)?>" maxlength="254"></label><label>Situação<select name="status"><?php foreach($labels as $key=>$label):?><option value="<?=mh($key)?>" <?=$filter===$key?'selected':''?>><?=mh($label)?></option><?php endforeach;?></select></label><button>Filtrar</button></form>
<p>Validade e utilização em horário de Brasília. Marque como utilizado somente após confirmar o cupom, o e-mail do cliente e a contratação.</p>
<div class="coupon-table"><table><thead><tr><th>E-mail</th><th>Partidas</th><th>Cupom</th><th>Situação</th><th>Validade</th><th>Envio / utilização</th><th>Ações</th></tr></thead><tbody>
<?php foreach($rows as $row):$status=memory_coupon_state($row);?><tr><td><?=mh($row['email'])?></td><td><?=(int)$row['games_used']?> / 3</td><td><code><?=mh($row['coupon']??'—')?></code></td><td><?=mh(['none'=>'Sem prêmio','active'=>'Ativo','expired'=>'Vencido','used'=>'Utilizado','cancelled'=>'Desativado'][$status]??$status)?></td><td><?=mh(md($row['expires_at']))?></td><td><?=$row['coupon']?($row['mail_sent']?'Enviado':'Pendente'):'—'?><?php if($row['redeemed_at']):?><br>Usado: <?=mh(md($row['redeemed_at']))?><?php endif;?></td><td><?php if($status==='active'):?><form method="post" class="coupon-actions"><?=csrf_field()?><input type="hidden" name="id" value="<?=(int)$row['id']?>"><button name="action" value="use" onclick="return confirm('Confirmar a utilização deste cupom na contratação?')">Marcar utilizado</button><button name="action" value="resend" <?=(int)$row['mail_attempts']>=5?'disabled':''?>>Reenviar e-mail</button><button name="action" value="cancel" onclick="return confirm('Desativar este cupom?')">Desativar</button></form><?php else:?>—<?php endif;?></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="7">Nenhum registro encontrado.</td></tr><?php endif;?></tbody></table></div>
<nav class="coupon-pages"><?php if($page>1):?><a href="?<?=mh(http_build_query(['q'=>$search,'status'=>$filter,'page'=>$page-1]))?>">← Anterior</a><?php endif;?><span>Página <?=$page?></span><?php if($hasNext):?><a href="?<?=mh(http_build_query(['q'=>$search,'status'=>$filter,'page'=>$page+1]))?>">Próxima →</a><?php endif;?></nav>
<?php endif;?></main></body></html>
