CREATE DATABASE IF NOT EXISTS mercado;
USE mercado;

CREATE TABLE IF NOT EXISTS contatos_mercado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS imagens_site (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) UNIQUE,
    caminho VARCHAR(255) NOT NULL
);


CREATE TABLE recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    codigo VARCHAR(6) NOT NULL,
    expira_em DATETIME NOT NULL,
    criado_em DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS formularios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS campos_formulario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    formulario_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
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




