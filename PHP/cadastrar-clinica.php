<?php
require_once("conexao.php");

header('Content-Type: application/json');

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
    empty($nome) ||
    empty($cnpj) ||
    empty($telefone) ||
    empty($cep) ||
    empty($cidade) ||
    empty($bairro) ||
    empty($regiao) ||
    empty($logradouro) ||
    empty($faixa_preco) ||
    empty($email) ||
    empty($senha) ||
    empty($confirmar)
) {

    echo json_encode([
        "sucesso" => false,
        "tipo" => "danger",
        "mensagem" => "Todos os campos precisam ser preenchidos."
    ]);

    exit;
}

    // Verifica senha
    if ($senha !== $confirmar) {

    echo json_encode([
        "sucesso" => false,
        "tipo" => "danger",
        "mensagem" => "As senhas não coincidem."
    ]);

    exit;
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

    echo json_encode([
        "sucesso" => false,
        "tipo" => "danger",
        "mensagem" => "Este e-mail já está cadastrado."
    ]);

    exit;
}

    // Verifica CNPJ duplicado
    $sql = $pdo->prepare("
        SELECT id 
        FROM clinicas 
        WHERE cnpj = ?
    ");

    $sql->execute([$cnpj]);

   if ($sql->rowCount() > 0) {

    echo json_encode([
        "sucesso" => false,
        "tipo" => "danger",
        "mensagem" => "Este CNPJ já está cadastrado."
    ]);

    exit;
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

   echo json_encode([
    "sucesso" => true,
    "tipo" => "success",
    "mensagem" => "Cadastro efetuado com sucesso! Aguarde, você será redirecionado para o login."
]);

exit;

} 
catch (Exception $e) {

    echo json_encode([
        "sucesso" => false,
        "tipo" => "danger",
        "mensagem" => $e->getMessage()
    ]);

}
?>