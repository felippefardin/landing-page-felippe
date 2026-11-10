<?php
declare(strict_types=1);
require_once __DIR__.'/src/memoria-desconto.php';
require_once __DIR__.'/src/cupom-utilizacao.php';
require_auth();
header('Cache-Control: no-store');
function ch(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function cd(?string $date): string { return $date ? (new DateTimeImmutable($date, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('d/m/Y H:i') : '—'; }
$authorized=false; $error=''; $row=null; $job=null;
$code=is_string($_GET['cupom']??null)?strtoupper(trim($_GET['cupom'])):'';
$notice=$_SESSION['coupon_usage_notice']??''; unset($_SESSION['coupon_usage_notice']);
try {
    $db=db(); $authorized=memory_admin();
    if (!$authorized) { http_response_code(403); }
    else {
        coupon_usage_schema($db);
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf();
            $code=is_string($_POST['cupom']??null)?strtoupper(trim($_POST['cupom'])):'';
        }
        if ($code!=='') {
            if (!preg_match('/^(?:FAS|FA15|FA50)-[A-F0-9]{24}$/D', $code)) throw new MemoryProblem('Informe um código de cupom válido.');
            $row=memory_sql($db,'SELECT * FROM promo_memory_players WHERE campaign=? AND coupon=?','ss',[MEMORY_CAMPAIGN,$code])->get_result()->fetch_assoc();
            if (!$row) throw new MemoryProblem('Cupom não encontrado. Confira o código.');
            if ($_SERVER['REQUEST_METHOD']==='POST') {
                $action=$_POST['acao']??'';
                if ($action==='utilizar') {
                    if (($_POST['confirmacao']??'')!=='1') throw new MemoryProblem('Confirme que conferiu os dados da contratação.');
                    $result=coupon_redeem($db,(int)$row['id'],$code);
                    $notice=$result['sent']?'Cupom utilizado e invalidado para novas utilizações. Confirmação enviada ao e-mail do cliente.':'Cupom utilizado e invalidado. O e-mail de confirmação está pendente; tente reenviar abaixo.';
                } elseif ($action==='reenviar') {
                    $sent=coupon_usage_send($db,(int)$row['id']);
                    $notice=$sent?'Confirmação de utilização enviada ao cliente.':'O envio falhou. O cupom permanece utilizado e inválido para novas utilizações.';
                } else throw new MemoryProblem('Ação inválida.');
                $_SESSION['coupon_usage_notice']=$notice;
                header('Location: validar-cupom.php?cupom='.rawurlencode($code)); exit;
            }
            $job=memory_sql($db,'SELECT * FROM promo_memory_usage_mail WHERE player_id=?','i',[(int)$row['id']])->get_result()->fetch_assoc();
        } elseif ($_SERVER['REQUEST_METHOD']==='POST') throw new MemoryProblem('Informe o cupom.');
    }
} catch (MemoryProblem $e) { $error=$e->publicMessage; }
catch (Throwable $e) { error_log('Coupon validation: '.get_class($e)); $error='Não foi possível concluir. Confira o registro do cupom antes de tentar novamente. Se a confirmação já estava pendente, o cupom continua bloqueado.'; }
$status=$row?memory_coupon_state($row):'';
$labels=['reserved'=>'Reservado em uma compra pendente','active'=>'Disponível para uso','used'=>'Utilizado — não pode ser reutilizado','expired'=>'Expirado','cancelled'=>'Desativado'];
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Validar cupom | Tech Tecnologia</title>
<link rel="stylesheet" href="css/validar-cupom.css?v=20261110"><script src="js/acessibilidade-global.js?v=20261110-reviews-layout" data-accessibility-fixed defer></script></head>
<body class="coupon-validation"><main id="conteudo-principal">
<header class="coupon-heading"><div><h1>Validar cupom</h1><p>Consulte o vale e confirme sua utilização na contratação.</p></div><a href="home.php">&larr; Voltar ao painel</a></header>
<?php if($notice):?><p class="coupon-notice" role="status"><?=ch($notice)?></p><?php endif;?>
<?php if($error):?><p class="coupon-error" role="alert"><?=ch($error)?></p><?php endif;?>
<?php if(!$authorized):?><section class="coupon-box"><h2>Confirme o e-mail de administrador</h2><p>Além do login, confirme seu e-mail de administrador pela janela do jogo. Não é necessário jogar outra partida.</p><a href="index.php#desafio-memoria">Confirmar e-mail no jogo</a></section>
<?php else:?>
<form method="get" class="coupon-box coupon-search"><label for="coupon-code">Código do cupom<input id="coupon-code" name="cupom" value="<?=ch($code)?>" maxlength="40" required autocomplete="off" placeholder="FAS-…"></label><button>Consultar cupom</button></form>
<?php if($row):?><section class="coupon-box"><h2>Dados do cupom</h2><dl><dt>Código</dt><dd><code><?=ch($row['coupon'])?></code></dd><dt>E-mail do cliente</dt><dd><?=ch($row['email'])?></dd><dt>Situação</dt><dd><strong><?=ch($labels[$status]??$status)?></strong></dd><dt>Validade</dt><dd><?=ch(cd($row['expires_at']))?> (Brasília)</dd>
<?php if($row['redeemed_at']):?><dt>Utilizado em</dt><dd><?=ch(cd($row['redeemed_at']))?> (Brasília)</dd><?php endif;?></dl>
<?php if($status==='active'):?><p>Confira o cliente e a contratação antes de confirmar. Esta ação utiliza o cupom definitivamente e envia a confirmação ao e-mail acima.</p><form method="post"><?=csrf_field()?><input type="hidden" name="cupom" value="<?=ch($code)?>"><label class="coupon-check"><input type="checkbox" required name="confirmacao" value="1"> Conferi os dados e confirmo a utilização deste cupom nesta contratação.</label><button name="acao" value="utilizar">Confirmar uso e invalidar cupom</button></form>
<?php elseif($status==='used'):?><p>Este cupom não pode mais ser utilizado.</p><?php if($job && !$job['sent_at']):?><p>A confirmação por e-mail está pendente.</p><form method="post"><?=csrf_field()?><input type="hidden" name="cupom" value="<?=ch($code)?>"><button name="acao" value="reenviar" <?=(int)$job['attempts']>=5?'disabled':''?>>Reenviar confirmação de utilização</button></form><?php elseif($job):?><p>Confirmação enviada em <?=ch(cd($job['sent_at']))?> (Brasília).</p><?php endif;?><?php endif;?>
</section><?php endif;?>
<p><a href="cupons-desconto.php">Ver todos os cupons</a></p><?php endif;?>
</main></body></html>
