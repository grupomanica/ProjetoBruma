<?php

require_once("conexao.php");

header('Content-Type: application/json');

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

        echo json_encode([
            "sucesso" => false,
            "tipo" => "danger",
            "mensagem" => "Preencha todos os campos corretamente."
        ]);

        exit;
    }

    // Senhas diferentes
    if ($senha !== $confirmar) {

        echo json_encode([
            "sucesso" => false,
            "tipo" => "danger",
            "mensagem" => "As senhas não coincidem."
        ]);

        exit;
    }

    // Verificar email duplicado
    $sql = $pdo->prepare(
        "SELECT id FROM usuarios WHERE email = ?"
    );

    $sql->execute([$email]);

    if ($sql->rowCount() > 0) {

        echo json_encode([
            "sucesso" => false,
            "tipo" => "danger",
            "mensagem" => "Este e-mail já está cadastrado."
        ]);

        exit;
    }

    // Criptografia
    $senhaHash = password_hash(
        $senha,
        PASSWORD_DEFAULT
    );

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

    echo json_encode([
        "sucesso" => true,
        "tipo" => "success",
        "mensagem" => "Cadastro realizado com sucesso! Redirecionando para o login..."
    ]);

    exit;

} catch (Exception $e) {

    echo json_encode([
        "sucesso" => false,
        "tipo" => "danger",
        "mensagem" => $e->getMessage()
    ]);

}