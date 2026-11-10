<?php
declare(strict_types=1);

// Loaded after memoria-desconto.php. Keep redemption and its notification durable.
function coupon_usage_schema(mysqli $db): void {
    memory_sql($db, "CREATE TABLE IF NOT EXISTS promo_memory_usage_mail (
      player_id BIGINT UNSIGNED PRIMARY KEY,
      sent_at DATETIME DEFAULT NULL,
      attempts INT UNSIGNED NOT NULL DEFAULT 0,
      last_attempt DATETIME DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function coupon_redeem(mysqli $db, int $id, string $expectedCode): array {
    if (!memory_admin()) throw new MemoryProblem('Acesso não autorizado.', 403);
    $db->begin_transaction();
    try {
        $row = memory_player($db, $id, true);
        if (!$row['coupon'] || !hash_equals($row['coupon'], $expectedCode)) {
            throw new MemoryProblem('O cupom informado não corresponde ao registro.');
        }
        if (memory_coupon_state($row) !== 'active') {
            throw new MemoryProblem('Este cupom já foi utilizado, expirou ou foi desativado. Nenhuma nova utilização foi registrada.');
        }
        memory_sql($db, "UPDATE promo_memory_players SET coupon_status='used',redeemed_at=?,redeemed_by=? WHERE id=? AND coupon_status='active'", 'ssi',
            [memory_now(), memory_env('DISCOUNT_ADMIN_EMAIL', 'felippefardin@gmail.com'), $id]);
        memory_sql($db, 'INSERT INTO promo_memory_usage_mail (player_id) VALUES (?)', 'i', [$id]);
        $db->commit();
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
    // SMTP failure must never reactivate a consumed coupon.
    return ['id' => $id, 'sent' => coupon_usage_send($db, $id)];
}

function coupon_usage_send(mysqli $db, int $id): bool {
    if (!memory_admin()) throw new MemoryProblem('Acesso não autorizado.', 403);
    $db->begin_transaction();
    try {
        $row = memory_player($db, $id, true);
        if ($row['coupon_status'] !== 'used') throw new MemoryProblem('O cupom ainda não foi utilizado.');
        $job = memory_sql($db, 'SELECT * FROM promo_memory_usage_mail WHERE player_id=? FOR UPDATE', 'i', [$id])->get_result()->fetch_assoc();
        if (!$job) throw new MemoryProblem('Não há confirmação pendente para este cupom.');
        if ($job['sent_at']) { $db->commit(); return true; }
        if ((int)$job['attempts'] >= 5 || memory_time($job['last_attempt']) > time() - 60) {
            throw new MemoryProblem('Aguarde 1 minuto entre envios. Limite de 5 tentativas de confirmação.', 429);
        }
        memory_sql($db, 'UPDATE promo_memory_usage_mail SET attempts=attempts+1,last_attempt=? WHERE player_id=?', 'si', [memory_now(), $id]);
        $db->commit();
    } catch (Throwable $e) { $db->rollback(); throw $e; }
    $date = (new DateTimeImmutable($row['redeemed_at'], new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('d/m/Y H:i');
    try {
        memory_mail($row['email'], 'Confirmação de utilização do cupom — Tech Tecnologia',
            "Olá!\n\nConfirmamos a utilização do seu vale-desconto.\n\nCupom: ".$row['coupon']."\nUtilizado em: ".$date." (horário de Brasília).\nSituação: UTILIZADO E INVALIDADO PARA NOVAS UTILIZAÇÕES.\n\nEste cupom é de uso único e não poderá ser utilizado novamente.\n\nEm caso de dúvida, entre em contato: https://felippeandreatadev.page.gd/index.php#formulario\n\nTech Tecnologia");
        memory_sql($db, 'UPDATE promo_memory_usage_mail SET sent_at=? WHERE player_id=?', 'si', [memory_now(), $id]);
        return true;
    } catch (Throwable $e) {
        error_log('Coupon usage confirmation failed: '.get_class($e));
        return false;
    }
}
