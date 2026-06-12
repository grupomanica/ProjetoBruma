<?php

session_start();

require_once("conexao.php");

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

try {

    $pdo = conectar();

    $usuario_id = $_SESSION['usuario_id'];

    $servico_id = $_POST['servico_id'] ?? '';
    $clinica_id = $_POST['clinica_id'] ?? '';
    $horarioSelecionado = $_POST['horario'] ?? '';
    $profissional_id = $_POST['profissional_id'] ?? '';
    $dataNascimento = $_POST['data_nascimento'] ?? '';

    $formaPagamento = $_POST['forma_pagamento'] ?? 'pix';

    // VALIDA CAMPOS
    if(
        empty($servico_id) ||
        empty($clinica_id) ||
        empty($horarioSelecionado)
    ){
        die("Erro: dados do agendamento não enviados.");
    }

    // VALIDA DATA DE NASCIMENTO
    if(empty($dataNascimento)){
        die("Data de nascimento não informada.");
    }

    $hoje = new DateTime();
    $nascimento = new DateTime($dataNascimento);

    $idade = $hoje->diff($nascimento)->y;

    if($idade < 18){

        echo "
        <script>
            alert('Você deve ser maior de idade para continuar o agendamento.');
            window.history.back();
        </script>
        ";

        exit();
    }

    // SEPARA HORÁRIO
    $partes = explode('|', $horarioSelecionado);

    if(count($partes) < 3){
        die("Horário inválido.");
    }

    $horario_id = $partes[0];
    $data = $partes[1];
    $hora = $partes[2];

    // BUSCA VALOR DO SERVIÇO
$horario_id = $partes[0];
$data = $partes[1];
$hora = $partes[2];
    // BUSCA VALOR
    $stmtServico = $pdo->prepare("
        SELECT valor
        FROM servicos
        WHERE id = :id
    ");

    $stmtServico->execute([
        ':id' => $servico_id
    ]);

    $servico = $stmtServico->fetch(PDO::FETCH_ASSOC);

    if(!$servico){
        die("Serviço não encontrado.");
    }

    $valor = $servico['valor'];

    // BUSCA PROFISSIONAL AUTOMATICAMENTE
    if(empty($profissional_id)){

        $sqlHorario = "
            SELECT
                data_disponivel,
                horario,
                clinica_id
            FROM horarios_disponiveis
            WHERE id = :horario_id
        ";

        $stmtHorario = $pdo->prepare($sqlHorario);

        $stmtHorario->execute([
            ':horario_id' => $horario_id
        ]);

        $horario = $stmtHorario->fetch(PDO::FETCH_ASSOC);

        if(!$horario){
            die("Horário não encontrado.");
        }

        $dataAgendamento = $horario['data_disponivel'];
        $horaAgendamento = $horario['horario'];

        $diasSemana = [
            'Domingo',
            'Segunda',
            'Terça',
            'Quarta',
            'Quinta',
            'Sexta',
            'Sábado'
        ];

        $diaSemana = $diasSemana[
            date('w', strtotime($dataAgendamento))
        ];

        $sqlProfissional = "
            SELECT id
            FROM profissionais
            WHERE clinica_id = :clinica_id
            AND status = 'ativo'
            AND dias_semana LIKE :dia_semana
            AND hora_inicio <= :hora_inicio
            AND hora_fim >= :hora_fim
            LIMIT 1
        ";

        $stmtProfissional = $pdo->prepare($sqlProfissional);

        $stmtProfissional->execute([
            ':clinica_id' => $horario['clinica_id'],
            ':dia_semana' => '%' . $diaSemana . '%',
            ':hora_inicio' => $horaAgendamento,
            ':hora_fim' => $horaAgendamento
        ]);

        $profissional = $stmtProfissional->fetch(PDO::FETCH_ASSOC);

        if(!$profissional){
            die("Nenhum profissional disponível para este horário.");
        }

        $profissional_id = $profissional['id'];
    }

    // INSERE AGENDAMENTO
    $sql = "
        INSERT INTO agendamentos (
            usuario_id,
            clinica_id,
            servico_id,
            profissional_id,
            horario_id,
            valor,
            status_pagamento,
            status_agendamento
        )
        VALUES (
            :usuario_id,
            :clinica_id,
            :servico_id,
            :profissional_id,
            :horario_id,
            :valor,
            :status_pagamento,
            :status_agendamento
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ':usuario_id' => $usuario_id,
        ':clinica_id' => $clinica_id,
        ':servico_id' => $servico_id,
        ':profissional_id' => $profissional_id,
        ':horario_id' => $horario_id,
        ':valor' => $valor,
        ':status_pagamento' => 'pendente',
        ':status_agendamento' => 'confirmado'
    ]);

    // PEGA ID DO AGENDAMENTO
    $agendamento_id = $pdo->lastInsertId();

    // OCUPA HORÁRIO
    $stmtHorario = $pdo->prepare("
        UPDATE horarios_disponiveis
        SET status = 'ocupado'
        WHERE id = :horario_id
    ");

    $stmtHorario->execute([
        ':horario_id' => $horario_id
    ]);

    // ABRE BOLETO
    echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Gerando boleto</title>
        </head>
        <body>
            <form
                id='abrirBoleto' action='gerar_boleto.php' method='GET' target='_blank'>

                <input type='hidden' name='id' value='{$agendamento_id}'>
            </form>

            <script>
                document.getElementById('abrirBoleto').submit();
                setTimeout(function(){
                    window.location.href = 'painel.php';
                }, 1500);
            </script>
        </body>
        </html>
        ";
    echo "
        <script>
            alert('Agendamento realizado com sucesso!');
        </script>
    ";

    exit();

} catch(PDOException $e){

    die('Erro no banco: ' . $e->getMessage());

}