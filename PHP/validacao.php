<?php
//validacao.php


// Apenas aceita POST e verifica se veio por ele
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastroUsuario.php');
    exit;
}

// Recebe os dados
$email		= $_POST["email"] ?? "";
$login		= $_POST["login"] ?? "";
$senha		= $_POST["senha"] ?? "";
$nome		= $_POST["nome"] ?? "";
$cpf		= $_POST["cpf"] ?? "";
$nascimento	= $_POST["nascimento"] ?? "";
$endereco	= $_POST["endereco"] ?? "";
// $telefone 	= $_POST["telefone"] ?? "";

$erros = [];

// Validações comuns ao cadastro
if ($is_register) {
    // Nome
    if ($nome === '') {
        $errors[] = "O campo Nome é obrigatório.";
    } elseif (strlen($nome) < 2) {
        $errors[] = "Nome muito curto.";
    }

    // Data de nascimento (YYYY-MM-DD)
    if ($nascimento === '') {
        $errors[] = "A data de nascimento é obrigatória.";
    }

    // Endereço
    if ($endereco === '') {
        $errors[] = "O endereço é obrigatório.";
    }

    if ($telefone === '') {
        $errors[] = "O telefone é obrigatório.";
    }
	
    // Email
    if ($email === '') {
        $errors[] = "O e-mail é obrigatório.";
    }
	
    // Senha
    if ($senha === '') {
        $errors[] = "A senha é obrigatória.";
    } elseif (strlen($senha) < 8) {
        $errors[] = "A senha deve ter pelo menos 8 caracteres.";
    }

    // Se houver erros, mostra e sai
    if (!empty($errors)) {
        echo "<h2 style='color:red;'>Foram encontrados os seguintes erros:</h2><ul style='color:red;'>";
        foreach ($errors as $err) {
            echo "<li>" . htmlspecialchars($err) . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='cadastro.php'>Voltar para o formulário</a></p>";
        include("rodape.php");
        exit;
    }
	
	// Validação de campos obrigatórios
	if (empty($email) || empty($login) || empty($senha) || empty($nome) ||
		empty($cpf) || empty($nascimento) || empty($endereco) || empty($telefone)){
			$erros[] = "Todos os campos são obrigatórios.";
		}
		
	// Se houver erros, mostra na tela
	if (!empty($erros)) {
		echo "<h2>Erros encontrados:</h2>";
		echo "<ul style='color:red; font-size:18px;'>";
		
		foreach ($erros as $e) {
			echo"<li>$e</li>";
		}
		
		echo "</ul>";
		
		echo "<br><a href='cadastro.php'><button>Voltar ao formulário</button></a>";
		exit;
	}
	
// Se tudo estiver ok, redireciona para a tela de 'cadastro ok'
session_start();
$_SESSION["dadosCadastro"] = $_POST;

header("Location: cadastroSucesso.php");
exit;
}

include("footer.php");

?>