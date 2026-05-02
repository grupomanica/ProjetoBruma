CREATE DATABASE sistemabruma;
USE sistemabruma;

-- USUÁRIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    telefone VARCHAR(20)
);

-- CLÍNICAS
CREATE TABLE clinicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    cnpj VARCHAR(20),
    cidade VARCHAR(100),
    bairro VARCHAR(100),
    faixa_preco VARCHAR(50)
);

-- SERVIÇOS
CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_clinica INT,
    FOREIGN KEY (id_clinica) REFERENCES clinicas(id),
    nome VARCHAR(100),
    descricao TEXT,
    valor DECIMAL(10,2),
    duracao INT
);

-- AGENDAMENTOS
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_servico INT,
    data DATE,
    hora TIME,
    nome_cliente VARCHAR(100),
    telefone VARCHAR(20),
    status ENUM('pendente', 'pago', 'confirmado') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_servico) REFERENCES servicos(id)
);

select*from usuarios;

DROP database sistemabruma