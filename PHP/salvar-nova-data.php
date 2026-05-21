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