<?php
session_start();

if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

require_once("conexao.php");
$pdo = conectar();

$agendamento_id = $_POST['agendamento_id'] ?? null;
$horario_id = $_POST['horario_id'] ?? null;

if (!$agendamento_id || !$horario_id) {
    die("Dados inválidos.");
}

try {
    $sql = "
        UPDATE agendamentos
        SET horario_id = :horario_id
        WHERE id = :agendamento_id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':horario_id' => $horario_id,
        ':agendamento_id' => $agendamento_id
    ]);

    header("Location: painel-clinica.php");
    exit();

} catch(PDOException $e) {
    die("Erro ao alterar horário.");
}