<?php
// alerta.php

// Recebe as variáveis por GET, POST ou SESSION, ou defina manualmente antes do include
// Por exemplo:
// $tipo = 'sucesso'; $mensagem = 'Cadastro realizado com sucesso!';

// Aqui só para exemplo:
if (!isset($tipo)) $tipo = 'info';
if (!isset($mensagem)) $mensagem = 'Mensagem padrão do sistema.';

// Define as classes CSS e ícones conforme o tipo
$classes = [
    'sucesso' => 'alert-success',
    'erro' => 'alert-error',
    'aviso' => 'alert-warning',
    'info' => 'alert-info'
];

$icones = [
    'sucesso' => '✔️',
    'erro' => '❌',
    'aviso' => '⚠️',
    'info' => 'ℹ️'
];

$classe_alerta = $classes[$tipo] ?? $classes['info'];
$icone = $icones[$tipo] ?? $icones['info'];
?>

<style>
.alerta {
    padding: 15px 20px;
    border-radius: 5px;
    margin: 15px 0;
    position: relative;
    font-family: Arial, sans-serif;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    animation: fadeIn 0.5s ease forwards;
}
.alerta span.icone {
    font-size: 22px;
}
.alerta button.fechar {
    position: absolute;
    top: 8px;
    right: 10px;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: inherit;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}
.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(-10px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>

<div class="alerta <?php echo $classe_alerta; ?>">
    <span class="icone"><?php echo $icone; ?></span>
    <div class="mensagem"><?php echo htmlspecialchars($mensagem); ?></div>
    <button class="fechar" onclick="this.parentElement.style.display='none';" aria-label="Fechar alerta">&times;</button>
</div>
