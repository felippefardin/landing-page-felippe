<?php
declare(strict_types=1);
require_once __DIR__.'/src/builder-commerce.php';
require_once __DIR__.'/src/builder-admin.php';require_auth();
$error='';$rows=[];$authorized=false;$search=is_string($_GET['q']??null)?trim(substr($_GET['q'],0,254)):'';$page=max(1,(int)($_GET['page']??1));$page=min(100000,$page);$hasNext=false;
try{
    $authorized=bc_admin();
    if(!$authorized)http_response_code(403);
    else{
        $db=bc_admin_db();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            verify_csrf();$id=is_string($_POST['id']??null)?$_POST['id']:'';$o=bc_order($db,$id);
            if(($_POST['action']??'')==='support'){
                if((int)$o['support_cents']===0 || $o['status']!=='paid')throw new InvalidArgumentException('Suporte não contratado ou pagamento não aprovado.');
                $next=is_string($_POST['status']??null)?$_POST['status']:'';if(!in_array($next,['requested','scheduled','completed'],true))throw new InvalidArgumentException('Situação inválida.');
                memory_sql($db,'UPDATE builder_orders SET support_status=? WHERE id=?','ss',[$next,$id]);
            }elseif(($_POST['action']??'')==='retry'){
                $o=bc_order($db,$id);if($o['status']!=='paid'||memory_time($o['download_until'])<=time())throw new InvalidArgumentException('Entrega indisponível.');
                // Retry pending email only; never issue another coupon use or another charge.
                bc_deliver($db,$id);
            }elseif(($_POST['action']??'')==='refresh'){bc_refresh($db,$o);}
            else throw new InvalidArgumentException('Ação inválida.');
            header('Location: documentos-assinados.php?q='.rawurlencode($id));exit;
        }
        $where='signed_at IS NOT NULL';$values=[];$types='';if($search!==''){$where.=' AND (id LIKE ? OR email LIKE ? OR customer_name LIKE ?)';$types='sss';$values=array_fill(0,3,'%'.$search.'%');}
        $rows=memory_sql($db,'SELECT * FROM builder_orders WHERE '.$where.' ORDER BY created_at DESC LIMIT 26 OFFSET '.(($page-1)*25),$types,$values)->get_result()->fetch_all(MYSQLI_ASSOC);$hasNext=count($rows)>25;$rows=array_slice($rows,0,25);
    }
}catch(InvalidArgumentException $e){$error=$e->getMessage();}catch(Throwable $e){error_log('Signed document archive failed: '.get_class($e));$error='Não foi possível carregar os documentos. Confira o banco de dados e a importação de migrations/20261110_site_builder.sql. Isso não exige uma nova confirmação de login.';}
function da_date(?string $date): string{return $date?(new DateTimeImmutable($date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('d/m/Y H:i'):'—';}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Pedidos e documentos | Painel</title><link rel="stylesheet" href="css/cupons-desconto.css?v=20261110"><script src="js/acessibilidade-global.js?v=20261110-reviews-layout" data-accessibility-fixed defer></script></head><body class="coupons-admin"><main id="conteudo-principal"><nav><a href="home.php">&larr; Voltar ao painel</a><a href="reembolsos.php">Reembolsos</a><a href="validar-cupom.php">Validar cupom</a></nav><h1>Contratos assinados</h1><p>Termos aceitos por código de e-mail, pagamentos, entregas e suporte opcional. Os PDFs permanecem disponíveis após o fim do download do site.</p>
<?php if($error):?><p role="alert" class="notice"><?=sb_escape($error)?></p><?php endif;?>
<?php if(!$authorized && !$error):?><section class="notice" role="alert"><h2>Acesso restrito ao administrador do site</h2><p>Esta área está disponível à conta administrativa configurada no site.</p></section><?php elseif($authorized && !$error):?>
<form method="get" class="coupon-filters"><label>Nome, e-mail ou pedido<input name="q" maxlength="254" value="<?=sb_escape($search)?>"></label><button>Buscar</button></form>
<div class="coupon-table"><table><thead><tr><th>Cliente / pedido</th><th>Valores</th><th>Termo</th><th>Pagamento / entrega</th><th>Suporte opcional</th><th>Ações</th></tr></thead><tbody>
<?php foreach($rows as $o):?><tr><td><strong><?=sb_escape($o['customer_name'])?></strong><br><?=sb_escape($o['email'])?><br><code><?=sb_escape($o['id'])?></code></td><td>Página: <?=sb_money((int)$o['base_cents'])?><br>Desconto: <?=sb_money((int)$o['discount_cents'])?><br>Suporte: <?=sb_money((int)$o['support_cents'])?><br><strong>Total: <?=sb_money((int)$o['total_cents'])?></strong></td><td>Aceito em <?=sb_escape(da_date($o['signed_at']))?><br>Assinatura eletrônica simples<br><a href="documento-assinado-pdf.php?id=<?=sb_escape($o['id'])?>&amp;visualizar=1" target="_blank" rel="noopener noreferrer">Visualizar PDF</a><br><a href="documento-assinado-pdf.php?id=<?=sb_escape($o['id'])?>">Baixar termo em PDF</a></td><td><?=sb_escape(['signed'=>'Aguardando checkout','pending'=>'Aguardando pagamento','paid'=>'Pagamento aprovado','revoked'=>'Pagamento revogado','needs_review'=>'Requer conferência'][$o['status']]??$o['status'])?><br><?=sb_escape($o['payment_status']??'')?><br>E-mail: <?=$o['delivery_sent_at']?'Enviado':'Não enviado'?><br>Download até: <?=sb_escape(da_date($o['download_until']))?><br>Downloads: <?=(int)$o['downloads']?></td><td><?php if((int)$o['support_cents']>0):?><form method="post"><?=csrf_field()?><input type="hidden" name="id" value="<?=sb_escape($o['id'])?>"><input type="hidden" name="action" value="support"><label>Situação<select name="status"><?php foreach(['requested'=>'Solicitado','scheduled'=>'Agendado','completed'=>'Concluído'] as $value=>$label):?><option value="<?=$value?>" <?=$o['support_status']===$value?'selected':''?>><?=$label?></option><?php endforeach;?></select></label><button>Atualizar suporte</button></form><?php else:?>Não contratado<?php endif;?></td><td><form method="post" class="coupon-actions"><?=csrf_field()?><input type="hidden" name="id" value="<?=sb_escape($o['id'])?>"><button name="action" value="refresh">Conferir pagamento</button><?php if($o['status']==='paid'&&!$o['delivery_sent_at']&&memory_time($o['download_until'])>time()):?><button name="action" value="retry">Tentar entrega pendente</button><?php endif;?></form></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="6">Nenhum termo assinado encontrado.</td></tr><?php endif;?></tbody></table></div><nav class="coupon-pages"><?php if($page>1):?><a href="?<?=sb_escape(http_build_query(['q'=>$search,'page'=>$page-1]))?>">Anterior</a><?php endif;?><span>Página <?=$page?></span><?php if($hasNext):?><a href="?<?=sb_escape(http_build_query(['q'=>$search,'page'=>$page+1]))?>">Próxima</a><?php endif;?></nav><?php endif;?></main></body></html>
