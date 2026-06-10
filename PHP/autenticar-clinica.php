<?php

session_start();
require_once("conexao.php");

header('Content-Type: application/json');

$pdo = conectar();

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

try {

    $sql = "SELECT * FROM clinicas WHERE email = :email";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $clinica = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($clinica && password_verify($senha, $clinica['senha'])) {

        $_SESSION['clinica_id'] = $clinica['id'];
        $_SESSION['clinica_nome'] = $clinica['nome'];
        $_SESSION['clinica_email'] = $clinica['email'];
        $_SESSION['clinica_telefone'] = $clinica['telefone'];
        $_SESSION['clinica_cep'] = $clinica['cep'];

        echo json_encode([
            "status" => "sucesso",
            "mensagem" => "Login realizado com sucesso!",
            "redirect" => "painel-clinica.php"
        ]);

    } else {

        echo json_encode([
            "status" => "erro",
            "mensagem" => "E-mail ou senha inválidos."
        ]);

    }

} catch(PDOException $e){

    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro interno do sistema."
    ]);

}