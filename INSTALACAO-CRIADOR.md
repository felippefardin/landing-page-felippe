# Criador de páginas — instalação e operação

Este pacote adiciona o criador ao projeto PHP existente. Inclui os ajustes anteriores de avaliações, acessibilidade, rodapé e cupons. Faça backup antes de substituir os arquivos, mantendo as subpastas.

## O que foi implementado

- Editor sem cadastro com três modelos, cor, logo, imagem, textos, serviços, contatos e ordem/visibilidade de seções; prévia para computador e celular.
- Exportação completa em ZIP: `index.php`, `contato.php`, painel próprio em `admin`, código de servidor em `app`, `banco.sql`, `style.css`, imagens e instruções de instalação. O site exportado requer PHP 8.2+ e MySQL/MariaDB, com PDO MySQL, mbstring e sessões. O cliente importa o SQL na própria hospedagem e cria seu administrador pelo instalador protegido; não recebe credenciais da plataforma. O formulário salva mensagens no banco e o painel permite lê-las e editar textos, contatos e cor. Não é WordPress nem um sistema personalizado de loja ou área de membros.
- Página por R$ 350. Suporte **opcional e desmarcado**: R$ 150 por até 2 horas. O cupom de 15% aplica-se só à página: R$ 297,50 sem suporte ou R$ 447,50 com suporte.
- Termo com valores e versão fixados; aceite eletrônico simples por código enviado ao e-mail, com validade de 10 minutos, limite de tentativas e registro de integridade. Não usa certificado ICP-Brasil nem cria PDF com assinatura qualificada.
- Checkout Pro do Mercado Pago sem exigir conta no seu site. O provedor pode solicitar identificação e dados do pagamento.
- Liberação somente após consultar o provedor e conferir referência do pedido, conta recebedora, valor, moeda e ambiente. Os parâmetros de retorno do navegador nunca aprovam uma compra.
- Reserva de cupom ao iniciar checkout e utilização definitiva somente após aprovação. Download não disponível para pagamentos pendentes ou revogados.
- E-mail com ZIP anexado, link temporário e PDF do termo. Se houve cupom, a mensagem também confirma seu uso único e invalidação.
- Link válido por 7 dias a partir da confirmação do pagamento pelo sistema. A tarefa agendada exclui os arquivos temporários. O e-mail na caixa do cliente permanece sob controle dele.
- Painel `documentos-assinados.php`, com busca por nome/e-mail/pedido, download de PDF e andamento do suporte. O dono recebe aviso por e-mail quando o suporte opcional é comprado.

## Hospedagem necessária

O plano gratuito da InfinityFree usa uma barreira de navegador que pode bloquear notificações externas e não é adequado para depender de tarefas em segundo plano. Não ative vendas automáticas nesse ambiente sem resolver essa limitação. Use hospedagem PHP compatível com HTTPS, webhook público e cron de CLI.

Requisitos: PHP 8.2+, MySQL/MariaDB com InnoDB, extensões mysqli, curl, mbstring, openssl, zip, dom e gd; saída HTTPS para a API do Mercado Pago e SMTP autenticado. Reaproveita as pastas `dompdf` e `PHPMailer` já instaladas no seu projeto. Configure o limite de requisição para pelo menos 6 MB e memória suficiente para gerar o ZIP e o PDF. Até duas imagens de 2 MB são aceitas.

## Banco e configurações

1. Importe `migrations/20261110_site_builder.sql` no mesmo banco do site. As tabelas `promo_memory_players` e `promo_memory_rates` do jogo já devem existir. A importação não modifica os cupons existentes.
2. Copie as variáveis de `builder-config.example.env` para o `.env` atual. Não sobrescreva os dados do banco ou SMTP.
3. Gere uma chave aleatória para `BUILDER_APP_KEY`, por exemplo com `php -r "echo bin2hex(random_bytes(32));"`. Guarde-a em local seguro; alterar a chave invalida os links de download ainda ativos.
4. Defina `BUILDER_BASE_URL` como o endereço HTTPS completo da pasta em que o projeto será publicado, sem barra final.
5. Configure `MP_ACCESS_TOKEN`, o ID da conta recebedora em `MP_COLLECTOR_ID` e o segredo em `MP_WEBHOOK_SECRET`. Use credenciais de teste primeiro e `MP_MODE=test`.
6. No painel de desenvolvedor do Mercado Pago, cadastre o evento **Pagamentos** com a URL HTTPS `https://SEU-DOMINIO/pagamento-webhook.php`. O endpoint valida a assinatura HMAC e consulta a API antes de processar a aprovação. A notificação deve incluir `data.id` na URL, como enviado pelo provedor.
7. Configure `BUILDER_CONTACT_EMAIL` para o atendimento e avisos de suporte. Confira também remetente, usuário, senha e porta SMTP.

Proteja o `.env` contra acesso HTTP. Em Apache, aproveite a configuração existente e acrescente, se necessário, sem remover suas regras:

```apache
<FilesMatch "^\.env">
    Require all denied
</FilesMatch>
```

Mantenha `display_errors=Off` em produção; registre erros em local protegido. Faça backup do banco e dos termos preservados, e considere esses dados ao definir a política de retenção e de exclusão dos backups.

## Tarefa agendada obrigatória

Execute a cada minuto, com os caminhos absolutos correspondentes à hospedagem:

```text
php /caminho/do/projeto/scripts/builder-worker.php
```

No cron Linux, por exemplo:

```cron
* * * * * /usr/bin/php /caminho/do/projeto/scripts/builder-worker.php
```

O script aceita somente CLI. Reconcilia pagamentos, envia ZIP/PDF, avisa o dono sobre suporte contratado e exclui ZIPs com prazo encerrado. Há até 10 tentativas automáticas para a entrega e até 5 para o aviso de suporte, com intervalo. Em erro persistente, corrija a configuração antes de reprocessar a fila. A exclusão física ocorre na execução seguinte ao vencimento; o acesso ao download já é bloqueado no horário de expiração.

Rascunhos sem checkout retêm o ZIP por até 24 horas; compras pendentes podem reter o arquivo por até 30 dias para processar aprovação assíncrona. O prazo de 7 dias é contado após a confirmação. Arquivos já expirados não são reconstituídos pelo painel.

## Cupons e exceções de pagamento

Cupons ficam **reservados** durante o checkout. Essa reserva não é liberada automaticamente por simples abandono da página, pois um pagamento pendente ainda pode ser aprovado. Em uma compra abandonada, confira todas as tentativas no Mercado Pago antes de encerrar o pedido ou liberar o cupom manualmente; não reutilize um cupom reservado só por ter passado algum tempo.

Uma segunda aprovação para o mesmo pedido não sobrescreve a primeira, não consome outro cupom e não gera outra entrega. O evento é registrado para conferência; é necessário avaliar eventual cobrança duplicada diretamente no provedor. Reembolsos e contestações bloqueiam futuros downloads; arquivos já baixados ou enviados por e-mail não podem ser recolhidos. Cupons utilizados não são reativados automaticamente em reembolso.

Se um pagamento chegar depois de o arquivo temporário ter sido removido, o pedido fica em **Requer conferência**. O responsável deve tratar a entrega ou o reembolso com o cliente. Não há retorno automático de dinheiro por este módulo.

## Ativação e validação no servidor

1. Mantenha `MP_MODE=test`; preencha os demais campos e habilite `BUILDER_ENABLED=1` apenas no ambiente de teste compatível.
2. Faça uma compra de teste com usuário de teste do provedor. Confira o aceite, os valores, as notificações, o ZIP e o PDF. Não use um pagamento real como simulação.
3. Teste sem suporte (R$ 350), com suporte (R$ 500), com cupom (R$ 297,50) e com cupom + suporte (R$ 447,50).
4. Confira o aviso ao dono no caso de suporte, a utilização do cupom e o acesso restrito aos PDFs no painel.
5. Valide o webhook pelo painel do provedor e acompanhe a execução do cron. Teste o prazo com um pedido de teste separado.
6. Só depois use credenciais de produção, o segredo correspondente e `MP_MODE=production`. A ativação requer dados reais de configuração, que não estão neste pacote.

O acesso ao arquivo de documentos usa a sessão do administrador já autenticado no site e não depende da confirmação do jogo. A conta é identificada pelo e-mail de SITE_ADMIN_EMAIL, ou DISCOUNT_ADMIN_EMAIL quando o primeiro não estiver definido (padrão: felippefardin@gmail.com). Sessões antigas com nome único são atualizadas automaticamente com ID e e-mail, sem novo login. Contas comuns continuam sem acesso a documentos. O cliente não precisa criar conta. O código recebido pelo cliente assina apenas o termo daquela compra.

## Evidência e limitações dos testes locais

O fluxo foi exercitado em banco MariaDB isolado, com respostas simuladas do provedor e transporte de e-mail de teste: preços, cupom associado ao e-mail, reserva, aprovação, duplicidade, rejeição de valores/conta/moeda/ambiente incorretos, assinatura por OTP, PDF, falha/reenvio de entrega, expiração do download e bloqueio por reembolso. A interface foi conferida no navegador para personalização, troca de modelo, ordem das seções, prévia móvel e suporte opcional.

Nenhuma cobrança real foi feita e nenhum e-mail de compra deste módulo foi enviado a um cliente real. A homologação com Mercado Pago, SMTP, webhook e cron reais permanece obrigatória antes das vendas. O texto do termo é uma base para a operação descrita; revise a identificação do fornecedor, atendimento e política de dados conforme sua atividade antes de publicá-lo.

Documentação de referência: [Checkout Pro](https://www.mercadopago.com.br/developers/pt/reference/online-payments/checkout-pro-preferences/overview), [notificações](https://www.mercadopago.com.br/developers/pt/docs/checkout-pro-preferences/payment-notifications), [limitações da InfinityFree](https://forum.infinityfree.com/t/browser-security-system-features-and-limitations/49353), [tipos de assinatura eletrônica](https://www.gov.br/governodigital/pt-br/identidade/assinatura-eletronica/saiba-mais-sobre-a-assinatura-eletronica).
