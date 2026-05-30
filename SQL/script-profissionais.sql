USE sistemabruma;

SELECT * FROM clinicas;
SELECT tipo_procedimento FROM servicos;
SELECT * FROM servicos WHERE clinica_id = 2;
select * FROM profissionais;
DELETE FROM profissionais WHERE status = 'ativo';

#PROFISSIONAIS DA 1ª CLÍNICA
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES ('1', 'Marina Melo', '12345', 'Esteticista', '1124745604', 'marina@email.com', '08:00:00', '18:00:00', 'Segunda, Quarta, Sexta, Sábado', 'ativo'),
('1', 'Camila Vasquez', '987654', 'Massagista', '1134565890', 'camila@email.com', '05:00:00', '15:00:00', 'Segunda, Terça, Quarta, Quinta, Sexta', 'ativo'),
('1', 'Camila Fernanda Ribeiro', '45871', 'Biomédica EstetaMassagista', '11987452231', 'camila.ribeiro.estetica@gmail.com', '10:00:00', '18:00:00', 'Quarta, Quinta, Sexta', 'ativo');

#PROFISSIONAIS DA 2ª CLÍNICA
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES
('2', 'Felipe Augusto Moreira', '554812', 'Enfermeiro Esteta', '41996731159', 'felipe.moreira.estetica@gmail.com', '14:00:00', '23:00:00', 'Quinta, Sexta, Sábado', 'ativo'),
('2', 'Juliana Alves Martins', '192834F', 'Massoterapeuta', '21998127745', 'juliana.martins.massagem@hotmail.com', '06:30:00', '16:30:00', 'Segunda, Terça, Quarta', 'ativo'),
('2', 'Renata Souza Lima', '78321', 'Esteticista', '31991546680', 'renata.lima.estetica@outlook.com', '13:00:00', '21:00:00', 'Terça, Quarta, Quinta, Sexta, Sábado', 'ativo');

#PROFISSIONAIS DA 3ª CLÍNICA
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES
('3', 'Patrícia Helena Costa', '51209', 'Biomédica Esteta', '19997418823', 'patricia.costa.estetica@gmail.com', '06:00:00', '12:00:00','Segunda, Terça, Quarta, Quinta, Sexta','ativo'),
('3', 'Diego Rafael Mendes', '208771F', 'Massoterapeuta', '71988531140', 'diego.mendes.terapias@outlook.com', '06:00:00', '12:00:00', 'Segunda, Terça, Quarta, Quinta, Sexta', 'ativo'),
('3', 'Vanessa Cristina Almeida', '39482', 'Farmacêutica Esteta', '62992746618', 'vanessa.almeida.estetica@icloud.com', '06:00:00', '12:00:00','Segunda, Terça, Quarta, Quinta, Sexta', 'ativo');

#PROFISSIONAIS DA 4ª CLÍNICA
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES
('