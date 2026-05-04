<?php
session_start();
require_once("conexao.php");

try {
    $pdo = conectar();

    // Dados do formulário de login
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (!$email || !$senha) {
        throw new Exception("Dados inválidos");
    }

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if (!$user || !password_verify($senha, $user['senha'])) {
        throw new Exception("Login inválido");
    }

    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['usuario_nome'] = $user['nome'];

    header("Location:servicos.php");
    exit;

} catch (Exception $e) {
    echo $e->getMessage();
}
?>