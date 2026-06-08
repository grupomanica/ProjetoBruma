
<?php

session_start();

require_once("conexao.php");

$pdo = conectar();

$id = $_POST['id'];

$nome = $_POST['nome'];
$registro = $_POST['registro'];
$especialidade = $_POST['especialidade'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];

$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];

$status = $_POST['status'];

$dias_semana =
    implode(',', $_POST['dias_semana']);

$sql = "

    UPDATE profissionais SET

        nome = :nome,
        registro = :registro,
        especialidade = :especialidade,
        telefone = :telefone,
        email = :email,

        hora_inicio = :hora_inicio,
        hora_fim = :hora_fim,
        dias_semana = :dias_semana,

        status = :status

    WHERE id = :id

";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':nome' => $nome,
    ':registro' => $registro,
    ':especialidade' => $especialidade,
    ':telefone' => $telefone,
    ':email' => $email,

    ':hora_inicio' => $hora_inicio,
    ':hora_fim' => $hora_fim,

    ':dias_semana' => $dias_semana,

    ':status' => $status,

    ':id' => $id

]);

header("Location: painel-clinica.php");

exit();
?>