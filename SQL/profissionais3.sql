USE sistemabruma;

SELECT * FROM clinicas;
SELECT tipo_procedimento FROM servicos;
SELECT * FROM servicos WHERE clinica_id = 2;
select * FROM profissionais;
DELETE FROM profissionais WHERE status = 'ativo';

INSERT INTO servicos (clinica_id, tipo_procedimento, nome, descricao, sessoes, valor, duracao)
VALUES ('2', 'Botox', 'Botox Enzimático', 'Botox feito com as mais finas enzimas possíveis', '2', '1399', '80');

INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES
('3', 'Patrícia Helena Costa', '51209', 'Biomédica Esteta', '19997418823', 'patricia.costa.estetica@gmail.com', '06:00:00', '12:00:00','Segunda, Terça, Quarta, Quinta, Sexta','ativo'),
('3', 'Diego Rafael Mendes', '208771F', 'Massoterapeuta', '71988531140', 'diego.mendes.terapias@outlook.com', '06:00:00', '12:00:00', 'Segunda, Terça, Quarta, Quinta, Sexta', 'ativo'),
('3', 'Vanessa Cristina Almeida', '39482', 'Farmacêutica Esteta', '62992746618', 'vanessa.almeida.estetica@icloud.com', '06:00:00', '12:00:00','Segunda, Terça, Quarta, Quinta, Sexta', 'ativo');	