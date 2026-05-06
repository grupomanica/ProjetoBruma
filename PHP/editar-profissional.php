<?php
session_start();
require_once("conexao.php");

if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

$pdo = conectar();
$clinica_id = $_SESSION['clinica_id'];

if (!isset($_GET['id'])) {
    die("Profissional não encontrado.");
}

$id = (int) $_GET['id'];

try {
    $sql = "SELECT * FROM profissionais 
            WHERE id = :id 
            AND clinica_id = :clinica_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':clinica_id' => $clinica_id
    ]);

    $profissional = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profissional) {
        die("Profissional não encontrado.");
    }

} catch (PDOException $e) {
    die("Erro ao buscar profissional: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $registro = trim($_POST['registro']);
    $especialidade = trim($_POST['especialidade']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $status = trim($_POST['status']);

    try {
        $sql = "UPDATE profissionais SET
                    nome = :nome,
                    registro = :registro,
                    especialidade = :especialidade,
                    telefone = :telefone,
                    email = :email,
                    status = :status
                WHERE id = :id
                AND clinica_id = :clinica_id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $nome,
            ':registro' => $registro,
            ':especialidade' => $especialidade,
            ':telefone' => $telefone,
            ':email' => $email,
            ':status' => $status,
            ':id' => $id,
            ':clinica_id' => $clinica_id
        ]);

        header("Location: painel-clinica.php");
        exit();

    } catch (PDOException $e) {
        die("Erro ao atualizar profissional: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Profissional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Editar Profissional</h2>

    <form method="POST">

        <label class="form-label">Nome</label>
        <input 
            type="text"
            name="nome"
            class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['nome']) ?>"
            required
        >

        <label class="form-label">Registro</label>
        <input 
            type="text"
            name="registro"
            class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['registro']) ?>"
            required
        >

        <label class="form-label">Especialidade</label>
        <input 
            type="text"
            name="especialidade"
            class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['especialidade']) ?>"
            required
        >

        <label class="form-label">Telefone</label>
        <input 
            type="text"
            name="telefone"
            class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['telefone']) ?>"
            required
        >

        <label class="form-label">E-mail</label>
        <input 
            type="email"
            name="email"
            class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['email']) ?>"
            required
        >

        <label class="form-label">Status</label>
        <select name="status" class="form-control mb-3">
            <option value="ativo" <?= $profissional['status'] == 'ativo' ? 'selected' : '' ?>>
                Ativo
            </option>

            <option value="inativo" <?= $profissional['status'] == 'inativo' ? 'selected' : '' ?>>
                Inativo
            </option>
        </select>

        <button class="btn btn-success">
            Salvar alterações
        </button>

        <a href="painel-clinica.php" class="btn btn-secondary">
            Voltar
        </a>

    </form>

</body>
</html>