CREATE TABLE IF NOT EXISTS site_avaliacoes (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(100) NOT NULL, comentario TEXT NOT NULL,
      estrelas TINYINT UNSIGNED NOT NULL,
      status ENUM('pendente','aprovada','desativada') NOT NULL DEFAULT 'pendente',
      criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      legado_id BIGINT UNSIGNED NULL UNIQUE,
      INDEX idx_publicas (status, criado_em)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
