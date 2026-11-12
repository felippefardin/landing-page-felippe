# Atualização de CPF e tema visual

## Banco de dados existente

Antes de publicar esta versão em uma instalação que já possui pedidos, execute:

`migrations/20261112_builder_customer_cpf.sql`

A coluna permanece nula somente para contratos antigos. Novos pedidos exigem um CPF válido, armazenam o valor formatado e o incluem no texto do termo antes do cálculo do hash e da assinatura eletrônica.

## Identidade visual

O arquivo `css/theme-global.css` centraliza cores, superfícies, bordas, tipografia, campos, foco, tabelas e comportamento responsivo. O tema escuro passa a ser o padrão na primeira visita e continua integrado aos controles de acessibilidade existentes.

## Compatibilidade documental

Contratos assinados anteriormente não são reescritos. Isso preserva o texto e o hash original de cada documento.
