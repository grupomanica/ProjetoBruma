<?php
session_start();

require_once("conexao.php");

if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

$pdo = conectar();

$clinica_id = $_SESSION['clinica_id'];

$tipo = trim($_POST['tipo_procedimento'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$sessoes = $_POST['sessoes'] ?? 1;
$valor = $_POST['valor'] ?? 0;
$duracao = $_POST['duracao'] ?? 0;

// validação básica
if (
    empty($tipo) ||
    empty($nome) ||
    empty($descricao) ||
    empty($sessoes) ||
    empty($valor) ||
    empty($duracao)
) {
    die("Preencha todos os campos obrigatórios.");
}

try {

    $sql = "
        INSERT INTO servicos (
            clinica_id,
            tipo_procedimento,
            nome,
            descricao,
            sessoes,
            valor,
            duracao
        )
        VALUES (
            :clinica_id,
            :tipo,
            :nome,
            :descricao,
            :sessoes,
            :valor,
            :duracao
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':clinica_id' => $clinica_id,
        ':tipo' => $tipo,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':sessoes' => $sessoes,
        ':valor' => $valor,
        ':duracao' => $duracao
    ]);

    header("Location: painel-clinica.php?sucesso=1");
    exit();

} catch (PDOException $e) {
    die("Erro ao salvar serviço: " . $e->getMessage());
}
?>