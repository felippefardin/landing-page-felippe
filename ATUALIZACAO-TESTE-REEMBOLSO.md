# Atualização: teste antes da compra e reembolsos

## Instalar na hospedagem existente

1. Faça backup dos arquivos e do banco. Este pacote contém o módulo e os ajustes acumulados; mantenha as demais pastas do projeto, o `.env`, PHPMailer e dompdf existentes.
2. Envie os arquivos preservando as subpastas, substituindo as versões anteriores.
3. No phpMyAdmin, selecione o banco da aplicação e importe `migrations/20261110_builder_refunds.sql`. É uma criação de tabela sem apagar dados. Alternativamente, a abertura da área de reembolsos pelo proprietário autenticado inicializa a tabela com `CREATE TABLE IF NOT EXISTS`. Para uma instalação nova, importe também as migrações anteriores.
4. Mantenha a tarefa `scripts/builder-worker.php` configurada a cada minuto. Ela confere pagamentos, envia entregas e confirmações e remove arquivos expirados. Não executa devoluções financeiras automaticamente: o proprietário executa a devolução no painel.
5. Confirme credenciais do Mercado Pago, assinatura de webhook, HTTPS, e-mail SMTP e `BUILDER_BASE_URL`. Não use credenciais de produção durante testes. Não ative vendas enquanto essa configuração e a infraestrutura descrita em `INSTALACAO-CRIADOR.md` estiverem pendentes.
6. Entre como proprietário e abra **Reembolsos** no painel. A área usa o login existente e a verificação de proprietário, sem confirmação adicional pelo jogo.
7. Atualize com Ctrl + F5.

## Fluxo do cliente

- **Testar meu site gratuitamente antes de comprar** abre a versão atual na prévia. É possível navegar e simular o formulário; nenhuma mensagem é enviada ou salva. A demonstração não executa o PHP/painel/SQL da futura hospedagem. A entrega continua sendo o pacote PHP/SQL completo. Alterar o projeto exige testar novamente.
- Para continuar, o cliente confirma a conferência e lê o termo. O teste não é renúncia a direitos. O suporte de R$ 150 permanece opcional e não recebe desconto do cupom.
- O e-mail de entrega inclui ZIP, PDF, link temporário de download e um **link separado de acompanhamento/reembolso**, que continua acessível depois de vencer o download. Não compartilhar esse link: ele permite acompanhar e cancelar o pedido.
- Após pagamento, a página do pedido apresenta o prazo e a solicitação de arrependimento, com motivo opcional e confirmação explícita do cancelamento. O atendimento continua disponível para falhas e outros direitos após a janela de arrependimento.
- Ao solicitar, o pedido passa a **Cancelamento solicitado**. Todos os links de novos downloads são bloqueados imediatamente, inclusive o enviado no e-mail. Arquivos já baixados ou anexados ao e-mail não podem ser revogados. Downloads já iniciados também não podem ser recolhidos.
- Cliente e responsável recebem confirmação da solicitação, separada da confirmação de dinheiro devolvido. Falhas de SMTP ficam pendentes para nova tentativa.

## Operar no painel

1. Abra **Reembolsos** e confira cliente, pedido, data, valor integral e situação do pagamento. Atenda os pedidos tempestivos sem demora; o painel não aplica recusa por download ou suporte.
2. Marque a confirmação do valor e clique em **Executar reembolso integral**. Isso envia uma operação financeira real quando credenciais de produção estiverem configuradas. A devolução inclui o valor pago pelo site e o suporte opcional contratado.
3. O sistema consulta o pagamento, usa a API `/v1/payments/{id}/refunds` e uma chave estável para impedir repetição da mesma devolução. Não muda para “reembolsado” só porque o botão foi clicado.
4. Se houver timeout, use **Conferir provedor / avisos pendentes**. Se repetir pelo sistema, a mesma chave é mantida. Não faça devolução paralela manual sem conferir o provedor.
5. O pagamento consultado e validado precisa confirmar a devolução integral para a situação final aparecer. O prazo para o crédito na conta/fatura depende da instituição e do meio de pagamento. O cliente recebe um novo e-mail de confirmação; o worker acompanha pendências.

## Contrato e prazos

Versão nova: `2026-11-10-v3-refunds`. Contratos antigos continuam com seu texto e hash originais; não são reescritos nem assinados novamente automaticamente.

A cláusula destaca o art. 49 do Código de Defesa do Consumidor (Lei nº 8.078/1990) e o art. 5º do Decreto nº 7.962/2013, normas aplicadas pelos órgãos de defesa do consumidor, como o Procon. Não existe uma “lei do Procon” que elimine todos os direitos após sete dias.

Após a janela, a oferta não prevê devolução por **simples arrependimento**, preservando direitos por falhas, vícios e descumprimento da oferta. O sistema usa a data mais recente entre disponibilização após pagamento e envio da entrega, até o fim do sétimo dia no horário de Brasília. Falhas de entrega e situações legais específicas devem ser avaliadas pelo atendimento. O prazo do download é uma regra distinta e não extingue direitos.

O destaque aparece antes do aceite e no PDF. Faça revisão jurídica do texto para sua operação antes de ativar vendas.

Referências oficiais consultadas:
- https://www.planalto.gov.br/ccivil_03/leis/l8078compilado.htm
- https://www.planalto.gov.br/ccivil_03/_ato2011-2014/2013/decreto/d7962.htm
- https://www.mercadopago.com.br/developers/pt/reference/online-payments/checkout-pro-preferences/create-refund/post

## Validação feita

Testes locais com banco isolado e pagamento/SMTP simulados: aceite, preços, suporte opcional, cupom só sobre o site, bloqueio sem teste, cancelamento, link antigo bloqueado, repetição de solicitação, webhook atrasado, restrição do administrador, timeout, repetição idempotente, confirmação pelo provedor e preservação do contrato/PDF. O formulário simulado também foi conferido no navegador. Nenhuma cobrança ou devolução real foi executada.
