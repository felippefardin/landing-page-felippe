# Instalação do seu site completo

Você recebeu o código PHP do site e do painel, estilos CSS, imagens, `banco.sql` e esta documentação. Nenhuma credencial do fornecedor ou do sistema de pagamentos acompanha estes arquivos.

## Requisitos

Hospedagem com PHP 8.2 ou superior, MySQL/MariaDB, extensões PDO MySQL, mbstring e session, e HTTPS. Uma hospedagem apenas de arquivos estáticos não executará este site. Não é WordPress e não precisa de plugins.

## Passo a passo

1. Crie um banco MySQL e um usuário na sua hospedagem e conceda a ele acesso a esse banco.
2. Importe `banco.sql` pelo phpMyAdmin no banco criado. Ele cria as tabelas e insere os textos e opções escolhidos por você no criador. Não há usuário ou senha de administrador predefinidos.
3. Copie `app/config.example.php` para `app/config.php`. Preencha host, porta, nome do banco, usuário e senha com os dados da sua hospedagem. Preserve as chaves aleatórias que já acompanham essa configuração.
4. Envie `index.php`, `contato.php`, `instalar.php`, `style.css`, `site.js`, `.htaccess` e as pastas `app`, `admin` e `assets` (quando houver imagens) para a pasta pública do seu domínio (normalmente public_html, www ou htdocs). Não publique `banco.sql`, este guia nem cópias de backup do banco.
5. Ative HTTPS. Acesse `https://SEU-DOMINIO/instalar.php`. Informe a chave `setup_token` de `app/config.php`, escolha um usuário e uma senha forte de pelo menos 12 caracteres. O instalador cria somente o primeiro administrador e bloqueia novas instalações quando ele existe.
6. Remova `instalar.php` do servidor após concluir. Acesse `https://SEU-DOMINIO/admin/login.php` para editar textos e contatos e ler mensagens.
7. Abra o site, envie uma mensagem de teste e confira seu recebimento no painel. As mensagens ficam no seu banco; esta versão não dispara e-mails de contato automaticamente. Para responder, use o e-mail informado pelo visitante.

Em Apache, a pasta `app` inclui proteção por `.htaccess`. Em Nginx ou outro servidor, configure o bloqueio HTTP dessa pasta. Os arquivos PHP de configuração não devem ser disponibilizados como texto. Guarde as credenciais com segurança e desative `display_errors` em produção.

## O que o painel faz

- Login com senha protegida por hash, limites de tentativas, sessões e proteção CSRF.
- Alteração dos textos de todas as seções, contatos, cores, fonte, tema, título e descrição para buscadores.
- Seleção de seções e ordenação pelas posições no editor.
- Consulta de mensagens, com marcação como nova, lida ou arquivada.

O painel não inclui loja virtual, área de membros, pagamentos dos seus visitantes, cadastro público de usuários ou funcionalidades específicas de um sistema sob medida. Logo e foto podem ser substituídos pela hospedagem, conservando nomes e formatos dos arquivos em `assets`.

## Dados, backups e manutenção

Os dados do seu site ficam no banco da hospedagem que você contratou, sob sua administração. O formulário informa aos visitantes que os contatos são armazenados para atendimento. Revise o aviso e a política de privacidade de acordo com sua operação e mantenha o servidor atualizado.

Faça backup periódico do banco pelo phpMyAdmin e copie também os arquivos do site, a pasta assets e a configuração para um local seguro. Não coloque backups dentro de pastas públicas. A recuperação da senha administrativa requer acesso técnico ao banco/hospedagem; não há recuperação automática por e-mail nesta versão.

O ZIP da compra deve ser guardado por você. O link fornecido na compra fica disponível por 7 dias. O suporte para publicação somente está incluído se você contratou a opção adicional de R$ 150 por até 2 horas, mediante agendamento.
