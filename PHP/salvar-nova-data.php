<?php

session_start();

require_once("conexao.php");

$pdo = conectar();

$agendamento_id = $_POST['agendamento_id'];
$novo_horario_id = $_POST['novo_horario_id'];

try {

    // Buscar horário antigo
    $sqlAtual = "

        SELECT horario_id

        FROM agendamentos

        WHERE id = :id

    ";

    $stmtAtual = $pdo->prepare($sqlAtual);

    $stmtAtual->execute([
        ':id' => $agendamento_id
    ]);

    $agendamento = $stmtAtual->fetch(PDO::FETCH_ASSOC);

    $horario_antigo = $agendamento['horario_id'];   

    // Buscar profissional atual do agendamento
    $sqlProfissional = "

        SELECT profissional_id

        FROM agendamentos

        WHERE id = :id

    ";

    $stmtProfissional = $pdo->prepare($sqlProfissional);

    $stmtProfissional->execute([
        ':id' => $agendamento_id
    ]);

    $dadosProfissional =
        $stmtProfissional->fetch(PDO::FETCH_ASSOC);

    $profissional_id =
        $dadosProfissional['profissional_id'];

    // Buscar informações do novo horário
    $sqlNovoHorario = "

        SELECT
            data_disponivel,
            horario

        FROM horarios_disponiveis

        WHERE id = :id

    ";

    $stmtNovoHorario =
        $pdo->prepare($sqlNovoHorario);

    $stmtNovoHorario->execute([
        ':id' => $novo_horario_id
    ]);

    $novoHorario =
        $stmtNovoHorario->fetch(PDO::FETCH_ASSOC);


    // Descobrir dia da semana

    $diasSemana = [

        'Sunday' => 'Domingo',
        'Monday' => 'Segunda',
        'Tuesday' => 'Terça',
        'Wednesday' => 'Quarta',
        'Thursday' => 'Quinta',
        'Friday' => 'Sexta',
        'Saturday' => 'Sábado'

    ];

    $diaSemana =
        $diasSemana[
            date(
                'l',
                strtotime(
                    $novoHorario['data_disponivel']
                )
            )
        ];


    // Verificar se profissional atende

    $sqlValidacao = "

        SELECT id

        FROM profissionais

        WHERE id = :profissional_id

        AND status = 'ativo'

        AND dias_semana LIKE :dia

        AND hora_inicio <= :hora

        AND hora_fim >= :hora

    ";

    $stmtValidacao =
        $pdo->prepare($sqlValidacao);

    $stmtValidacao->execute([

        ':profissional_id' => $profissional_id,

        ':dia' => '%' . $diaSemana . '%',

        ':hora' => $novoHorario['horario']

    ]);

    if(!$stmtValidacao->fetch()){

        die(
            'O profissional atual não atende no novo dia e horário.'
        );

    }


    // Atualizar agendamento
    $sqlUpdate = "

        UPDATE agendamentos

        SET horario_id = :novo_horario_id

        WHERE id = :id

    ";

    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->execute([
        ':novo_horario_id' => $novo_horario_id,
        ':id' => $agendamento_id
    ]);

    // Liberar horário antigo
    $sqlLivre = "

        UPDATE horarios_disponiveis

        SET status = 'livre'

        WHERE id = :id

    ";

    $stmtLivre = $pdo->prepare($sqlLivre);

    $stmtLivre->execute([
        ':id' => $horario_antigo
    ]);

    // Ocupar novo horário
    $sqlOcupado = "

        UPDATE horarios_disponiveis

        SET status = 'ocupado'

        WHERE id = :id

    ";

    $stmtOcupado = $pdo->prepare($sqlOcupado);

    $stmtOcupado->execute([
        ':id' => $novo_horario_id
    ]);

    header("Location: painel-clinica.php");
    exit();

} catch(PDOException $e) {

    die("Erro: " . $e->getMessage());

}
?>