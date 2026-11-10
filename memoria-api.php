<?php
declare(strict_types=1);
require_once __DIR__.'/src/memoria-desconto.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');
function memory_reply(array $body,int $code=200): never {http_response_code($code);echo json_encode($body,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);exit;}
$db=null;
try {
    $db=db();
    if ($_SERVER['REQUEST_METHOD']==='GET') {
        // Probe installation; missing tables must not look like an unverified player.
        memory_sql($db,'SELECT id FROM promo_memory_players LIMIT 0');
        $state=empty($_SESSION['memory_player'])?['verified'=>false,'can_manage'=>memory_admin()]:memory_view(memory_player($db,memory_identity()));
        memory_reply(['csrf_token'=>csrf_token(),'state'=>$state]);
    }
    if ($_SERVER['REQUEST_METHOD']!=='POST') {header('Allow: GET, POST');memory_reply(['error'=>'Método inválido.'],405);}
    if ((int)($_SERVER['CONTENT_LENGTH']??0)>4096) throw new MemoryProblem('Requisição muito grande.',413);
    $input=json_decode(file_get_contents('php://input')?:'{}',true);
    if (!is_array($input)) throw new MemoryProblem('Requisição inválida.');
    $csrf=$input['csrf_token']??null;
    if (!is_string($csrf) || !hash_equals(csrf_token(),$csrf)) throw new MemoryProblem('A confirmação de segurança expirou. Tente novamente.',403,'csrf');
    $action=$input['action']??'';
    if ($action==='request_code') {
        if (($input['consent']??false)!==true) throw new MemoryProblem('Aceite as regras e o uso do e-mail para participar.');
        if (!is_string($input['email']??null)) throw new MemoryProblem('Informe seu e-mail.');
        [$email,$key]=memory_email($input['email']);$now=memory_now();$day=gmdate('Y-m-d');
        $db->begin_transaction();
        // A consistent lock order makes parallel email requests safe.
        memory_rate($db,'all-mails',150);
        memory_rate($db,'ip:'.($_SERVER['REMOTE_ADDR']??'unknown'),5);
        memory_sql($db,'INSERT IGNORE INTO promo_memory_players (campaign,email_key,email,created_at) VALUES (?,?,?,?)','ssss',[MEMORY_CAMPAIGN,$key,$email,$now]);
        $row=memory_sql($db,'SELECT * FROM promo_memory_players WHERE campaign=? AND email_key=? FOR UPDATE','ss',[MEMORY_CAMPAIGN,$key])->get_result()->fetch_assoc();
        $sends=$row['otp_day']===$day?(int)$row['otp_sends']:0;
        if ($sends>=3 || memory_time($row['otp_last'])>time()-60) throw new MemoryProblem('Aguarde 1 minuto entre códigos. Limite de 3 envios por dia.',429);
        $code=(string)random_int(100000,999999);$hash=password_hash($code,PASSWORD_DEFAULT);$expires=gmdate('Y-m-d H:i:s',time()+600);$sends++;
        memory_sql($db,'UPDATE promo_memory_players SET otp_hash=?,otp_expires=?,otp_failures=0,otp_day=?,otp_sends=?,otp_last=? WHERE id=?','sssisi',[$hash,$expires,$day,$sends,$now,(int)$row['id']]);
        $db->commit();$_SESSION['memory_pending']=(int)$row['id'];
        try {memory_mail($row['email'],'Confirme seu e-mail — Jogo da memória',"Seu código: $code\n\nVálido por 10 minutos. Não compartilhe este código.\nUse-o na janela do jogo da memória da Tech Tecnologia.\n\nSe não solicitou, ignore esta mensagem.");}
        catch(MemoryProblem $e) {throw $e;}
        catch(Throwable $e) {error_log('Memory verification email unavailable: '.get_class($e));throw new MemoryProblem('Não foi possível enviar o código. Aguarde 1 minuto e tente novamente.',503);}
        memory_reply(['message'=>'Código enviado. Confira também o spam.']);
    }
    if ($action==='verify_code') {
        $id=(int)($_SESSION['memory_pending']??0);if (!$id) throw new MemoryProblem('Solicite o código primeiro.');
        $code=$input['code']??'';if (!is_string($code)||!preg_match('/^\d{6}$/D',$code)) throw new MemoryProblem('Informe o código de 6 dígitos.');
        $db->begin_transaction();$row=memory_player($db,$id,true);
        if (!$row['otp_hash'] || memory_time($row['otp_expires'])<=time() || (int)$row['otp_failures']>=5) throw new MemoryProblem('Código expirado ou bloqueado. Solicite outro código.');
        if (!password_verify($code,$row['otp_hash'])) {
            memory_sql($db,'UPDATE promo_memory_players SET otp_failures=otp_failures+1 WHERE id=?','i',[$id]);$db->commit();throw new MemoryProblem('Código incorreto. Após 5 erros, solicite outro código.');
        }
        memory_sql($db,'UPDATE promo_memory_players SET verified_at=?,otp_hash=NULL,otp_expires=NULL WHERE id=?','si',[memory_now(),$id]);$db->commit();
        session_regenerate_id(true);$_SESSION['memory_player']=$id;unset($_SESSION['memory_pending']);
        memory_reply(['state'=>memory_view(memory_player($db,$id))]);
    }
    if ($action==='change_email') {unset($_SESSION['memory_player'],$_SESSION['memory_pending']);memory_reply(['state'=>['verified'=>false,'can_manage'=>memory_admin()]]);}
    $id=memory_identity();
    if ($action==='resend_voucher') {
        $sent=memory_send_coupon($db,$id);
        memory_reply(['state'=>memory_view(memory_player($db,$id)),'message'=>$sent?'Voucher enviado ao seu e-mail.':'Seu cupom está salvo, mas o e-mail não foi enviado. Você pode copiar o código ou tentar reenviar em 1 minuto.']);
    }
    if (!in_array($action,['start','flip','settle','abandon'],true)) throw new MemoryProblem('Ação inválida.');
    $db->begin_transaction();$row=memory_player($db,$id,true);$game=json_decode($row['game_state']??'null',true);
    $won=false;
    if ($action==='start') {
        if ($row['coupon']) throw new MemoryProblem('Você já ganhou um voucher nesta campanha.');
        if ($game && $game['status']==='active') throw new MemoryProblem('Sua partida está em andamento. Continue ou encerre a partida.',409);
        if ((int)$row['games_used']>=MEMORY_GAMES) throw new MemoryProblem('Você já utilizou suas 3 partidas.');
        $game=memory_new_game();memory_sql($db,'UPDATE promo_memory_players SET games_used=games_used+1 WHERE id=?','i',[$id]);
    } else {
        if (!$game || $game['status']!=='active') throw new MemoryProblem('Não há partida em andamento.',409);
        if (!is_string($input['revision']??null)||!hash_equals($game['revision'],$input['revision'])) throw new MemoryProblem('A partida foi atualizada em outra aba. Atualize o jogo.',409);
        if ($action==='flip') {
            if (!is_int($input['position']??null)) throw new MemoryProblem('Carta inválida.');
            memory_apply_flip($game,$input['position']);
            if ($game['status']==='won') {
                $won=true;$coupon='FAS-'.strtoupper(bin2hex(random_bytes(12)));$expires=gmdate('Y-m-d H:i:s',time()+7*86400);
                memory_sql($db,"UPDATE promo_memory_players SET coupon=?,coupon_status='active',won_at=?,expires_at=? WHERE id=? AND coupon IS NULL",'sssi',[$coupon,memory_now(),$expires,$id]);
            }
        } elseif ($action==='settle') {
            if (count($game['open'])!==2 || microtime(true)<$game['settle_at']) throw new MemoryProblem('Aguarde as cartas fecharem.',409);
            $game['open']=[];$game['settle_at']=0;$game['revision']=bin2hex(random_bytes(16));
        } else {$game['status']='lost';$game['revision']=bin2hex(random_bytes(16));}
    }
    memory_game_save($db,$id,$game);$db->commit();
    $message='';if ($won) {$sent=memory_send_coupon($db,$id);$message=$sent?'Você ganhou! O voucher foi enviado ao seu e-mail.':'Você ganhou! Seu cupom está salvo, mas o envio por e-mail falhou. Copie o código ou tente reenviar em 1 minuto.';}
    memory_reply(['state'=>memory_view(memory_player($db,$id)),'message'=>$message]);
} catch(MemoryProblem $e) {
    if ($db instanceof mysqli) {try {$db->rollback();}catch(Throwable $ignored) {}}
    memory_reply(['error'=>$e->publicMessage,'error_code'=>$e->errorCode],$e->httpStatus);
} catch(mysqli_sql_exception $e) {
    if ($db instanceof mysqli) {try {$db->rollback();}catch(Throwable $ignored) {}}
    error_log('Memory promotion database unavailable: '.(int)$e->getCode());
    if ((int)$e->getCode()===1146 && str_contains($e->getMessage(),'promo_memory_')) {
        memory_reply(['error'=>'O jogo ainda precisa ser ativado no banco de dados. A instalação deve importar o arquivo migrations/memoria-desconto.sql. Nenhuma partida foi iniciada.','error_code'=>'setup_required'],503);
    }
    memory_reply(['error'=>'Não foi possível conectar o jogo ao banco de dados. Tente novamente mais tarde.','error_code'=>'database'],503);
} catch(Throwable $e) {
    if ($db instanceof mysqli) {try {$db->rollback();}catch(Throwable $ignored) {}}
    error_log('Memory promotion unavailable: '.get_class($e));
    memory_reply(['error'=>'O jogo está temporariamente indisponível. Tente mais tarde. Suas partidas já salvas permanecem registradas.'],503);
}
