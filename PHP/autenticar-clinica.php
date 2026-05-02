<?php
require_once("conexao.php");

try {
    $pdo = conectar();

    // =========================
    // SANITIZAÇÃO
    // =========================
    $nome = htmlspecialchars(trim($_POST['nome']));
    $cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj']);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $cidade = htmlspecialchars(trim($_POST['cidade']));
    $bairro = htmlspecialchars(trim($_POST['bairro']));
    $endereco = htmlspecialchars(trim($_POST['endereco']));
    $faixa = htmlspecialchars($_POST['faixa_preco']);

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    // =========================
    // VALIDAÇÕES
    // =========================
    if (!$nome || !$email || !$senha) {
        throw new Exception("Preencha os campos obrigatórios");
    }

    if (strlen($senha) < 6) {
        throw new Exception("Senha deve ter no mínimo 6 caracteres");
    }

    if ($senha !== $confirmar) {
        throw new Exception("As senhas não coincidem");
    }

    // =========================
    // VERIFICAR EMAIL
    // =========================
    $stmt = $pdo->prepare("SELECT id FROM clinicas WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        throw new Exception("Email já cadastrado");
    }

    // =========================
    // HASH
    // =========================
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // =========================
    // INSERT
    // =========================
    $stmt = $pdo->prepare("
        INSERT INTO clinicas 
        (nome, email, senha, telefone, endereco, cnpj, cidade, bairro, faixa_preco)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $nome,
        $email,
        $senhaHash,
        $telefone,
        $endereco,
        $cnpj,
        $cidade,
        $bairro,
        $faixa
    ]);

    // =========================
    // LOGIN AUTOMÁTICO
    // =========================
    $id_clinica = $pdo->lastInsertId();

   header("Location: login-clinica.php");
exit;
    session_regenerate_id(true);

    // =========================
    // COOKIE
    // =========================
    setcookie("clinica_login", $email, time() + (86400 * 7), "/", "", false, true);

    // =========================
    // REDIRECIONAMENTO
    // =========================
    header("Location:painel-clinica.php");
    exit;

} catch (Exception $e) {
    echo "<div style='color:red; text-align:center; margin-top:20px;'>".$e->getMessage()."</div>";
}
