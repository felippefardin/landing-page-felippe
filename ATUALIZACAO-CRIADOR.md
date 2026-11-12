# Atualização do criador de sites

## Correção da prévia — revisão 2

A prévia agora aceita seções ainda vazias e campos de contato parcialmente digitados. Endereços incompletos ficam temporariamente sem link. A conferência obrigatória do conteúdo, contatos e contraste permanece no teste final e na compra.

Alterações em textos, seletores, cores, seleção e ordem de seções atualizam a prévia. Requisições antigas são canceladas e suas respostas não substituem versões mais recentes. O JavaScript usa uma nova versão de cache.

Substitua todos os arquivos deste pacote, incluindo `builder-api.php`, `criar-site.php`, `js/criador-site.js` e os arquivos de `src`. Depois use Ctrl+F5. Corrigir somente o JavaScript não aplica a correção de validação no servidor.

Os testes adicionais verificaram digitação, eventos de mudança, seleção de seções, descarte de respostas antigas, seções vazias, contatos incompletos e manutenção da validação final. A interface real no navegador permanece sem conferência visual neste ambiente.

Copie o conteúdo deste pacote para a raiz do projeto, mantendo as pastas e substituindo os arquivos correspondentes. No InfinityFree, envie esses mesmos arquivos para `htdocs`. Atualize a página com Ctrl+F5.

A aplicação não foi feita na pasta original: o acesso nesta sessão é somente de leitura. O script `APLICAR.ps1` permite aplicar localmente, guardando uma cópia dos arquivos anteriores. Ele não altera o banco nem as configurações de pagamento.

## O cliente poderá escolher

- Sobre, serviços, benefícios, portfólio, equipe, depoimentos, perguntas frequentes e contato, na ordem desejada.
- Até três itens por seção de serviços, benefícios, portfólio, equipe, depoimentos e perguntas frequentes.
- Logo, foto de apresentação, três imagens de portfólio, três fotos de equipe e imagem para compartilhamento. As imagens são otimizadas no navegador.
- Cor de destaque, fundo e texto, quatro fontes e tema claro, escuro ou conforme o dispositivo.
- WhatsApp, Instagram, Facebook, LinkedIn, YouTube, e-mail e localização por link do Google Maps.
- Título e descrição para buscadores. Para compartilhar com a imagem correta, preencha o endereço HTTPS definitivo do site.

O visitante terá controles de tema, tamanho de fonte, navegação por teclado e link para pular ao conteúdo. O editor verifica o contraste entre texto e fundo. Esta validação não substitui uma auditoria completa de acessibilidade dos conteúdos e imagens inseridos.

As seções novas começam desmarcadas e sem conteúdo comercial fictício. Para habilitar uma delas, preencha pelo menos um item. Use somente depoimentos reais e autorizados. O formulário acompanha a seção Contato na posição escolhida; quando Contato estiver desmarcado, o formulário não aparecerá.

## Entrega ao comprador

O fluxo existente continua gerando o ZIP do projeto antes de criar o pedido. O acesso ao ZIP permanece sujeito à confirmação do pagamento e às regras existentes de entrega e reembolso. O ZIP inclui a página com as seções escolhidas, imagens pertinentes, CSS, `site.js`, PHP, SQL, painel e instruções de instalação. As mensagens do formulário são armazenadas no banco do site do cliente e consultadas no painel desse site.

O painel entregue permite editar os novos textos e contatos, aparência, títulos para buscadores, seções e ordem. Para trocar imagens após instalar, substitua os arquivos correspondentes em `assets`, mantendo nome e formato, conforme o guia.

Não é necessária nova migração no banco do criador: as escolhas são incluídas no ZIP e no SQL inicial do site do cliente. Pacotes de compras anteriores permanecem como foram gerados.

## Validação

Foram verificadas as 256 combinações de seções na prévia e no HTML final, modelos, temas e fontes, entradas inválidas, escape de conteúdo, arquivos do ZIP, imagens condicionais, SQL inicial e renderizador exportado. Os PHP passaram na verificação de sintaxe e os JavaScript na análise de sintaxe.

O ambiente bloqueou o navegador de teste. A conferência visual, a instalação em MySQL, o envio de mensagens pelo navegador e uma compra real ainda precisam ser conferidos em homologação. Nenhuma cobrança foi realizada nesta sessão.
