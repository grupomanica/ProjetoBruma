<?php
session_start();

require_once("conexao.php");

header('Content-Type: application/json');

try {

    $pdo = conectar();

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$email || empty($senha)) {

        echo json_encode([
            "status" => "erro",
            "mensagem" => "Preencha todos os campos."
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM usuarios
        WHERE email = ?
    ");
    
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        echo json_encode([
            "status" => "erro",
            "mensagem" => "Usuário não encontrado."
        ]);

        exit;
    }

    if(!password_verify($senha, $user['senha'])){

        echo json_encode([
            "status" => "erro",
            "mensagem" => "Senha incorreta."
        ]);

        exit;
    }

    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['usuario_nome'] = $user['nome'];

    echo json_encode([
        "status" => "sucesso",
        "mensagem" => "Login realizado com sucesso!",
        "redirect" => "servicos.php"
    ]);

} catch (PDOException $e) {
    
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro no banco de dados."
    ]);
} catch (Exception $e){

    echo json_encode([
        "status" => "erro",
        "mensagem" => $e->GetMessage()
    ]);

}
?>