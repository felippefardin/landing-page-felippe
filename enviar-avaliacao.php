<?php
declare(strict_types=1);
require_once __DIR__ . '/src/reviews.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function review_response(int $code, string $message): void {
    http_response_code($code); echo json_encode(['ok'=>$code === 201,'message'=>$message], JSON_UNESCAPED_UNICODE); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Allow: POST'); review_response(405, 'Use o formulário de avaliação.'); }
$token = $_POST['csrf_token'] ?? null;
if (!is_string($token) || !hash_equals(csrf_token(), $token)) review_response(403, 'Atualize a página e tente novamente.');
if (!empty($_POST['website'])) review_response(400, 'Não foi possível enviar.');
if (($_POST['consentimento'] ?? '') !== '1') review_response(422, 'Autorize a publicação do seu nome e comentário.');
if (time() - (int)($_SESSION['review_last_sent'] ?? 0) < 60) review_response(429, 'Aguarde um minuto antes de enviar outra avaliação.');
try {
    [$name,$comment,$stars] = review_validate($_POST);
    review_schema();
    $stmt = db()->prepare('INSERT INTO site_avaliacoes (nome,comentario,estrelas) VALUES (?,?,?)');
    $stmt->bind_param('ssi',$name,$comment,$stars); $stmt->execute();
    $_SESSION['review_last_sent'] = time();
    review_response(201, 'Obrigado! Sua avaliação foi enviada e aguarda aprovação.');
} catch (InvalidArgumentException $e) { review_response(422,$e->getMessage()); }
catch (Throwable $e) { error_log('Review submission: '.$e->getMessage()); review_response(503,'Avaliações temporariamente indisponíveis. Tente novamente mais tarde.'); }
