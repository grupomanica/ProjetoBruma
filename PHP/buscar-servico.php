<?php

session_start();
require_once("conexao.php");

if(!isset($_GET['id'])){
    exit;
}

$pdo = conectar();

$sql = "
    SELECT *
    FROM servicos
    WHERE id = :id
    AND clinica_id = :clinica_id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $_GET['id'],
    ':clinica_id' => $_SESSION['clinica_id']
]);

$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$servico){
    exit;
}
?>

<form
    action="salvar-edicao-servico.php"
    method="POST"
>

    <input
        type="hidden"
        name="id"
        value="<?= $servico['id'] ?>"
    >

    <div class="mb-3">
        <label class="form-label">
            Nome
        </label>

        <input
            type="text"
            name="nome"
            class="form-control"
            value="<?= htmlspecialchars($servico['nome']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">
            Descrição
        </label>

        <textarea
            name="descricao"
            class="form-control"
            rows="3"
            required
        ><?= htmlspecialchars($servico['descricao']) ?></textarea>
    </div>

    <div class="row">

        <div class="col-md-4">
            <label class="form-label">
                Sessões
            </label>

            <input
                type="number"
                name="sessoes"
                class="form-control"
                value="<?= $servico['sessoes'] ?>"
                required
            >
        </div>

        <div class="col-md-4">
            <label class="form-label">
                Valor
            </label>

            <input
                type="number"
                step="0.01"
                name="valor"
                class="form-control"
                value="<?= $servico['valor'] ?>"
                required
            >
        </div>

        <div class="col-md-4">
            <label class="form-label">
                Duração
            </label>

            <input
                type="number"
                name="duracao"
                class="form-control"
                value="<?= $servico['duracao'] ?>"
                required
            >
        </div>

    </div>

    <div class="mt-4 d-flex gap-2">

        <button
            type="submit"
            class="btn btn-success"
        >
            Salvar Alterações
        </button>

        <button
            type="button"
            id="cancelar-edicao"
            class="btn btn-secondary"
        >
            Cancelar
        </button>

    </div>

</form>