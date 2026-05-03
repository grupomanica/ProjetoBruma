<?php
require_once("conexao.php");

try {
    $pdo = conectar();

    // 🔒 Sanitização + validação
    $nome = htmlspecialchars($_POST['nome']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha'];

    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $endereco = htmlspecialchars($_POST['endereco']);

    if (!$nome || !$email || !$senha) {
        throw new Exception("Preencha todos os campos corretamente");
    }

    if ($senha !== $confirmar) {
        throw new Exception("Senhas não coincidem");
    }

    // 🔐 Criptografia
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 🔍 Verifica duplicado
    $sql = $pdo->prepare("SELECT id FROM clinicas WHERE email = ?");
    $sql->execute([$email]);

    if ($sql->rowCount() > 0) {
        throw new Exception("Email já cadastrado");
    }

    // 💾 Inserir
    $sql = $pdo->prepare("
        INSERT INTO clinicas (nome, email, senha, telefone, endereco)
        VALUES (?, ?, ?, ?, ?)
    ");

    $sql->execute([$nome, $email, $senhaHash, $telefone, $endereco]);

    header("Location: login-clinica.php");
    exit;

} catch (Exception $e) {
    echo $e->getMessage();
}