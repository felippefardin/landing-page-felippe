<?php
declare(strict_types=1);
require_once __DIR__.'/src/builder-commerce.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');
function ba_reply(array $data,int $status=200): never {http_response_code($status);echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);exit;}
try{
    if($_SERVER['REQUEST_METHOD']!=='POST'){header('Allow: POST');ba_reply(['error'=>'Método inválido.'],405);}
    $raw=file_get_contents('php://input',false,null,0,6*1024*1024+1);
    if(!is_string($raw)||strlen($raw)>6*1024*1024)ba_reply(['error'=>'As imagens ultrapassam o limite do projeto.'],413);
    $in=json_decode($raw,true,32,JSON_THROW_ON_ERROR);if(!is_array($in))throw new InvalidArgumentException('Dados inválidos.');
    if(!is_string($in['csrf']??null)||!hash_equals(csrf_token(),$in['csrf']))ba_reply(['error'=>'A sessão expirou. Atualize a página.'],403);
    $action=$in['action']??'';
    if(in_array($action,['preview','test_preview'],true)){
        if(!is_array($in['project']??null))throw new InvalidArgumentException('Projeto inválido.');
        $project=sb_project($in['project'],$action==='preview');$html=sb_html($project,true);
        if($action==='test_preview'){$_SESSION['builder_tested_project']=hash('sha256',serialize($project));$html=sb_test_html($html);}
        ba_reply(['html'=>$html]);
    }
    if($action==='terms'){
        $email=is_string($in['email']??null)?strtolower(trim($in['email'])):'';$code=is_string($in['coupon']??null)?strtoupper(trim($in['coupon'])):'';
        $quote=$code===''?sb_price(($in['support']??false)===true,false):bc_quote(bc_db(),$email,$code,($in['support']??false)===true);
        $o=$quote+['id'=>'Prévia — o identificador será gerado ao continuar','customer_name'=>sb_text($in,'name',100),'customer_cpf'=>sb_cpf($in),'email'=>$email,'project_title'=>sb_text($in,'title',100)];
        ba_reply(['quote'=>$quote,'terms'=>sb_terms($o)]);
    }
    $db=bc_db();
    if(bc_browser_mode())bc_cleanup($db);
    if($action==='create')ba_reply(bc_create($db,$in));
    $id=is_string($in['id']??null)?$in['id']:'';$o=bc_order($db,$id);$token=is_string($in['token']??null)?$in['token']:'';bc_access($o,$token);
    if($action==='refund_request'){br_request($db,$o,sb_text($in,'reason',2000));ba_reply(['requested'=>true]+br_info($db,bc_order($db,$id)));}
    if($action==='resend')ba_reply(['sent'=>bc_resend_otp($db,$o)]);
    if($action==='sign'){
        if(($in['agree']??false)!==true)throw new InvalidArgumentException('Confirme a leitura do termo.');
        $o=bc_sign($db,$o,sb_text($in,'code',6),sb_text($in,'terms_hash',64));
        // Signing is committed first; checkout failure does not require signing again.
        $access=$token!==''?$token:($_SESSION['builder_access'][$id]??'');ba_reply(['checkout'=>bc_checkout($db,$o,$access)]);
    }
    if($action==='checkout'){
        $access=$token!==''?$token:($_SESSION['builder_access'][$id]??'');ba_reply(['checkout'=>bc_checkout($db,$o,$access)]);
    }
    if($action==='deliver'){
        if(!bc_browser_mode())throw new InvalidArgumentException('Entrega gerenciada pela fila.');
        bc_refresh($db,$o);$o=bc_order($db,$id);
        if($o['status']!=='paid' || memory_time($o['download_until'])<=time())throw new InvalidArgumentException('Confirme o pagamento e o prazo de download antes de enviar.');
        $sent=(bool)$o['delivery_sent_at'] || bc_deliver($db,$id);
        ba_reply(['sent'=>$sent,'delivery_message'=>$sent?'Entrega enviada ao seu e-mail.':'Não foi possível enviar agora. O download continua disponível; tente novamente em 1 minuto.']);
    }
    if($action==='status'){
        bc_refresh($db,$o);$o=bc_order($db,$id);
        ba_reply(br_info($db,$o)+['browser_delivery'=>bc_browser_mode(),'status'=>$o['status'],'payment_status'=>$o['payment_status'],'email'=>$o['email'],'until'=>$o['download_until'],'until_display'=>bc_date($o['download_until']),'sent'=>(bool)$o['delivery_sent_at'],'download'=>$o['status']==='paid'&&memory_time($o['download_until'])>time()?bc_url('baixar-site.php?id='.$id.'&token='.bc_download_token($o)):null]);
    }
    throw new InvalidArgumentException('Ação inválida.');
}catch(MemoryProblem $e){ba_reply(['error'=>$e->publicMessage],$e->httpStatus);}
catch(InvalidArgumentException $e){ba_reply(['error'=>$e->getMessage()],422);}
catch(Throwable $e){error_log('Builder request failed: '.get_class($e));ba_reply(['error'=>'Não foi possível concluir esta etapa. Seu aceite, se confirmado, continua registrado. Tente novamente ou fale com o atendimento.'],503);}
