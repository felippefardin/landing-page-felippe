-- A página cria esta tabela automaticamente. Se o usuário do banco não tiver
-- permissão CREATE, importe este SQL antes de usar a validação.
CREATE TABLE IF NOT EXISTS promo_memory_usage_mail (
 player_id BIGINT UNSIGNED PRIMARY KEY,
 sent_at DATETIME DEFAULT NULL,
 attempts INT UNSIGNED NOT NULL DEFAULT 0,
 last_attempt DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
