<?php
declare(strict_types=1);

/** Use the site's authenticated owner session, independently of the memory game. */
function bc_admin(): bool {
    if(empty($_SESSION['usuario']))return false;
    $owner=strtolower(trim(memory_env('SITE_ADMIN_EMAIL',memory_env('DISCOUNT_ADMIN_EMAIL','felippefardin@gmail.com'))));
    if(!filter_var($owner,FILTER_VALIDATE_EMAIL))return false;
    $db=db();$row=null;
    if(!empty($_SESSION['usuario_id'])){
        $row=memory_sql($db,'SELECT id,nome,email FROM usuarios WHERE id=?','i',[(int)$_SESSION['usuario_id']])->get_result()->fetch_assoc();
        if(!$row)return false;
    }elseif(!empty($_SESSION['usuario_email']) && is_string($_SESSION['usuario_email'])){
        $rows=memory_sql($db,'SELECT id,nome,email FROM usuarios WHERE email=? LIMIT 2','s',[$_SESSION['usuario_email']])->get_result()->fetch_all(MYSQLI_ASSOC);
        if(count($rows)!==1)return false;$row=$rows[0];
    }else{
        // Older password logins only stored the display name. Upgrade an unambiguous
        // authenticated session without forcing the owner to sign in again.
        if(!is_string($_SESSION['usuario']))return false;
        $rows=memory_sql($db,'SELECT id,nome,email FROM usuarios WHERE nome=? LIMIT 2','s',[$_SESSION['usuario']])->get_result()->fetch_all(MYSQLI_ASSOC);
        if(count($rows)!==1)return false;$row=$rows[0];
    }
    // Reject stale identity fields left by older login handlers when accounts switch.
    if(!is_string($_SESSION['usuario']) || !hash_equals((string)$row['nome'],$_SESSION['usuario']))return false;
    // Match the exact configured mailbox, never a display name or a Gmail alias.
    if(!hash_equals($owner,strtolower(trim((string)$row['email']))))return false;
    $_SESSION['usuario_id']=(int)$row['id'];$_SESSION['usuario_email']=$row['email'];
    return true;
}

function bc_admin_db(): mysqli {
    if(!bc_admin())throw new RuntimeException('Acesso restrito ao administrador.');
    try{$db=bc_db();br_install($db);return $db;}
    catch(mysqli_sql_exception $e){
        if((int)$e->getCode()!==1146 || !str_contains($e->getMessage(),'builder_orders'))throw $e;
        // Initialize the module only for an authenticated owner. Existing data is untouched.
        $schema=file_get_contents(dirname(__DIR__).'/migrations/20261110_site_builder.sql');
        if($schema===false)throw new RuntimeException('Arquivo de instalação do módulo ausente.');
        $db=db();$db->multi_query($schema);
        do{if($result=$db->store_result())$result->free();}while($db->more_results() && $db->next_result());
        br_install($db);return bc_db();
    }
}

function br_install(mysqli $db): void {
    $schema=file_get_contents(dirname(__DIR__).'/migrations/20261110_builder_refunds.sql');
    if($schema===false)throw new RuntimeException('Migração de reembolsos ausente.');
    $db->query($schema);
}
