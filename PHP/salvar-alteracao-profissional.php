<?php

session_start();

if(!isset($_SESSION['clinica_id'])){
    header("Location: login-clinica.php");
    exit();
}

require_once("conexao.php");

$pdo = conectar();

$agendamento_id = $_POST['agendamento_id'] ?? 0;

$profissional_id = $_POST['profissional_id'] ?? 0;

try {

    // Buscar data e horário do agendamento
    $sqlAgendamento = "

        SELECT

            h.data_disponivel,
            h.horario

        FROM agendamentos a

        INNER JOIN horarios_disponiveis h

            ON h.id = a.horario_id

        WHERE a.id = :id

    ";

    $stmtAgendamento =
        $pdo->prepare($sqlAgendamento);

    $stmtAgendamento->execute([
        ':id' => $agendamento_id
    ]);

    $agendamento =
        $stmtAgendamento->fetch(PDO::FETCH_ASSOC);


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
                    $agendamento['data_disponivel']
                )
            )
        ];


    // Validar profissional

    $sqlValidaProfissional = "

        SELECT id

        FROM profissionais

        WHERE id = :profissional_id

        AND status = 'ativo'

        AND dias_semana LIKE :dia

        AND hora_inicio <= :hora

        AND hora_fim >= :hora

    ";

    $stmtValidaProfissional =
        $pdo->prepare($sqlValidaProfissional);

    $stmtValidaProfissional->execute([

        ':profissional_id' => $profissional_id,

        ':dia' => '%' . $diaSemana . '%',

        ':hora' => $agendamento['horario']

    ]);

    if(!$stmtValidaProfissional->fetch()){

        die(
            'Este profissional não atende neste dia ou horário.'
        );

    }

    $sql = "

        UPDATE agendamentos

        SET profissional_id = :profissional_id

        WHERE id = :id

    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ':profissional_id' => $profissional_id,

        ':id' => $agendamento_id

    ]);

    header("Location: painel-clinica.php");

    exit();

} catch(PDOException $e){

    die("Erro ao atualizar profissional.");

}