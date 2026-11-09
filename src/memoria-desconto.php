<?php
declare(strict_types=1);
require_once __DIR__.'/bootstrap.php';

const MEMORY_CAMPAIGN = 'memoria-50-v1';
const MEMORY_PAIRS = 25;
const MEMORY_MOVES = 80;
const MEMORY_GAMES = 3;

final class MemoryProblem extends RuntimeException {
    public function __construct(public string $publicMessage, public int $httpStatus=422, public string $errorCode='') { parent::__construct($publicMessage); }
}
function memory_sql(mysqli $db,string $sql,string $types='',array $values=[]): mysqli_stmt {
    $stmt=$db->prepare($sql);
    if ($types!=='') $stmt->bind_param($types,...$values);
    $stmt->execute();return $stmt;
}
function memory_now(): string { return gmdate('Y-m-d H:i:s'); }
function memory_time(?string $value): int { return $value ? (strtotime($value.' UTC') ?: 0) : 0; }
function memory_env(string $key,string $default=''): string {
    return (string)($_ENV[$key] ?? (getenv($key)!==false ? getenv($key) : (defined($key)?constant($key):$default)));
}
function memory_admin(): bool {
    // The existing login stores only a display name, which is not a safe admin identity.
    // Require both website login and ownership of the configured mailbox, verified by OTP.
    if (empty($_SESSION['usuario']) || empty($_SESSION['memory_player'])) return false;
    $row=memory_player(db(),(int)$_SESSION['memory_player']);
    [, $adminKey]=memory_email(memory_env('DISCOUNT_ADMIN_EMAIL','felippefardin@gmail.com'));
    return !empty($row['verified_at']) && hash_equals($adminKey,$row['email_key']);
}
function memory_email(string $value): array {
    $email=strtolower(trim($value));
    if (strlen($email)>254 || !filter_var($email,FILTER_VALIDATE_EMAIL)) throw new MemoryProblem('Informe um e-mail válido.');
    [$local,$domain]=explode('@',$email,2);
    $canonical=$email;
    // Gmail aliases share the same attempt allowance. Delivery still uses the supplied address.
    if (in_array($domain,['gmail.com','googlemail.com'],true)) $canonical=str_replace('.','',explode('+',$local,2)[0]).'@gmail.com';
    return [$email,hash('sha256',$canonical)];
}
function memory_rate(mysqli $db,string $bucket,int $max): void {
    $key=hash('sha256',MEMORY_CAMPAIGN.'|'.$bucket);$day=gmdate('Y-m-d');
    memory_sql($db,'INSERT IGNORE INTO promo_memory_rates (bucket,day,hits) VALUES (?,?,0)','ss',[$key,$day]);
    $row=memory_sql($db,'SELECT hits FROM promo_memory_rates WHERE bucket=? AND day=? FOR UPDATE','ss',[$key,$day])->get_result()->fetch_assoc();
    if ((int)$row['hits'] >= $max) throw new MemoryProblem('Limite de envios atingido. Tente novamente amanhã.',429);
    memory_sql($db,'UPDATE promo_memory_rates SET hits=hits+1 WHERE bucket=? AND day=?','ss',[$key,$day]);
}
function memory_mail(string $to,string $subject,string $body): void {
    $base=dirname(__DIR__);
    if (is_file($base.'/vendor/autoload.php')) require_once $base.'/vendor/autoload.php';
    if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
        foreach (['Exception','PHPMailer','SMTP'] as $class) {
            $path=$base.'/PHPMailer/PHPMailer/'.$class.'.php';
            if (!is_file($path)) $path=$base.'/PHPMailer/src/'.$class.'.php';
            if (!is_file($path)) throw new RuntimeException('Mail library missing');
            require_once $path;
        }
    }
    $user=memory_env('SMTP_USERNAME');$pass=memory_env('SMTP_PASSWORD');
    if ($user==='' || $pass==='') throw new RuntimeException('SMTP unavailable');
    $mail=new \PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();$mail->Host=memory_env('SMTP_HOST','smtp.gmail.com');$mail->SMTPAuth=true;
    $mail->Username=$user;$mail->Password=$pass;$mail->Port=(int)memory_env('SMTP_PORT','587');
    $mail->SMTPSecure=$mail->Port===465?'ssl':'tls';$mail->Timeout=15;$mail->SMTPDebug=0;
    $mail->CharSet='UTF-8';$mail->setFrom(memory_env('SMTP_FROM_EMAIL',$user),memory_env('SMTP_FROM_NAME','Tech Tecnologia'));
    $mail->addAddress($to);$mail->isHTML(false);$mail->Subject=$subject;$mail->Body=$body;
    try {$mail->send();}
    catch(\PHPMailer\PHPMailer\Exception $e) {
        $detail=strtolower($e->getMessage().' '.$mail->ErrorInfo);
        $smtpCode=(string)($mail->getSMTPInstance()->getError()['smtp_code']??'');
        error_log('Memory SMTP delivery failed; smtp_code='.$smtpCode);
        if (str_contains($detail,'authenticate') || $smtpCode==='535') throw new MemoryProblem('O servidor de e-mail recusou a autenticação. Confira o usuário SMTP e a senha de aplicativo na configuração do site.',503,'smtp_auth');
        if (str_contains($detail,'certificate') || str_contains($detail,'crypto')) throw new MemoryProblem('Não foi possível validar a conexão segura de e-mail. Confira os certificados TLS do PHP/servidor.',503,'smtp_tls');
        if (str_contains($detail,'quota') || str_contains($detail,'daily') || str_contains($detail,'limit exceeded')) throw new MemoryProblem('O provedor de e-mail atingiu o limite de envios. Tente novamente mais tarde.',503,'smtp_limit');
        if (str_contains($detail,'connect')) throw new MemoryProblem('Não foi possível conectar ao servidor de e-mail. Confira host, porta e acesso SMTP na hospedagem.',503,'smtp_connect');
        if (str_contains($detail,'invalid address') || str_contains($detail,'from address')) throw new MemoryProblem('O endereço remetente do site não está configurado corretamente. Confira SMTP_FROM_EMAIL.',503,'smtp_sender');
        throw new MemoryProblem('O provedor recusou o envio do código'.($smtpCode!==''?' (SMTP '.$smtpCode.')':'').'. Tente novamente mais tarde.',503,'smtp_delivery');
    }
}
function memory_rules(): string {
    return "50% de desconto somente no desenvolvimento de landing page, site ou sistema web, conforme escopo e valor da proposta. Domínio, hospedagem, licenças e serviços externos não estão incluídos. Cupom individual vinculado a este e-mail, não cumulativo, sem conversão em dinheiro e válido para uma única contratação confirmada até a data indicada. Limite de 3 partidas por e-mail nesta campanha, 80 jogadas por partida. Uma jogada corresponde a virar duas cartas. Vence quem encontrar os 25 pares dentro do limite. Sem limite de tempo por partida.";
}
function memory_player(mysqli $db,int $id,bool $lock=false): array {
    $row=memory_sql($db,'SELECT * FROM promo_memory_players WHERE id=? AND campaign=?'.($lock?' FOR UPDATE':''),'is',[$id,MEMORY_CAMPAIGN])->get_result()->fetch_assoc();
    if (!$row) throw new MemoryProblem('Confirme seu e-mail para participar.',401);
    return $row;
}
function memory_identity(): int {
    $id=(int)($_SESSION['memory_player']??0);
    if (!$id) throw new MemoryProblem('Confirme seu e-mail para participar.',401);
    return $id;
}
function memory_new_game(): array {
    $board=array_merge(range(0,MEMORY_PAIRS-1),range(0,MEMORY_PAIRS-1));
    for($i=count($board)-1;$i>0;$i--) {$j=random_int(0,$i);[$board[$i],$board[$j]]=[$board[$j],$board[$i]];}
    return ['board'=>$board,'matched'=>[],'open'=>[],'moves'=>0,'status'=>'active','revision'=>bin2hex(random_bytes(16)),'settle_at'=>0];
}
function memory_apply_flip(array &$game,int $position): void {
    if ($game['status']!=='active') throw new MemoryProblem('Esta partida já terminou. Atualize o jogo.',409);
    if ($position<0 || $position>=50 || in_array($position,$game['matched'],true) || in_array($position,$game['open'],true)) throw new MemoryProblem('Escolha uma carta fechada.');
    if (count($game['open'])===2) throw new MemoryProblem('Aguarde as cartas fecharem.',409);
    $game['open'][]=$position;
    if (count($game['open'])===2) {
        $game['moves']++;
        [$a,$b]=$game['open'];
        if ($game['board'][$a]===$game['board'][$b]) {
            $game['matched']=array_merge($game['matched'],[$a,$b]);$game['open']=[];
            if (count($game['matched'])===50) $game['status']='won';
        } else $game['settle_at']=microtime(true)+1.2;
        if ($game['status']!=='won' && $game['moves']>=MEMORY_MOVES) $game['status']='lost';
    }
    $game['revision']=bin2hex(random_bytes(16));
}
function memory_game_save(mysqli $db,int $id,array $game): void {
    memory_sql($db,'UPDATE promo_memory_players SET game_state=? WHERE id=?','si',[json_encode($game,JSON_THROW_ON_ERROR),$id]);
}
function memory_coupon_state(array $row): string {
    if ($row['coupon_status']==='active' && memory_time($row['expires_at'])<=time()) return 'expired';
    return $row['coupon_status'];
}
function memory_view(array $row): array {
    $game=json_decode($row['game_state']??'null',true);
    $data=['verified'=>true,'email'=>$row['email'],'games_used'=>(int)$row['games_used'],'games_left'=>max(0,MEMORY_GAMES-(int)$row['games_used']),
      'max_moves'=>MEMORY_MOVES,'pairs'=>MEMORY_PAIRS,'can_manage'=>memory_admin(),'coupon'=>null,'game'=>null];
    if ($game) {
        $visible=[];
        // Never send the complete hidden deck to the visitor.
        foreach(array_unique(array_merge($game['matched'],$game['open'])) as $pos) $visible[]=['position'=>$pos,'symbol'=>$game['board'][$pos]];
        $data['game']=['status'=>$game['status'],'moves'=>$game['moves'],'matched'=>$game['matched'],'open'=>$game['open'],'visible'=>$visible,
          'revision'=>$game['revision'],'settle_after_ms'=>max(0,(int)ceil(($game['settle_at']-microtime(true))*1000))];
    }
    if ($row['coupon']) $data['coupon']=['code'=>$row['coupon'],'status'=>memory_coupon_state($row),'expires_at'=>$row['expires_at'].'Z',
      'sent'=>(bool)$row['mail_sent'],'mail_attempts'=>(int)$row['mail_attempts']];
    return $data;
}
function memory_send_coupon(mysqli $db,int $id): bool {
    $db->begin_transaction();
    try {
        $row=memory_player($db,$id,true);
        if (!$row['coupon'] || memory_coupon_state($row)!=='active') throw new MemoryProblem('Não há cupom ativo para reenviar.');
        if ((int)$row['mail_attempts']>=5 || memory_time($row['mail_last'])>time()-60) throw new MemoryProblem('Aguarde 1 minuto entre envios. São permitidos até 5 envios do voucher.',429);
        memory_sql($db,'UPDATE promo_memory_players SET mail_attempts=mail_attempts+1,mail_last=? WHERE id=?','si',[memory_now(),$id]);
        $db->commit();
    } catch(Throwable $e) {$db->rollback();throw $e;}
    $zone=new DateTimeZone('America/Sao_Paulo');
    $expires=(new DateTimeImmutable($row['expires_at'],new DateTimeZone('UTC')))->setTimezone($zone)->format('d/m/Y \à\s H:i');
    try {
        memory_mail($row['email'],'Seu voucher de 50% — Tech Tecnologia',"Parabéns! Você encontrou os 25 pares.\n\nCupom: ".$row['coupon']."\nDesconto: 50% no desenvolvimento do projeto.\nVálido até: ".$expires." (horário de Brasília).\n\n".memory_rules()."\n\nSolicite sua proposta: https://felippeandreatadev.page.gd/index.php#formulario\nApresente este cupom e o e-mail confirmado ao Felippe antes de fechar a contratação.");
        memory_sql($db,'UPDATE promo_memory_players SET mail_sent=1 WHERE id=?','i',[$id]);return true;
    } catch(Throwable $e) {error_log('Memory voucher email unavailable: '.get_class($e));return false;}
}
