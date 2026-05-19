<?php
session_start();
require_once("conexao.php");

// Verifica login da clínica
if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

$clinica_id = $_SESSION['clinica_id'];

$nome = trim($_POST['nome'] ?? '');
$registro = trim($_POST['registro'] ?? '');
$especialidade = trim($_POST['especialidade'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$hora_inicio = trim($_POST['hora_inicio'] ?? '');
$hora_fim = trim($_POST['hora_fim'] ?? '');
$dias_semana = implode(', ', $_POST['dias_semana'] ?? []);
$status = trim($_POST['status'] ?? '');

if (
    empty($nome) ||
    empty($registro) ||
    empty($especialidade) ||
    empty($telefone) ||
    empty($email) ||
    empty($status)
) {
    die("Preencha todos os campos obrigatórios.");
}

try {
    $pdo = conectar();

    $sql = "INSERT INTO profissionais 
(
    clinica_id,
    nome,
    registro,
    especialidade,
    telefone,
    email,
    hora_inicio,
    hora_fim,
    dias_semana,
    status
)
VALUES 
(
    :clinica_id,
    :nome,
    :registro,
    :especialidade,
    :telefone,
    :email,
    :hora_inicio,
    :hora_fim,
    :dias_semana,
    :status
)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
    ':clinica_id' => $clinica_id,
    ':nome' => $nome,
    ':registro' => $registro,
    ':especialidade' => $especialidade,
    ':telefone' => $telefone,
    ':email' => $email,
    ':hora_inicio' => $hora_inicio,
    ':hora_fim' => $hora_fim,
    ':dias_semana' => $dias_semana,
    ':status' => $status
]);

    header("Location: painel-clinica.php");
    exit();

} catch (PDOException $e) {
    die("Erro ao cadastrar profissional: " . $e->getMessage());
}
?>