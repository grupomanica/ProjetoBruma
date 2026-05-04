<?php
session_start();
require_once("conexao.php");

try {
    $pdo = conectar();

    // Dados do formulário
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha'] ?? '');

    // Validação
    if (!$email || !$senha) {
        throw new Exception("Preencha email e senha corretamente.");
    }

    // Buscar clínica pelo email
    $stmt = $pdo->prepare("
        SELECT * 
        FROM clinicas 
        WHERE email = ?
    ");
    
    $stmt->execute([$email]);

    $clinica = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se encontrou clínica
    if (!$clinica) {
        throw new Exception("Clínica não encontrada.");
    }

    // Verifica senha
    if (!password_verify($senha, $clinica['senha'])) {
        throw new Exception("Senha incorreta.");
    }

    // Regenera sessão
    session_regenerate_id(true);

    $_SESSION['clinica_id'] = $clinica['id'];
    $_SESSION['clinica_nome'] = $clinica['nome'];
    $_SESSION['clinica_email'] = $clinica['email'];

    // Redireciona para painel
    header("Location: painel-clinica.php");
    exit();

} catch (Exception $e) {
    echo "
    <div style='color:red; text-align:center; margin-top:20px;'>
        ".$e->getMessage()."
    </div>";
}
?>