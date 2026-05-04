<?php
session_start();
require_once("conexao.php");

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

$pdo = conectar();

$usuario_id = $_SESSION['usuario_id'];

$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$data_nascimento = $_POST['data_nascimento'];

$stmt = $pdo->prepare("
    UPDATE usuarios
    SET 
        nome = ?,
        sobrenome = ?,
        email = ?,
        telefone = ?,
        data_nascimento = ?
    WHERE id = ?
");

$stmt->execute([
    $nome,
    $sobrenome,
    $email,
    $telefone,
    $data_nascimento,
    $usuario_id
]);

$_SESSION['usuario_nome'] = $nome;

header("Location: painel.php");
exit();
?>