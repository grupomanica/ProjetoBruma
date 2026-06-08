<?php
require_once("conexao.php");

try {
    $pdo = conectar();

    // Sanitização
    $nome = htmlspecialchars($_POST['nome']);
    $cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj']);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);

    $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
    $cidade = htmlspecialchars($_POST['cidade']);
    $bairro = htmlspecialchars($_POST['bairro']);
    $regiao = $_POST['regiao'];
    $logradouro = htmlspecialchars($_POST['logradouro']);

    $faixa_preco = $_POST['faixa_preco'];

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha'];

    // Validação básica
    if (
        !$nome ||
        !$cnpj ||
        !$telefone ||
        !$email ||
        !$senha ||
        !$logradouro
    ) {
        throw new Exception("Preencha todos os campos obrigatórios.");
    }

    // Verifica senha
    if ($senha !== $confirmar) {
        throw new Exception("As senhas não coincidem.");
    }

    // Criptografa senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica email duplicado
    $sql = $pdo->prepare("
        SELECT id 
        FROM clinicas 
        WHERE email = ?
    ");
    
    $sql->execute([$email]);

    if ($sql->rowCount() > 0) {
        throw new Exception("Este e-mail já está cadastrado.");
    }

    // Verifica CNPJ duplicado
    $sql = $pdo->prepare("
        SELECT id 
        FROM clinicas 
        WHERE cnpj = ?
    ");

    $sql->execute([$cnpj]);

    if ($sql->rowCount() > 0) {
        throw new Exception("Este CNPJ já está cadastrado.");
    }

    // Inserção
    $sql = $pdo->prepare("
        INSERT INTO clinicas (
            nome,
            cnpj,
            cep,
            cidade,
            bairro,
            regiao,
            logradouro,
            faixa_preco,
            email,
            telefone,
            senha
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $sql->execute([
        $nome,
        $cnpj,
        $cep,
        $cidade,
        $bairro,
        $regiao,
        $logradouro,
        $faixa_preco,
        $email,
        $telefone,
        $senhaHash
    ]);

    header("Location: login-clinica.php");
    exit();

} catch (Exception $e) {
    echo "<div style='color:red; text-align:center; margin-top:20px;'>".$e->getMessage()."</div>";
}
?>