CREATE DATABASE IF NOT EXISTS mercado;
USE mercado;

CREATE TABLE IF NOT EXISTS contatos_mercado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE avaliacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  nota INT CHECK (nota BETWEEN 1 AND 5),
  mensagem TEXT NOT NULL,
  data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  aprovado BOOLEAN DEFAULT FALSE
);


CREATE TABLE emails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100),
    mensagem TEXT,
    data_envio DATETIME,
    whatsapp VARCHAR(20),    
    respondida TINYINT(1)
);

CREATE TABLE exclusao_perfil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    codigo INT,
    data_envio TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    celular VARCHAR(20) NOT NULL
);
CREATE TABLE IF NOT EXISTS imagens_site (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) UNIQUE,
    caminho VARCHAR(255) NOT NULL
);

CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100),
    mensagem TEXT,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    agendamento DATETIME NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    codigo VARCHAR(6) NOT NULL,
    expira_em DATETIME NOT NULL,
    criado_em DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS formularios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS campos_formulario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    formulario_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    whatsapp VARCHAR(20) NOT NULL;
    email VARCHAR(100) NOT NULL;
    tipo VARCHAR(50) NOT NULL,  -- ex: text, email, number
    obrigatorio BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (formulario_id) REFERENCES formularios(id)
);

CREATE TABLE recuperacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    codigo VARCHAR(6) NOT NULL,
    expira_em DATETIME NOT NULL
);
CREATE TABLE respondidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    whatsapp VARCHAR(20),
    mensagem TEXT,
    data_envio DATETIME NOT NULL,
    data_resposta DATETIME NOT NULL
);

CREATE TABLE disponibilidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia VARCHAR(20) NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL
);