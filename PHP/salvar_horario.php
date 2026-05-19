<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servico_id = $_POST['servico_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    try {

        $pdo = conectar();

        $stmt = $pdo->prepare("
            INSERT INTO horarios_disponiveis
            (
                clinica_id,
                servico_id,
                data_disponivel,
                horario,
                status
            )
            VALUES (?, ?, ?, ?, 'livre')
        ");

        $stmt->execute([
            $_SESSION['clinica_id'],
            $servico_id,
            $data,
            $hora
        ]);

        header("Location: painel-clinica.php");
        exit;

    } catch (Exception $e) {
        die("Erro: " . $e->getMessage());
    }
}
?>