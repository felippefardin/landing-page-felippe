<?php
declare(strict_types=1);

function br_deadline(array $o): ?string {
    // Use the later delivery/availability date, and the end of the seventh local day.
    $dates=array_filter([$o['paid_at']??null,$o['delivery_sent_at']??null]);
    if(!$dates)return null;
    $date=new DateTimeImmutable(max($dates),new DateTimeZone('UTC'));
    return $date->setTimezone(new DateTimeZone('America/Sao_Paulo'))->modify('+7 days')->setTime(23,59,59)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
}
function br_row(mysqli $db,string $id): ?array {
    return memory_sql($db,'SELECT * FROM builder_refunds WHERE order_id=?','s',[$id])->get_result()->fetch_assoc();
}
function br_info(mysqli $db,array $o): array {
    $r=br_row($db,$o['id']);$until=br_deadline($o);
    return ['refund'=>$r?['status'=>$r['status'],'requested_display'=>bc_date($r['requested_at'])]:null,
        'refund_eligible'=>$o['status']==='paid' && !$r && $until && memory_time($until)>=time(),
        'refund_until_display'=>bc_date($until)];
}
function br_request(mysqli $db,array $o,string $reason): array {
    if(mb_strlen($reason)>2000)throw new InvalidArgumentException('Use até 2.000 caracteres. O motivo é opcional.');
    $db->begin_transaction();try{
        $o=bc_order($db,$o['id'],true);$existing=br_row($db,$o['id']);
        if($existing){$db->commit();return $existing;}
        $until=br_deadline($o);
        if($o['status']!=='paid'||!$until||memory_time($until)<time())throw new InvalidArgumentException('A solicitação de arrependimento não está disponível. Para falhas, entrega ou outros direitos, fale com o atendimento.');
        memory_sql($db,'INSERT INTO builder_refunds(order_id,reason,requested_at,deadline_at,amount_cents) VALUES(?,?,?,?,?)','ssssi',[$o['id'],$reason,memory_now(),$until,(int)$o['total_cents']]);
        // Cancelling blocks every existing download token immediately, including links in emails.
        memory_sql($db,"UPDATE builder_orders SET status='refund_requested',download_until=? WHERE id=?",'ss',[memory_now(),$o['id']]);
        $db->commit();
    }catch(Throwable $e){$db->rollback();throw $e;}
    br_notify($db,$o['id']);return br_row($db,$o['id']);
}
function br_notify(mysqli $db,string $id): void {
    $r=br_row($db,$id);if(!$r)return;$o=bc_order($db,$id);
    foreach(['customer_notified_at'=>$o['email'],'admin_notified_at'=>memory_env('BUILDER_CONTACT_EMAIL','felippefardin@gmail.com')] as $field=>$email){
        if($r[$field])continue;
        try{
            memory_mail($email,'Solicitação de reembolso recebida — Felippe Andreata',"Pedido: $id\nSolicitação registrada em ".bc_date($r['requested_at'])."\nValor integral: ".sb_money((int)$r['amount_cents'])."\nO download foi cancelado. O pedido de devolução está registrado, mas o dinheiro só será considerado devolvido após confirmação do provedor. O prazo de crédito depende do meio de pagamento.\nMotivo (opcional): ".$r['reason']."\nAtendimento: ".memory_env('BUILDER_CONTACT_EMAIL','felippefardin@gmail.com'));
            memory_sql($db,"UPDATE builder_refunds SET $field=? WHERE order_id=?",'ss',[memory_now(),$id]);
        }catch(Throwable $e){error_log('Refund receipt email pending; order='.$id);}
    }
    if($r['status']==='refunded'&&!$r['completed_notified_at']){
        try{memory_mail($o['email'],'Reembolso confirmado — Felippe Andreata',"Pedido: $id\nO provedor confirmou a devolução integral de ".sb_money((int)$r['amount_cents']).". O prazo para o crédito depende do meio de pagamento e da instituição financeira. Novos downloads permanecem cancelados.");memory_sql($db,'UPDATE builder_refunds SET completed_notified_at=? WHERE order_id=?','ss',[memory_now(),$id]);}catch(Throwable $e){error_log('Refund completion email pending; order='.$id);}
    }
}
function br_sync(mysqli $db,string $id): void {
    $o=bc_order($db,$id);$r=br_row($db,$id);if(!$r||!$o['payment_id'])return;
    $p=bc_api('GET','/v1/payments/'.rawurlencode($o['payment_id']));
    if(!bc_payment_valid($o,$p))throw new RuntimeException('Pagamento não corresponde ao pedido.');
    bc_apply_payment($db,$p);
    if(($p['status']??'')==='refunded' && (float)($p['transaction_amount_refunded']??0)*100 >= (int)$r['amount_cents']-.01){
        memory_sql($db,"UPDATE builder_refunds SET status='refunded',processed_at=COALESCE(processed_at,?),last_error=NULL WHERE order_id=?",'ss',[memory_now(),$id]);
    }
    br_notify($db,$id);
}
function br_process(mysqli $db,string $id): void {
    // Owner access is checked again here; public endpoints cannot initiate money transfers.
    if(!function_exists('bc_admin')||!bc_admin())throw new RuntimeException('Acesso restrito.');
    br_sync($db,$id);$r=br_row($db,$id);$o=bc_order($db,$id);
    if(!$r)throw new InvalidArgumentException('Solicitação não encontrada.');
    if($r['status']==='refunded')return;
    if(!bc_configured()||!$o['payment_id']||$o['payment_status']!=='approved')throw new InvalidArgumentException('Confira o pagamento com o provedor antes de reembolsar.');
    memory_sql($db,"UPDATE builder_refunds SET status='processing',last_error=NULL WHERE order_id=?",'s',[$id]);
    try{
        // A stable key makes retries after timeouts safe. Never use a new key for this refund.
        $response=bc_api('POST','/v1/payments/'.rawurlencode($o['payment_id']).'/refunds',null,hash('sha256','builder-full-refund:'.$id));
        memory_sql($db,'UPDATE builder_refunds SET provider_refund_id=? WHERE order_id=?','ss',[(string)($response['id']??''),$id]);
        br_sync($db,$id);
    }catch(Throwable $e){
        memory_sql($db,"UPDATE builder_refunds SET last_error='Confira o provedor. Resultado não confirmado; use Conferir ou repita com a mesma chave.' WHERE order_id=?",'s',[$id]);
        throw new RuntimeException('Devolução ainda não confirmada. Confira o provedor antes de repetir.');
    }
}
