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
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    regiao VARCHAR(20) NOT NULL DEFAULT 'Centro',
    logradouro VARCHAR(255),
    faixa_preco VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    senha VARCHAR(255)
);

-- SERVIÇOS
CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinica_id INT NOT NULL,
    tipo_procedimento VARCHAR(100) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    sessoes INT,
    valor DECIMAL(10,2),
    duracao INT,
    FOREIGN KEY (clinica_id)
    REFERENCES clinicas(id)
);

-- PROFISSIONAIS
CREATE TABLE profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinica_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    registro VARCHAR(100) NOT NULL,
    especialidade VARCHAR(150) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(150),
    hora_inicio TIME,
	hora_fim TIME,
    dias_semana VARCHAR(255),
    status VARCHAR(20) DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_profissional_clinica
    FOREIGN KEY (clinica_id)
    REFERENCES clinicas(id)
);

-- HORÁRIOS DISPONÍVEIS
CREATE TABLE horarios_disponiveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinica_id INT NOT NULL,
    servico_id INT NOT NULL,
    data_disponivel DATE NOT NULL,
    horario TIME NOT NULL,
    status ENUM('livre', 'ocupado') DEFAULT 'livre',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_horario_clinica
        FOREIGN KEY (clinica_id)
        REFERENCES clinicas(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_horario_servico
        FOREIGN KEY (servico_id)
        REFERENCES servicos(id)
        ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AGENDAMENTOS
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    clinica_id INT NOT NULL,
    servico_id INT NOT NULL,
    profissional_id INT NULL,
    horario_id INT NOT NULL,
    valor DECIMAL(10,2),
    status_pagamento ENUM(
        'pendente',
        'pago'
    ) DEFAULT 'pendente',
    status_agendamento ENUM(
        'pendente',
        'confirmado',
        'concluido',
        'cancelado'
    ) DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id),

    FOREIGN KEY (clinica_id)
    REFERENCES clinicas(id),

    FOREIGN KEY (servico_id)
    REFERENCES servicos(id),

    FOREIGN KEY (profissional_id)
    REFERENCES profissionais(id),

    FOREIGN KEY (horario_id)
    REFERENCES horarios_disponiveis(id)
);

CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    clinica_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(usuario_id, clinica_id)
);

SELECT * FROM usuarios;
SELECT * FROM clinicas;
SELECT senha FROM clinicas;
SELECT * from profissionais;

DROP DATABASE sistemaBruma;
DROP TABLE agendamentos;
DROP TABLE servicos;
DROP TABLE clinicas;

DROP TABLE horarios_disponiveis;

DESCRIBE servicos;
