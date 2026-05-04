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
    endereco VARCHAR(100) NOT NULL,
    logradouro VARCHAR(255),
	faixa_preco VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    senha VARCHAR(255)
);

ALTER TABLE clinicas 
ADD senha VARCHAR(255);
  
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_agendamento DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE servicos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	botox char(30) not null
);

SELECT * FROM usuarios;
SELECT * FROM clinicas;
SELECT senha FROM clinicas;

DROP DATABASE sistemaBruma;
DROP TABLE agendamento;
DROP TABLE clinicas;