<?php
session_start();
require_once("conexao.php");

try {
    $pdo = conectar();

    $id_clinica = $_SESSION['clinica_id'];

    $nome = htmlspecialchars($_POST['nome']);
    $descricao = htmlspecialchars($_POST['descricao']);
    $valor = floatval($_POST['valor']);
    $duracao = intval($_POST['duracao']);

    $sql = $pdo->prepare("
        INSERT INTO servicos (id_clinica, nome, descricao, valor, duracao)
        VALUES (?, ?, ?, ?, ?)
    ");

    $sql->execute([$id_clinica, $nome, $descricao, $valor, $duracao]);

    header("Location:painel-clinica.php");
    exit;

} catch (Exception $e) {
    echo $e->getMessage();
}
?>