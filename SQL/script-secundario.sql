##### PROFISSIONAIS, SERVICOS, HORÁRIOS DAS CLINICAS
############# ANTES DE RODAR, É PRECISO CADASTRAR (MANUALMENTE) AS CLÍNICAS


USE sistemabruma;

SELECT * FROM clinicas;
SELECT tipo_procedimento FROM servicos;
SELECT * FROM servicos WHERE clinica_id = 2;
select * FROM profissionais;
DELETE FROM profissionais WHERE status = 'ativo';

#1ª CLÍNICA (Grupo Manica)
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
    VALUES ('1', 'Marina Melo', '12345', 'Esteticista', '1124745604', 'marina@email.com', '08:00:00', '18:00:00', 'Segunda, Quarta, Sexta, Sábado', 'ativo'),
    ('1', 'Camila Vasquez', '987654', 'Massagista', '1134565890', 'camila@email.com', '05:00:00', '15:00:00', 'Segunda, Terça, Quarta, Quinta, Sexta', 'ativo'),
    ('1', 'Camila Fernanda Ribeiro', '45871', 'Biomédica EstetaMassagista', '11987452231', 'camila.ribeiro.estetica@gmail.com', '10:00:00', '18:00:00', 'Quarta, Quinta, Sexta', 'ativo');
INSERT INTO servicos (clinica_id, tipo_procedimento, nome, descricao, sessoes, valor, duracao)
    VALUES ('1', 'Limpeza', 'Limpeza de Pele Facial', 'Higienização, esfoliação, extração de cravos, cauterização, aplicação de máscara calmante e hidratante e filtro solar.', '1', '179.99', '40'),
    ('1', 'Botox', 'Botox Ácido Hialurônico', 'Procedimento estético que suaviza linhas de expressão e rugas, proporcionando uma aparência mais jovem, leve e natural.', '1', '849.99', '80'),
    ('1', 'Peeling Químico', 'Peeling Facial Renovador', 'Aplicação de ácidos específicos para renovação celular, auxiliando na redução de manchas, acne, linhas finas e melhorando a textura da pele.', '4', '249.99', '80');
INSERT INTO horarios_disponiveis (clinica_id, servico_id, data_disponivel, horario, status)
    VALUES ('1', '1', '2026-06-19', '08:00:00', 'livre'),
    ('1', '1', '2026-06-20', '09:00:00', 'livre'),
    ('1', '1', '2026-06-21', '10:00:00', 'livre'),
    ('1', '2', '2026-06-19', '13:00:00', 'livre'),
    ('1', '2', '2026-06-20', '14:00:00', 'livre'),
    ('1', '2', '2026-06-21', '16:00:00', 'livre'),
    ('1', '3', '2026-06-19', '06:00:00', 'livre'),
    ('1', '3', '2026-06-20', '08:30:00', 'livre'),
    ('1', '3', '2026-06-21', '11:00:00', 'livre');  

#2ª CLÍNICA (Shizenguo Estética)
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES ('2', 'Felipe Augusto Moreira', '554812', 'Enfermeiro Esteta', '41996731159', 'felipe.moreira.estetica@gmail.com', '14:00:00', '23:00:00', 'Quinta, Sexta, Sábado', 'ativo'),
('2', 'Juliana Alves Martins', '192834F', 'Massoterapeuta', '21998127745', 'juliana.martins.massagem@hotmail.com', '06:30:00', '16:30:00', 'Segunda, Terça, Quarta', 'ativo'),
('2', 'Renata Souza Lima', '78321', 'Esteticista', '31991546680', 'renata.lima.estetica@outlook.com', '13:00:00', '21:00:00', 'Terça, Quarta, Quinta, Sexta, Sábado', 'ativo');
INSERT servicos (clinica_id, tipo_procedimento, nome, descricao, sessoes, valor, duracao)
VALUES ('2', 'Microagulhamento', 'Microagulhamento Facial com Dermapen', 'Procedimento que estimula a produção de colágeno através de microperfurações, ajudando na redução de cicatrizes, manchas e sinais de envelhecimento.', '3', '349.99', '60'),
('2', 'Harmonização Facial', 'Harmonização Facial Premium', 'Conjunto de procedimentos estéticos realizados para equilibrar os traços faciais, melhorar a simetria e valorizar a beleza natural.', '1', '2499.99', '45'),
('2', 'Preenchimento Labial', 'Preenchimento Labial com Ácido Hialurônico', 'Procedimento que aumenta o volume, define o contorno e melhora a hidratação dos lábios, proporcionando um resultado natural e harmonioso.', '1', '1199.99', '30');


#3ª CLÍNICA (VenomousDiva Estética e Unhas)
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES ('3', 'Patrícia Helena Costa', '51209', 'Biomédica Esteta', '19997418823', 'patricia.costa.estetica@gmail.com', '06:00:00', '12:00:00','Segunda, Terça, Quarta, Quinta, Sexta','ativo'),
('3', 'Diego Rafael Mendes', '208771F', 'Massoterapeuta', '71988531140', 'diego.mendes.terapias@outlook.com', '06:00:00', '12:00:00', 'Segunda, Terça, Quarta, Quinta, Sexta', 'ativo'),
('3', 'Vanessa Cristina Almeida', '39482', 'Farmacêutica Esteta', '62992746618', 'vanessa.almeida.estetica@icloud.com', '06:00:00', '12:00:00','Segunda, Terça, Quarta, Quinta, Sexta', 'ativo');
INSERT servicos (clinica_id, tipo_procedimento, nome, descricao, sessoes, valor, duracao)
VALUES ('3', 'Bioestimuladores de Colágeno', 'Bioestimulador Facial de Colágeno', 'Tratamento que estimula a produção natural de colágeno, promovendo firmeza, sustentação e rejuvenescimento da pele.', '3', '1799.99', '45'),
('3', 'Lipo Enzimática', 'Lipo Enzimática Corporal', 'Aplicação de enzimas que auxiliam na quebra de gordura localizada, ajudando na redução de medidas e definição corporal.', '3', '299.99', '30'),
('3', 'Drenagem Linfática', 'Drenagem Linfática Corporal', 'Massagem terapêutica que auxilia na eliminação de líquidos retidos, melhora a circulação e reduz o inchaço corporal.', '1', '	119.99', '90');


#4ª CLÍNICA (Bella Estética)
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES ('4','Nicholly Gonzaga', '895345', 'Biomédica', '1156903678', 'nicholly@email.com', '08:00:00', '15:00:00', 'Terça, Quarta, Quinta, Sexta, Sábado', 'ativo'),
('4', 'Gael Augusto Luís Porto', '127102', 'Dermatologista', '84987781331', 'gael-porto89@salera.com.br', '08:00:00', '15:00:00', 'Terça, Quarta, Quinta, Sexta, Sábado', 'ativo'),
('4', 'Hugo Enzo José Silva', '153057', 'Dermatologista', '84995933112', 'hugo_silva@cladm.com.br', '05:00:00', '14:00:00', 'Terça, Quarta, Quinta, Sexta, Sábado', 'ativo');
INSERT servicos (clinica_id, tipo_procedimento, nome, descricao, sessoes, valor, duracao)
VALUES ('4', 'Criolipólise', 'Criolipólise Redutora', 'Procedimento não invasivo que utiliza baixas temperaturas para reduzir gordura localizada e auxiliar na definição corporal.', '1', '899.99', '180'),
('4', 'Tratamento para Celulite', 'Tratamento Corporal Anti-Celulite', 'Procedimento estético voltado para reduzir celulites, melhorar a circulação e deixar a pele mais firme e uniforme.', '4', '220', '300'),
('4', 'Detox Corporal', 'Detox Corporal Relaxante', 'Tratamento corporal que auxilia na eliminação de toxinas, melhora a circulação e promove sensação de leveza e bem-estar.', '4', '179.99', '90');


#5ª CLÍNICA (Clínica Mariah & Anthony)
INSERT INTO profissionais (clinica_id, nome, registro, especialidade, telefone, email, hora_inicio, hora_fim, dias_semana, status)
VALUES ('5','Marina Beatriz Falcão','48291','Biomédica Esteta','11987654321','marina.falcao@esteticaviva.com.br','08:00:00','15:00:00','Segunda, Terça, Quarta, Quinta, Sexta','ativo'),
('5','Rafael Henrique Nogueira','238745','Dermatologista','11991234567','rafael.nogueira@clinicabelle.com.br','09:00:00','18:00:00','Segunda, Terça, Quinta, Sexta, Sábado','ativo'),
('5','Lucas Gabriel Monteiro','65432','Harmonização Orofacial','11994567812','lucas.monteiro@primeface.com.br','10:25:00','19:25:00','Segunda, Quarta, Quinta, Sexta, Sábado','ativo');
INSERT servicos (clinica_id, tipo_procedimento, nome, descricao, sessoes, valor, duracao)
VALUES ('5', 'Carboxiterapia', 'Carboxiterapia Corporal', 'Procedimento à base de CO2 terapia que ajuda no combate às estrias, celulites, flacidez, gordura localizada e olheiras. ', '5', '429', '40'),
('5', 'Fios de Sustentação', 'Foxy Eyes', 'Realizamos o procedimento de forma temporária, através de Fios de tração de PDO.
São realizados na clínica sob anestesia local. Procedimento rápido e reversível.
Outra forma mais definitiva de se conquistar esse olhar marcante, é através de cirurgias como a cantoplastia e reposicionamento de supercílios.', '1', '249', '30'),
('5', 'Bichectomia', 'Bichectomia', 'A bichectomia é um procedimento cirúrgico que visa remover as bolsas de gordura da bochecha, proporcionando um contorno facial mais definido e estético. ', '1', '4149', '80');