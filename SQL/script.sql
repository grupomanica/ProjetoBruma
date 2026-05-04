CREATE DATABASE sistemabruma;
USE sistemabruma;

-- USUÁRIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    sobrenome VARCHAR(100),
	data_nascimento DATE,
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    telefone VARCHAR(20)
);

-- CLÍNICAS
CREATE TABLE clinicas (
    id INT PRIMARY KEY AUTO_INCREMENT ,
    nome VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    logradouro VARCHAR(255),
	faixa_preco VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    senha VARCHAR(255)
);

CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinica_id INT NOT NULL,
    tipo_procedimento VARCHAR(100) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    sessoes INT,
    valor DECIMAL(10,2),
    duracao INT,

    FOREIGN KEY (clinica_id) REFERENCES clinicas(id)
);

CREATE TABLE horarios_disponiveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    servico_id INT NOT NULL,
    data_disponivel DATE NOT NULL,
    horario TIME NOT NULL,
    status ENUM('livre','ocupado') DEFAULT 'livre',

    FOREIGN KEY (servico_id) REFERENCES servicos(id)
);
  
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    clinica_id INT NOT NULL,
    servico_id INT NOT NULL,
    horario_id INT NOT NULL,

    valor DECIMAL(10,2),
    status_pagamento ENUM('pendente','pago') DEFAULT 'pendente',
    status_agendamento ENUM('pendente','confirmado','concluido','cancelado') DEFAULT 'pendente',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (clinica_id) REFERENCES clinicas(id),
    FOREIGN KEY (servico_id) REFERENCES servicos(id),
    FOREIGN KEY (horario_id) REFERENCES horarios_disponiveis(id)
);

SELECT * FROM usuarios;
SELECT * FROM clinicas;
SELECT senha FROM clinicas;

DROP DATABASE sistemaBruma;
DROP TABLE agendamentos;
DROP TABLE servicos;
DROP TABLE clinicas;

DESCRIBE servicos;