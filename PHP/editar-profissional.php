<?php
session_start();

if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

require_once("conexao.php");

$pdo = conectar();

$id = $_GET['id'] ?? 0;

if (!$id){
    die("Profissional inválido.");
}

$sql = "SELECT * FROM profissionais WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([':id' => $id]);

$profissional = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profissional) {
    die("Profissional não encontrado.");
}

$diasSelecionados = explode(',', $profissional['dias_semana']);
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

    <form action="salvar-edicao-profissiona.php" method="POST">

        <input type="hidden" name="id" value="<?= $profissional['id']?>">
        
        <!-- Nome -->
        <label class="form-label">Nome completo</label>
        <input type="text" name="nome" class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['nome']) ?>" required>
        
        <!-- Registro -->
        <label class="form-label">Registro Profissional</label>
        <input type="text" name="registro" class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['registro']) ?>" required>

        <!-- Especialidade -->
        <label class="form-label">Especialidade</label>
        <input type="text" name="especialidade" class="form-control mb-3"
            value="<?= htmlspecialchars($profissionais['especialidade']) ?>" required>

        <!-- Telefone -->
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['telefone']) ?>" required>

        <!-- Email -->
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control mb-3"
            value="<?= htmlspecialchars($profissional['email']) ?>" required>

        <!-- Horário -->
         <label class="form-label">Horário de trabalho</label>
         <div class="row mb-3">
            <div class="col-6">
                <input type="time" name="hora_inicio" class="form-control"
                    value="<?= $profissional['hora_inicio'] ?>" required>
            </div>

            <div class="col-6">
                <input type="time" name="hora_fim" class="form-control"
                    value="<?= $profissional['hora_fim']?>" required>
            </div>
         </div>
        
        <!-- Dias -->
         <label class="form-label"> Dias da semana</label>
         <?php $dias = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo']; ?>
         <div class="mb-3">
            <?php foreach($dias as $dia): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dias_semana[]"
                        value="<?= $dia ?>" <?= in_array($dia, $diasSelecionados) ? 'checked': '' ?>>
                    <label class="form-check-label"><?= $dia ?></label>
                </div>
            <?php endforeach; ?>
         </div>

        <!-- Status -->
        <label class="form-label">Status</label>
        <select name="status" class="form-control mb-4" required>
            <option value="ativo" <?= $profissional['status'] == 'ativo' ? 'selected' : '' ?>>
                Ativo
            </option>

            <option value="inativo" <?= $profissional['status'] == 'inativo' ? 'selected' : '' ?>>
                Inativo
            </option>
        </select>
        
        <div class="d-flex gap-2">
            <a href="painel-clinica.php" class="btn btn-secondary">Voltar</a>

            <button type="submit" class="btn btn-success">
                Salvar alterações
            </button>
        </div>
    </form>
</body>
</html>