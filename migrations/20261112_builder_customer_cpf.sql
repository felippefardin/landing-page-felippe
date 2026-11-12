ALTER TABLE builder_orders
  ADD COLUMN customer_cpf VARCHAR(14) NULL AFTER customer_name;

-- Pedidos novos passam a exigir CPF na aplicação. O NULL preserva contratos
-- antigos sem alterar o texto e o hash que já foram assinados.
