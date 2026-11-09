<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

function review_escape($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function review_validate(array $input): array {
    foreach (['nome', 'comentario', 'estrelas'] as $key) {
        if (!isset($input[$key]) || !is_string($input[$key])) throw new InvalidArgumentException('Preencha nome, comentário e estrelas.');
    }
    $name = trim($input['nome']); $comment = trim($input['comentario']);
    $length = static fn(string $v): int => preg_match_all('/./us', $v) ?: 0;
    if ($length($name) < 2 || $length($name) > 100) throw new InvalidArgumentException('O nome deve ter de 2 a 100 caracteres.');
    if ($length($comment) < 10 || $length($comment) > 2000) throw new InvalidArgumentException('O comentário deve ter de 10 a 2.000 caracteres.');
    if (!preg_match('/^[1-5]$/D', $input['estrelas'])) throw new InvalidArgumentException('Selecione de 1 a 5 estrelas.');
    return [$name, $comment, (int)$input['estrelas']];
}
function review_schema(): void {
    // Existing tables do not require CREATE permission on every request.
    try {
        db()->query('SELECT id FROM site_avaliacoes LIMIT 0');
        return;
    } catch (mysqli_sql_exception $e) {
        if ((int)$e->getCode() !== 1146) throw $e;
    }
    db()->query("CREATE TABLE IF NOT EXISTS site_avaliacoes (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(100) NOT NULL, comentario TEXT NOT NULL,
      estrelas TINYINT UNSIGNED NOT NULL,
      status ENUM('pendente','aprovada','desativada') NOT NULL DEFAULT 'pendente',
      criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      legado_id BIGINT UNSIGNED NULL UNIQUE,
      INDEX idx_publicas (status, criado_em)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    review_import_legacy();
}

function review_import_legacy(): void {
    if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') !== 'avaliacoes.php') return;
    try {
        $columns = array_column(db()->query('SHOW COLUMNS FROM avaliacoes')->fetch_all(MYSQLI_ASSOC), 'Field');
        if (array_diff(['id','nome','nota','mensagem','data_envio','aprovado'], $columns)) return;
        db()->query("INSERT INTO site_avaliacoes (legado_id,nome,comentario,estrelas,status,criado_em)
          SELECT old.id,LEFT(old.nome,100),old.mensagem,LEAST(5,GREATEST(1,COALESCE(old.nota,5))),
          CASE WHEN old.aprovado=1 THEN 'aprovada' ELSE 'pendente' END,COALESCE(old.data_envio,CURRENT_TIMESTAMP)
          FROM avaliacoes old WHERE NOT EXISTS (SELECT 1 FROM site_avaliacoes fresh WHERE fresh.legado_id=old.id)");
    } catch (Throwable $e) { error_log('Legacy review import: '.$e->getMessage()); }
}
