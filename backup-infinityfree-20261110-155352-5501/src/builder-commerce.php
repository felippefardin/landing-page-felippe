<?php
declare(strict_types=1);
require_once __DIR__.'/memoria-desconto.php';
require_once __DIR__.'/site-builder.php';
require_once __DIR__.'/builder-refunds.php';

function bc_configured(): bool {
    return memory_env('BUILDER_ENABLED', '0') === '1'
        && preg_match('/^[a-f0-9]{64}$/iD', memory_env('BUILDER_APP_KEY')) === 1
        && memory_env('MP_ACCESS_TOKEN') !== ''
        && preg_match('/^\d+$/D', memory_env('MP_COLLECTOR_ID')) === 1
        && in_array(memory_env('MP_MODE', 'test'), ['test', 'production'], true)
        && (bc_browser_mode() || memory_env('MP_WEBHOOK_SECRET') !== '')
        && str_starts_with(memory_env('BUILDER_BASE_URL'), 'https://');
}

function bc_date(?string $date): string {return $date?(new DateTimeImmutable($date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('d/m/Y H:i').' (Brasília)':'—';}
function bc_url(string $path): string {return rtrim(memory_env('BUILDER_BASE_URL'),'/').'/'.$path;}
function bc_db(): mysqli {
    $db=db();memory_sql($db,'SELECT id FROM builder_orders LIMIT 0');return $db;
}
function bc_order(mysqli $db,string $id,bool $lock=false): array {
    if(!preg_match('/^[a-f0-9]{32}$/D',$id))throw new InvalidArgumentException('Pedido inválido.');
    $o=memory_sql($db,'SELECT * FROM builder_orders WHERE id=?'.($lock?' FOR UPDATE':''),'s',[$id])->get_result()->fetch_assoc();
    if(!$o)throw new InvalidArgumentException('Pedido não encontrado.');return $o;
}
function bc_access(array $o,string $token=''): void {
    $management=hash_hmac('sha256','manage-order:'.$o['id'],memory_env('BUILDER_APP_KEY'));
    if(memory_env('BUILDER_APP_KEY')!=='' && $token!=='' && hash_equals($management,$token))return;
    $sessionToken=$_SESSION['builder_access'][$o['id']]??'';
    if(!is_string($sessionToken))$sessionToken='';
    if(!hash_equals($o['access_hash'],hash('sha256',$token)) && !hash_equals($o['access_hash'],hash('sha256',$sessionToken)))throw new MemoryProblem('O link do pedido é inválido ou a sessão expirou.',403);
}
function bc_download_token(array $o): string {
    $key=memory_env('BUILDER_APP_KEY');if(strlen($key)<32)throw new RuntimeException('Chave da entrega ausente.');
    return hash_hmac('sha256',$o['id'].'|'.$o['download_until'].'|'.$o['zip_hash'],$key);
}
function bc_download_access(array $o,string $token): void {
    if($o['status']!=='paid' || !$o['download_until'] || memory_time($o['download_until'])<=time() || !hash_equals(bc_download_token($o),$token))throw new MemoryProblem('O download não está disponível ou o prazo de 7 dias terminou.',403);
}
function bc_quote(mysqli $db,string $email,string $code,bool $support,bool $lock=false): array {
    $coupon=null;
    if($code!==''){
        if(!preg_match('/^(?:FAS|FA15)-[A-F0-9]{24}$/D',$code))throw new InvalidArgumentException('Este cupom não é válido para o criador de páginas.');
        $coupon=memory_sql($db,'SELECT * FROM promo_memory_players WHERE campaign=? AND coupon=?'.($lock?' FOR UPDATE':''),'ss',[MEMORY_CAMPAIGN,$code])->get_result()->fetch_assoc();
        [, $key]=memory_email($email);
        if(!$coupon || !$coupon['verified_at'] || !hash_equals($coupon['email_key'],$key) || memory_coupon_state($coupon)!=='active')throw new InvalidArgumentException('Cupom indisponível, vencido ou vinculado a outro e-mail.');
    }
    return sb_price($support,$coupon!==null)+['coupon_id'=>$coupon?(int)$coupon['id']:null,'coupon_code'=>$coupon?$code:null];
}
function bc_rate(mysqli $db,string $email): void {
    $db->begin_transaction();try{
        memory_rate($db,'builder:all',150);
        memory_rate($db,'builder:ip:'.($_SERVER['REMOTE_ADDR']??'unknown'),8);
        memory_rate($db,'builder:email:'.hash('sha256',$email),5);
        $db->commit();
    }catch(Throwable $e){$db->rollback();throw $e;}
}
function bc_create(mysqli $db,array $in): array {
    if(!bc_configured())throw new MemoryProblem('As compras ainda não estão disponíveis. Você pode personalizar e visualizar sua página.',503);
    $name=sb_text($in,'name',100);if(mb_strlen($name)<2)throw new InvalidArgumentException('Informe seu nome completo.');
    [$email]=memory_email(sb_text($in,'email',254));
    if(($in['accept']??false)!==true)throw new InvalidArgumentException('Leia e aceite o termo antes de continuar.');
    if(!is_array($in['project']??null))throw new InvalidArgumentException('Projeto inválido.');
    $project=sb_project($in['project']);
    if(($in['tested']??false)!==true || !hash_equals(hash('sha256',serialize($project)),(string)($_SESSION['builder_tested_project']??'')))throw new InvalidArgumentException('Teste esta versão do projeto e confirme a conferência antes de continuar.');
    $quote=bc_quote($db,$email,strtoupper(sb_text($in,'coupon',40)),($in['support']??false)===true);
    bc_rate($db,$email);
    $zip=sb_zip($project);$id=bin2hex(random_bytes(16));$token=bin2hex(random_bytes(32));$otp=(string)random_int(100000,999999);
    $o=$quote+['id'=>$id,'customer_name'=>$name,'email'=>$email,'project_title'=>$project['brand']];
    $terms=sb_terms($o);$now=memory_now();$hash=hash('sha256',$terms);$ziphash=hash('sha256',$zip);
    $db->begin_transaction();try{
        memory_sql($db,'INSERT INTO builder_orders (id,access_hash,customer_name,email,project_title,base_cents,discount_cents,support_cents,total_cents,coupon_id,coupon_code,terms_version,terms_text,terms_hash,otp_hash,otp_expires,otp_sends,otp_last,zip_hash,created_at,support_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1,?,?,?,?)',
        'sssssiiiiissssssssss',[$id,hash('sha256',$token),$name,$email,$project['brand'],35000,$quote['discount_cents'],$quote['support_cents'],$quote['total_cents'],$quote['coupon_id'],$quote['coupon_code'],BUILDER_TERMS_VERSION,$terms,$hash,password_hash($otp,PASSWORD_DEFAULT),gmdate('Y-m-d H:i:s',time()+600),$now,$ziphash,$now,$quote['support_cents']>0?'requested':'not_requested']);
        memory_sql($db,'INSERT INTO builder_archives (order_id,zip_data,purge_at) VALUES (?,?,?)','sss',[$id,$zip,gmdate('Y-m-d H:i:s',time()+86400)]);
        $db->commit();
    }catch(Throwable $e){$db->rollback();throw $e;}
    if(count($_SESSION['builder_access']??[])>=10)array_shift($_SESSION['builder_access']);
    $_SESSION['builder_access'][$id]=$token;
    $sent=true;try{memory_mail($email,'Confirme o aceite do seu projeto — Tech Tecnologia',"Seu código de confirmação: $otp\n\nPedido: $id\nTotal: ".sb_money($quote['total_cents'])."\nCódigo válido por 10 minutos. Use-o apenas se conferiu a prévia, os valores e o termo. A confirmação registra assinatura eletrônica simples e ainda não efetua pagamento.\n\nPara retomar este pedido: ".bc_url('pedido-site.php?id='.$id.'&token='.$token)."\n\nNão compartilhe o código nem o link do pedido. Se não solicitou, ignore esta mensagem.");}catch(Throwable $e){error_log('Builder OTP failed: '.get_class($e));$sent=false;}
    return ['id'=>$id,'token'=>$token,'terms'=>$terms,'terms_hash'=>$hash,'quote'=>$quote,'sent'=>$sent];
}
function bc_resend_otp(mysqli $db,array $o): bool {
    bc_rate($db,$o['email']);$code=(string)random_int(100000,999999);
    $db->begin_transaction();try{
        $o=bc_order($db,$o['id'],true);
        if($o['signed_at'] || (int)$o['otp_sends']>=5 || memory_time($o['otp_last'])>time()-60)throw new InvalidArgumentException('Aguarde 1 minuto entre envios. Limite de 5 códigos por pedido.');
        memory_sql($db,'UPDATE builder_orders SET otp_hash=?,otp_expires=?,otp_attempts=0,otp_sends=otp_sends+1,otp_last=? WHERE id=?','ssss',[password_hash($code,PASSWORD_DEFAULT),gmdate('Y-m-d H:i:s',time()+600),memory_now(),$o['id']]);$db->commit();
    }catch(Throwable $e){$db->rollback();throw $e;}
    try{memory_mail($o['email'],'Novo código de aceite — Tech Tecnologia',"Código: $code\nPedido: ".$o['id']."\nVálido por 10 minutos. A confirmação registra assinatura eletrônica simples, não pagamento.");return true;}catch(Throwable $e){return false;}
}
function bc_sign(mysqli $db,array $o,string $code,string $expectedHash): array {
    $db->begin_transaction();try{
        $o=bc_order($db,$o['id'],true);
        if(!hash_equals($o['terms_hash'],$expectedHash))throw new InvalidArgumentException('O termo mudou. Recarregue e confira antes de assinar.');
        if($o['signed_at']){$db->commit();return $o;}
        if($o['status']!=='draft' || !$o['otp_hash'] || memory_time($o['otp_expires'])<=time() || (int)$o['otp_attempts']>=5)throw new InvalidArgumentException('Código expirado ou bloqueado. Solicite outro.');
        if(!preg_match('/^\d{6}$/D',$code) || !password_verify($code,$o['otp_hash'])){
            memory_sql($db,'UPDATE builder_orders SET otp_attempts=otp_attempts+1 WHERE id=?','s',[$o['id']]);$db->commit();throw new MemoryProblem('Código incorreto. Após 5 erros, solicite outro.');
        }
        $now=memory_now();$signature=hash('sha256',json_encode([$o['id'],$o['customer_name'],$o['email'],$o['terms_hash'],$o['zip_hash'],$now,'email-otp-v1'],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
        memory_sql($db,"UPDATE builder_orders SET status='signed',signed_at=?,signature_hash=?,otp_hash=NULL,otp_expires=NULL WHERE id=?",'sss',[$now,$signature,$o['id']]);$db->commit();return bc_order($db,$o['id']);
    }catch(Throwable $e){$db->rollback();throw $e;}
}
function bc_api(string $method,string $path,?array $body=null,string $idempotency=''): array {
    $token=memory_env('MP_ACCESS_TOKEN');if($token==='')throw new RuntimeException('Pagamento não configurado.');
    $curl=curl_init('https://api.mercadopago.com'.$path);$headers=['Authorization: Bearer '.$token,'Content-Type: application/json'];if($idempotency!=='')$headers[]='X-Idempotency-Key: '.$idempotency;
    curl_setopt_array($curl,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>20,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$headers,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2]);
    if($body!==null)curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($body,JSON_THROW_ON_ERROR));
    $raw=curl_exec($curl);$status=curl_getinfo($curl,CURLINFO_RESPONSE_CODE);curl_close($curl);
    if(!is_string($raw) || $status<200 || $status>=300)throw new RuntimeException('Não foi possível consultar o provedor de pagamento. Tente novamente.');
    $data=json_decode($raw,true,512,JSON_THROW_ON_ERROR);if(!is_array($data))throw new RuntimeException('Resposta de pagamento inválida.');return $data;
}
function bc_checkout(mysqli $db,array $o,string $access): string {
    if(!bc_configured())throw new RuntimeException('Pagamento indisponível.');
    $db->begin_transaction();try{
        $o=bc_order($db,$o['id'],true);
        if(!$o['signed_at'] || !in_array($o['status'],['signed','pending'],true))throw new InvalidArgumentException('Confirme o termo antes do pagamento.');
        if($o['checkout_url']){$db->commit();return $o['checkout_url'];}
        if(!memory_sql($db,'SELECT order_id FROM builder_archives WHERE order_id=? AND purge_at>?','ss',[$o['id'],memory_now()])->get_result()->fetch_assoc())throw new InvalidArgumentException('O rascunho expirou. Personalize novamente antes de pagar.');
        if($o['coupon_id']){
            $coupon=memory_player($db,(int)$o['coupon_id'],true);
            if(memory_coupon_state($coupon)!=='active')throw new InvalidArgumentException('O cupom não está mais disponível. Refaça o resumo da compra.');
            memory_sql($db,"UPDATE promo_memory_players SET coupon_status='reserved' WHERE id=?",'i',[(int)$o['coupon_id']]);
        }
        $return=bc_url('pedido-site.php?id='.$o['id'].'&token='.rawurlencode($access));
        $items=[['id'=>'pagina','title'=>'Página personalizada — '.$o['project_title'],'quantity'=>1,'currency_id'=>'BRL','unit_price'=>((int)$o['base_cents']-(int)$o['discount_cents'])/100]];
        if((int)$o['support_cents']>0)$items[]=['id'=>'suporte','title'=>'Suporte opcional para publicação — até 2 horas','quantity'=>1,'currency_id'=>'BRL','unit_price'=>150];
        $pref=bc_api('POST','/checkout/preferences',['items'=>$items,'payer'=>['email'=>$o['email'],'name'=>$o['customer_name']],'external_reference'=>$o['id'],'metadata'=>['builder_order'=>$o['id']],'notification_url'=>bc_url('pagamento-webhook.php'),'back_urls'=>['success'=>$return,'pending'=>$return,'failure'=>$return],'auto_return'=>'approved','expires'=>true,'expiration_date_from'=>gmdate('Y-m-d\TH:i:s.000\Z'),'expiration_date_to'=>gmdate('Y-m-d\TH:i:s.000\Z',time()+86400),'payment_methods'=>['excluded_payment_types'=>[['id'=>'ticket'],['id'=>'atm']]]],$o['id']);
        $url=memory_env('MP_MODE','test')==='production'?($pref['init_point']??''):($pref['sandbox_init_point']??'');
        if(!is_string($url)||!preg_match('~^https://(?:[a-z0-9-]+\.)*mercadopago\.(?:com\.br|com)/~i',$url)||empty($pref['id']))throw new RuntimeException('O provedor não retornou um checkout válido.');
        memory_sql($db,"UPDATE builder_orders SET status='pending',preference_id=?,checkout_url=? WHERE id=?",'sss',[(string)$pref['id'],$url,$o['id']]);
        memory_sql($db,'UPDATE builder_archives SET purge_at=? WHERE order_id=?','ss',[gmdate('Y-m-d H:i:s',time()+30*86400),$o['id']]);$db->commit();return $url;
    }catch(Throwable $e){$db->rollback();throw $e;}
}
function bc_payment_valid(array $o,array $p): bool {
    $live=memory_env('MP_MODE','test')==='production';
    return (string)($p['external_reference']??'')===$o['id'] && (string)($p['collector_id']??'')===memory_env('MP_COLLECTOR_ID') && ($p['currency_id']??'')==='BRL' && abs(((float)($p['transaction_amount']??-1)*100)-(int)$o['total_cents'])<0.01 && ($p['live_mode']??null)===$live;
}
function bc_apply_payment(mysqli $db,array $p): void {
    $id=(string)($p['external_reference']??'');if(!preg_match('/^[a-f0-9]{32}$/D',$id))return;
    $db->begin_transaction();try{
        $o=bc_order($db,$id,true);
        if(!bc_payment_valid($o,$p) || !$o['signed_at'])throw new RuntimeException('Dados do pagamento não correspondem ao pedido.');
        $pid=(string)($p['id']??'');if(!preg_match('/^\d+$/D',$pid))throw new RuntimeException('Identificador do pagamento inválido.');
        if($o['payment_id'] && $o['payment_id']!==$pid){error_log('Builder duplicate payment requires review; order='.$id);$db->commit();return;}
        $status=(string)($p['status']??'unknown');
        if(in_array($status,['refunded','charged_back'],true) || (float)($p['transaction_amount_refunded']??0)>0){
            memory_sql($db,"UPDATE builder_orders SET status='revoked',payment_status=? WHERE id=?",'ss',[$status,$id]);
            memory_sql($db,'DELETE FROM builder_archives WHERE order_id=?','s',[$id]);
            if($status==='refunded' && (float)($p['transaction_amount_refunded']??0)*100 >= (int)$o['total_cents']-.01)memory_sql($db,"UPDATE builder_refunds SET status='refunded',processed_at=COALESCE(processed_at,?),last_error=NULL WHERE order_id=?",'ss',[memory_now(),$id]);
            $db->commit();return;
        }
        if($status!=='approved'){
            if($o['status']!=='paid')memory_sql($db,'UPDATE builder_orders SET payment_status=? WHERE id=?','ss',[$status,$id]);
            $db->commit();return;
        }
        if($o['paid_at']){$db->commit();return;}
        if($o['status']!=='pending' || !$o['preference_id'])throw new RuntimeException('Pedido sem checkout associado.');
        $archive=memory_sql($db,'SELECT order_id FROM builder_archives WHERE order_id=? FOR UPDATE','s',[$id])->get_result()->fetch_assoc();
        if(!$archive){memory_sql($db,"UPDATE builder_orders SET status='needs_review',payment_id=?,payment_status=? WHERE id=?",'sss',[$pid,$status,$id]);$db->commit();return;}
        $now=memory_now();$until=gmdate('Y-m-d H:i:s',time()+7*86400);
        if($o['coupon_id']){
            $coupon=memory_player($db,(int)$o['coupon_id'],true);
            if($coupon['coupon_status']!=='reserved')throw new RuntimeException('Reserva do cupom inválida.');
            memory_sql($db,"UPDATE promo_memory_players SET coupon_status='used',redeemed_at=?,redeemed_by=? WHERE id=?",'ssi',[$now,'checkout:'.$id,(int)$o['coupon_id']]);
        }
        memory_sql($db,"UPDATE builder_orders SET status='paid',payment_id=?,payment_status='approved',paid_at=?,download_until=? WHERE id=?",'ssss',[$pid,$now,$until,$id]);
        memory_sql($db,'UPDATE builder_archives SET purge_at=? WHERE order_id=?','ss',[$until,$id]);
        memory_sql($db,'INSERT IGNORE INTO builder_mail_jobs (order_id) VALUES (?)','s',[$id]);$db->commit();
    }catch(Throwable $e){$db->rollback();throw $e;}
}
function bc_refresh(mysqli $db,array $o): void {
    if(!$o['preference_id'] || memory_time($o['last_check'])>time()-30)return;
    // Compare-and-set throttle remains effective across concurrent status requests.
    $now=memory_now();$stmt=memory_sql($db,'UPDATE builder_orders SET last_check=? WHERE id=? AND (last_check IS NULL OR last_check<?)','sss',[$now,$o['id'],gmdate('Y-m-d H:i:s',time()-30)]);if($stmt->affected_rows!==1)return;
    $data=bc_api('GET','/v1/payments/search?external_reference='.rawurlencode($o['id']).'&sort=date_created&criteria=desc&limit=30');
    foreach($data['results']??[] as $p)if(is_array($p))bc_apply_payment($db,$p);
}
function bc_webhook_valid(string $id,string $request,string $signature,string $secret,?int $now=null): bool {
    if($secret==='' || $request==='' || !preg_match('/^\d+$/D',$id))return false;
    $parts=[];foreach(explode(',',$signature) as $part){$v=explode('=',trim($part),2);if(count($v)===2)$parts[$v[0]]=$v[1];}
    if(!preg_match('/^\d{10,13}$/D',$parts['ts']??'') || !preg_match('/^[a-f0-9]{64}$/iD',$parts['v1']??''))return false;
    $ts=(int)$parts['ts'];if($ts>100000000000)$ts=(int)floor($ts/1000);
    if(abs(($now??time())-$ts)>600)return false;
    return hash_equals(hash_hmac('sha256','id:'.$id.';request-id:'.$request.';ts:'.$parts['ts'].';',$secret),$parts['v1']);
}
function bc_document_html(array $o): string {
    if(!$o['signed_at'] || !hash_equals($o['terms_hash'],hash('sha256',$o['terms_text'])))throw new RuntimeException('Documento não assinado ou com conteúdo inconsistente.');
    if(!hash_equals((string)$o['signature_hash'],hash('sha256',json_encode([$o['id'],$o['customer_name'],$o['email'],$o['terms_hash'],$o['zip_hash'],$o['signed_at'],'email-otp-v1'],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR))))throw new RuntimeException('Evidência inconsistente.');
    return '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><style>body{font:11px/1.5 DejaVu Sans,sans-serif;color:#172033}h1{font-size:20px}pre{font:11px/1.6 DejaVu Sans,sans-serif;white-space:pre-wrap;overflow-wrap:break-word}.hash{font-size:9px;word-wrap:break-word}.evidence{border:1px solid #94a3b8;padding:14px}</style></head><body><h1>Termo de contratação e aceite eletrônico</h1><div style="border:2px solid #b45309;padding:12px;background:#fffbeb"><strong>ATENÇÃO: DIREITO DE ARREPENDIMENTO — 7 DIAS</strong><p>Art. 49 do CDC e art. 5º do Decreto nº 7.962/2013. Após o prazo, não há devolução por simples arrependimento nesta oferta; permanecem os direitos por falhas e descumprimento da oferta. Consulte as condições no termo assinado abaixo.</p></div><div class="evidence"><strong>Assinatura eletrônica simples — confirmação por código enviado ao e-mail</strong><p>Cliente: '.sb_escape($o['customer_name']).'<br>E-mail: '.sb_escape($o['email']).'<br>Pedido: '.sb_escape($o['id']).'<br>Aceito em: '.sb_escape($o['signed_at']).' UTC</p><p class="hash">SHA-256 do termo: '.sb_escape($o['terms_hash']).'<br>SHA-256 do arquivo entregue: '.sb_escape($o['zip_hash']).'<br>Identificador da evidência: '.sb_escape($o['signature_hash']).'</p><p>Representação do registro eletrônico, sem assinatura por certificado ICP-Brasil. O aceite não comprova pagamento.</p></div><pre>'.sb_escape($o['terms_text']).'</pre></body></html>';
}
function bc_pdf(array $o): string {
    require_once dirname(__DIR__).'/dompdf/autoload.inc.php';
    $options=new \Dompdf\Options();$options->set('isRemoteEnabled',false);$options->set('isPhpEnabled',false);$options->set('defaultFont','DejaVu Sans');
    $pdf=new \Dompdf\Dompdf($options);$pdf->loadHtml(bc_document_html($o),'UTF-8');$pdf->setPaper('A4');$pdf->render();return $pdf->output();
}
function bc_deliver(mysqli $db,string $id): bool {
    $db->begin_transaction();try{
        $o=bc_order($db,$id,true);$job=memory_sql($db,'SELECT * FROM builder_mail_jobs WHERE order_id=? FOR UPDATE','s',[$id])->get_result()->fetch_assoc();
        if(!$job || $job['sent_at'] || $o['status']!=='paid' || memory_time($o['download_until'])<=time()){$db->commit();return false;}
        if((int)$job['attempts']>=10 || memory_time($job['lease_until'])>time() || memory_time($job['last_attempt'])>time()-60){$db->commit();return false;}
        memory_sql($db,'UPDATE builder_mail_jobs SET attempts=attempts+1,last_attempt=?,lease_until=? WHERE order_id=?','sss',[memory_now(),gmdate('Y-m-d H:i:s',time()+120),$id]);$db->commit();
    }catch(Throwable $e){$db->rollback();throw $e;}
    try{
        $archive=memory_sql($db,'SELECT zip_data FROM builder_archives WHERE order_id=? AND purge_at>?','ss',[$id,memory_now()])->get_result()->fetch_assoc();if(!$archive)throw new RuntimeException('Arquivo temporário ausente.');
        // Load the same supported mail transport already used by the site.
        if(!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)){
            $base=dirname(__DIR__);foreach(['Exception','PHPMailer','SMTP'] as $class){$path=$base.'/PHPMailer/PHPMailer/'.$class.'.php';if(!is_file($path))$path=$base.'/PHPMailer/src/'.$class.'.php';require_once $path;}
        }
        $cfg=smtp_config();$mail=new \PHPMailer\PHPMailer\PHPMailer(true);$mail->isSMTP();$mail->Host=$cfg['host'];$mail->Port=$cfg['port'];$mail->SMTPAuth=true;$mail->Username=$cfg['username'];$mail->Password=$cfg['password'];$mail->SMTPSecure=(int)$cfg['port']===465?'ssl':'tls';$mail->Timeout=20;$mail->SMTPDebug=0;$mail->CharSet='UTF-8';$mail->setFrom($cfg['from_email'],$cfg['from_name']);$mail->addAddress($o['email']);$mail->Subject='Seu site está pronto para baixar — Tech Tecnologia';
        $link=bc_url('baixar-site.php?id='.$id.'&token='.bc_download_token($o));
        $orderLink=bc_url('pedido-site.php?id='.$id.'&token='.hash_hmac('sha256','manage-order:'.$id,memory_env('BUILDER_APP_KEY')));
        $mail->Body="Pagamento confirmado!\nPedido: $id\nTotal: ".sb_money((int)$o['total_cents'])."\nPágina: ".sb_money((int)$o['base_cents'])."\nDesconto da página: ".sb_money((int)$o['discount_cents'])."\nSuporte opcional: ".sb_money((int)$o['support_cents'])."\n\nAcompanhar o pedido ou solicitar reembolso (acesso permanece após o fim do download):\n$orderLink\n\nSeu ZIP está anexado. Você também pode baixar em:\n$link\n\nPrazo do link: ".$o['download_until']." UTC. Após 7 dias, os arquivos serão excluídos do sistema. Guarde este e-mail, o ZIP e um backup.\n\n".($o['coupon_code']?"Cupom ".$o['coupon_code'].": UTILIZADO. Ele não poderá ser usado novamente.\n\n":'').((int)$o['support_cents']>0?"Você contratou suporte opcional de até 2 horas. Para agendar, escreva para ".memory_env('BUILDER_CONTACT_EMAIL','felippefardin@gmail.com').".\n":"Suporte de publicação não contratado.\n")."\nSeu termo de aceite eletrônico também está anexado em PDF.";
        $mail->addStringAttachment($archive['zip_data'],'meu-site.zip','base64','application/zip');$mail->addStringAttachment(bc_pdf($o),'termo-'.$id.'.pdf','base64','application/pdf');$mail->send();
        memory_sql($db,'UPDATE builder_mail_jobs SET sent_at=?,lease_until=NULL WHERE order_id=?','ss',[memory_now(),$id]);memory_sql($db,'UPDATE builder_orders SET delivery_sent_at=? WHERE id=?','ss',[memory_now(),$id]);return true;
    }catch(Throwable $e){memory_sql($db,'UPDATE builder_mail_jobs SET lease_until=NULL WHERE order_id=?','s',[$id]);error_log('Builder delivery failed; order='.$id.' type='.get_class($e));return false;}
}

function bc_notify_support(mysqli $db,array $o): void {
    if($o['status']!=='paid' || (int)$o['support_cents']===0 || $o['support_notified_at'] || (int)$o['support_mail_attempts']>=5 || memory_time($o['support_mail_last'])>time()-60)return;
    memory_sql($db,'UPDATE builder_orders SET support_mail_attempts=support_mail_attempts+1,support_mail_last=? WHERE id=?','ss',[memory_now(),$o['id']]);
    try{
        memory_mail(memory_env('BUILDER_CONTACT_EMAIL','felippefardin@gmail.com'),'Novo suporte de publicação contratado — Tech Tecnologia',"Pagamento confirmado com suporte opcional de R$ 150,00 por até 2 horas.\nPedido: ".$o['id']."\nCliente: ".$o['customer_name']."\nE-mail: ".$o['email']."\nTotal pago: ".sb_money((int)$o['total_cents'])."\n\nEntre em contato com o cliente para combinar o horário. Consulte e atualize o atendimento no painel: ".bc_url('documentos-assinados.php?q='.$o['id']));
        memory_sql($db,'UPDATE builder_orders SET support_notified_at=? WHERE id=?','ss',[memory_now(),$o['id']]);
    }catch(Throwable $e){error_log('Builder support notice failed; order='.$o['id']);}
}
