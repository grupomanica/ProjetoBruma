<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Profissional inválido.");
}

$clinica_id = $_SESSION['clinica_id'];
$id = (int) $_GET['id'];

try {
    $pdo = conectar();

    $sql = "DELETE FROM profissionais 
            WHERE id = :id 
            AND clinica_id = :clinica_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':clinica_id' => $clinica_id
    ]);

    header("Location: painel-clinica.php");
    exit();

} catch (PDOException $e) {
    die("Erro ao remover profissional: " . $e->getMessage());
}
?>