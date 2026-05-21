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