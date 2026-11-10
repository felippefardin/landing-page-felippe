<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require_once dirname(__DIR__).'/src/builder-commerce.php';
$db=bc_db();
// One worker at a time; mail jobs also use short leases for cross-process safety.
$lock=memory_sql($db,"SELECT GET_LOCK('site_builder_worker',0) AS ok")->get_result()->fetch_assoc();
if((int)($lock['ok']??0)!==1)exit;
try{
    $pending=memory_sql($db,"SELECT * FROM builder_orders WHERE status IN ('pending','paid') AND preference_id IS NOT NULL ORDER BY COALESCE(last_check,'1970-01-01') LIMIT 20")->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($pending as $o){try{bc_refresh($db,$o);}catch(Throwable $e){error_log('Builder reconciliation failed; order='.$o['id']);}}
    $jobs=memory_sql($db,"SELECT j.order_id FROM builder_mail_jobs j JOIN builder_orders o ON o.id=j.order_id WHERE j.sent_at IS NULL AND j.attempts<10 AND o.status='paid' AND o.download_until>? AND (j.lease_until IS NULL OR j.lease_until<?) AND (j.last_attempt IS NULL OR j.last_attempt<?) ORDER BY o.paid_at LIMIT 10",'sss',[memory_now(),memory_now(),gmdate('Y-m-d H:i:s',time()-60)])->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($jobs as $job)bc_deliver($db,$job['order_id']);
    $support=memory_sql($db,"SELECT * FROM builder_orders WHERE status='paid' AND support_cents>0 AND support_notified_at IS NULL AND support_mail_attempts<5 ORDER BY paid_at LIMIT 10")->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($support as $o)bc_notify_support($db,$o);
    $refunds=memory_sql($db,"SELECT order_id FROM builder_refunds WHERE status<>'refunded' OR completed_notified_at IS NULL OR customer_notified_at IS NULL OR admin_notified_at IS NULL ORDER BY requested_at DESC LIMIT 20")->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($refunds as $refund){try{br_sync($db,$refund['order_id']);}catch(Throwable $e){error_log('Refund reconciliation pending; order='.$refund['order_id']);}}
    // Delete only temporary project bytes; retain the signed terms and purchase records.
    memory_sql($db,'DELETE FROM builder_archives WHERE purge_at<=?','s',[memory_now()]);
    memory_sql($db,"UPDATE builder_orders SET otp_hash=NULL,otp_expires=NULL WHERE signed_at IS NULL AND otp_expires<?",'s',[memory_now()]);
    echo "Fila de entrega e expiração processadas.\n";
}finally{memory_sql($db,"SELECT RELEASE_LOCK('site_builder_worker')");}
