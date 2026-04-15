create database sistemaBruma;
use sistemaBruma;

create table usuarios
    (
        codigo int primary key auto_increment,
        nome char(30) not null,
        email char(50) not null,
        senha char(60) not null
    );

select * from usuarios;
DESCRIBE usuarios;


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
drop database sistemaBruma;
drop table agendamento;