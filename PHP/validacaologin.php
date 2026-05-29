<?php
session_start();    

include_once("conexao.php");
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

try{
if($email === '') {
    if($senha !== '') {
        throw new Exception("senha incorreta");
    }
    throw new Exception("Email e senha são obrigatórios.");

} else {
    echo "Login efetuado com sucesso!";
}
}catch (Exception $erro) { echo "Erro: ".$erro ->getMessage();};
    echo "Erro: " . $e->getMessage();
header('Location: principal.php'); //Conferir se esta correto
exit;

include "footer.php";
?>