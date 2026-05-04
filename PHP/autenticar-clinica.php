<?php
session_start();
require_once("conexao.php");

$pdo = conectar();

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

try {

    $sql = "SELECT * FROM clinicas WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $clinica = $stmt->fetch();

    if ($clinica && password_verify($senha, $clinica['senha'])) {

        $_SESSION['clinica_id'] = $clinica['id'];
        $_SESSION['clinica_nome'] = $clinica['nome'];
        $_SESSION['clinica_email'] = $clinica['email'];
        $_SESSION['clinica_telefone'] = $clinica['telefone'];
        $_SESSION['clinica_cep'] = $clinica['cep'];

        header("Location: painel-clinica.php");
        exit();

    } else {
        echo "E-mail ou senha inválidos.";
    }

} catch(PDOException $e){
    die("Erro no login: " . $e->getMessage());
}