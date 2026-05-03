<?php


require_once("conexao.php");

try {
    $pdo = conectar();

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (!$email || !$senha) {
        throw new Exception("Dados inválidos");
    }

    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $sql->execute([$email]);

    $usuario = $sql->fetch();

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        throw new Exception("Email ou senha inválidos");
    }

    // 🔒 segurança de sessão
    session_regenerate_id(true);

  //  $_SESSION['usuario_id'] = $usuario['id'];
  //  $_SESSION['usuario_nome'] = $usuario['nome'];


} catch (Exception $e) {
    echo $e->getMessage();
}
 header("Location:painel.php");
    exit; 

    ?>