<?php
session_start();

include_once("conexao.php");

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$CEP = $_POST['CEP'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$estado = $_POST['estado'] ?? '';
$celular = $_POST['celular'] ?? '';

$_SESSION['nome'] = $nome;
$_SESSION['email'] = $email;
$_SESSION['cpf'] = $cpf;
$_SESSION['birthdate'] = $birthdate;
$_SESSION['CEP'] = $CEP;
$_SESSION['cidade'] = $cidade;
$_SESSION['estado'] = $estado;
$_SESSION['celular'] = $celular;

try {

    if(
        $nome === '' || 
        $email === '' || 
        $senha === '' || 
        $cpf === '' || 
        $birthdate === '' || 
        $CEP === '' || 
        $cidade === '' || 
        $estado === '' || 
        $celular === ''
    ){
        throw new Exception("Todos os campos são obrigatórios.");
    }

    echo "Cadastro efetuado com sucesso!";

} catch (Exception $erro) {

    echo "Erro: " . $erro->getMessage();

}

include "footer.php";
?>