<?php 
require_once("conexao.php");

try {

    $pdo = conectar();

    // Sanitização
    $nome = htmlspecialchars($_POST['nome'] ?? '');

    $email = filter_input(
        INPUT_POST,
        'email',
        FILTER_VALIDATE_EMAIL
    );

    $telefone = htmlspecialchars(
        $_POST['telefone'] ?? ''
    );

    $senha = $_POST['senha'] ?? '';

    $confirmar = $_POST['confirmar_senha'] ?? '';

    // Validação
    if (!$nome || !$email || !$senha) {

        throw new Exception(
            "Preencha todos os campos corretamente"
        );

    }

    if ($senha !== $confirmar) {

        throw new Exception(
            "As senhas não coincidem"
        );

    }

    // Criptografia
    $senhaHash = password_hash(
        $senha,
        PASSWORD_DEFAULT
    );

    // Verificar email duplicado
    $sql = $pdo->prepare(
        "SELECT id FROM usuarios WHERE email = ?"
    );

    $sql->execute([$email]);

    if ($sql->rowCount() > 0) {

        throw new Exception(
            "Email já cadastrado"
        );

    }

    // Inserir usuário
    $sql = $pdo->prepare("
        INSERT INTO usuarios (
            nome,
            email,
            telefone,
            senha
        ) 
        VALUES (?, ?, ?, ?)
    ");

    $sql->execute([
        $nome,
        $email,
        $telefone,
        $senhaHash
    ]);

    header("Location: login.php");

    exit;

} catch (Exception $e) {

    echo $e->getMessage();

}