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
    codigo INT PRIMARY KEY AUTO_INCREMENT ,
    nome VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    endereco VARCHAR(100) NOT NULL
);

  
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_agendamento DATETIME DEFAULT CURRENT_TIMESTAMP
);
create table servicos
( 
botox char(30) not null
);

SELECT * FROM usuarios;

drop database sistemaBruma;
drop table agendamento;