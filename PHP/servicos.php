<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once("conexao.php");

$pdo = conectar();

try {

    $sql = "
        SELECT 
            s.id,
            s.nome,
            s.descricao,
            s.valor,
            s.clinica_id,
            c.nome AS nome_clinica,
            c.bairro
        FROM servicos s

        INNER JOIN clinicas c
            ON s.clinica_id = c.id

        ORDER BY s.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $servicos = $stmt->fetchAll();

    $favoritos = [];

$stmtFavoritos = $pdo->prepare("
    SELECT clinica_id
    FROM favoritos
    WHERE usuario_id = ?
");

$stmtFavoritos->execute([
    $_SESSION['usuario_id']
]);

$favoritos = $stmtFavoritos->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {

    die("Erro ao buscar serviços: " . $e->getMessage());

}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" alt="logo" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../CSS/servicos.css">

    <title>Bruma | Serviços</title>
</head>

<body>

<!-- HEADER -->
<header class="header-bruma">
    <div class="container d-flex justify-content-between align-items-center">

        <img src="../ASSETS/IMG/logo-horizontal-roxo.png" width="150">

        <div class="d-flex align-items-center gap-3">

            <a href="painel.php" class="perfil-link">
                <i class="bi bi-person-circle"></i> Meu perfil
            </a>

            <a href="logout.php" class="btn btn-outline-dark btn-sm">
                Sair
            </a>

        </div>
    </div>
</header>

<!-- FILTROS -->
<section class="container filtros mt-4">

    <div class="row g-3">

        <!-- filtro serviço -->
        <div class="col-md-4">
            <div class="filtro-box">

                <i class="bi bi-stars"></i>

                <select class="form-select">
                    <option value="">Serviço</option>
                    <option>Limpeza</option>
                    <option>Botox</option>
                    <option>Peeling Químico</option>
                    <option>Microagulhamento</option>
                    <option>Radiofrequência facial</option>
                    <option>Skinbooster</option>
                    <option>Harmonização facial</option>
                    <option>Preenchimento Labial</option>
                    <option>Bichectomia</option>
                    <option>Bioestimuladores de colágeno</option>
                    <option>Fios de sustentação</option>
                    <option>Lipo enzimática</option>
                    <option>Drenagem linfática</option>
                    <option>Massagem modeladora</option>
                    <option>Criolipólise</option>
                    <option>Carboxiterapia</option>
                    <option>Tratamento para celulite</option>
                    <option>Detox corporal</option>
                </select>

            </div>
        </div>

        <!-- filtro região -->
        <div class="col-md-4">
            <div class="filtro-box">

                <i class="bi bi-geo-alt"></i>

                <select class="form-select">
                    <option value="">Região</option>
                    <option>Central</option>
                    <option>Norte</option>
                    <option>Sul</option>
                    <option>Leste</option>
                    <option>Oeste</option>
                </select>

            </div>
        </div>

        <!-- filtro preço -->
        <div class="col-md-4">
            <div class="filtro-box">

                <i class="bi bi-currency-dollar"></i>

                <select class="form-select">
                    <option value="">Preço</option>
                    <option>Até R$100</option>
                    <option>R$100 - R$200</option>
                    <option>R$200 - R$400</option>
                    <option>Acima de R$400</option>
                </select>

            </div>
        </div>

    </div>
</section>

<!-- LISTA DE SERVIÇOS -->
<section class="container mt-5">

    <h3 class="mb-4">Serviços disponíveis</h3>

    <div class="row g-4">

        <?php if(count($servicos) > 0): ?>

            <?php foreach($servicos as $row): ?>

         <div class="col-md-4">

    <div class="card shadow-sm h-100 p-3">

        <!-- FAVORITAR -->
        <div class="d-flex justify-content-end mb-2">

            <form action="favoritar.php" method="POST">

                <input
                    type="hidden"
                    name="clinica_id"
                    value="<?= $row['clinica_id'] ?>"
                >

                <button
                    type="submit"
                    class="btn border-0 bg-transparent p-0"
                >

<?php if(in_array($row['clinica_id'], $favoritos)): ?>

                    <i class="bi bi-heart-fill text-danger fs-4"></i>

<?php else: ?>

                    <i class="bi bi-heart text-dark fs-4"></i>

<?php endif; ?>

                </button>

            </form>

        </div>

        <h5>
            <?= htmlspecialchars($row['nome']) ?>
        </h5>

        <p class="text-muted mb-1">
            <strong>Clínica:</strong>
            <?= htmlspecialchars($row['nome_clinica']) ?>
        </p>

        <p>
            <?= htmlspecialchars($row['descricao']) ?>
        </p>

        <p>
            <strong>Região:</strong>
            <?= htmlspecialchars($row['bairro']) ?>
        </p>

        <p class="text-success fw-bold">
            A partir de R$
            <?= number_format($row['valor'], 2, ',', '.') ?>
        </p>

        <form action="agendamento.php" method="POST">

            <!-- IDs -->
            <input 
                type="hidden" 
                name="servico_id" 
                value="<?= $row['id'] ?>"
            >

            <input 
                type="hidden" 
                name="clinica_id" 
                value="<?= $row['clinica_id'] ?>"
            >

            <input 
                type="hidden" 
                name="valor" 
                value="<?= $row['valor'] ?>"
            >

            <input 
                type="hidden" 
                name="servico"
                value="<?= $row['nome'] ?>"
            >

            <input 
                type="hidden" 
                name="clinica"
                value="<?= $row['nome_clinica'] ?>"
            >

            <input 
                type="hidden" 
                name="endereco"
                value="<?= $row['bairro'] ?>"
            >

<?php

$stmtHorarios = $pdo->prepare("
    SELECT *
    FROM horarios_disponiveis
    WHERE servico_id = ?
    AND status = 'livre'
    AND data_disponivel >= CURDATE()
    ORDER BY data_disponivel ASC, horario ASC
");

$stmtHorarios->execute([
    $row['id']
]);

$horarios = $stmtHorarios->fetchAll(PDO::FETCH_ASSOC);

?>

            <div class="horarios-box mb-3">

<?php

$datas = [];

foreach($horarios as $h){

    $datas[$h['data_disponivel']][] = $h;
}

?>

<?php if(count($datas) > 0): ?>

<?php foreach($datas as $data => $listaHorarios): ?>

    <button
        type="button"
        class="btn btn-outline-primary mb-2"
        data-bs-toggle="modal"
        data-bs-target="#modal<?= md5($data . $row['id']) ?>"
    >

        <?= date('d/m/Y', strtotime($data)) ?>

    </button>

    <!-- MODAL -->
    <div
        class="modal fade"
        id="modal<?= md5($data . $row['id']) ?>"
        tabindex="-1"
    >

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Horários disponíveis
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

<?php foreach($listaHorarios as $horario): ?>

    <div class="mb-2">

        <input
            type="radio"
            class="btn-check"
            name="horario"
            value="<?= $horario['id'] ?>|<?= $horario['data_disponivel'] ?>|<?= $horario['horario'] ?>"
            id="horario<?= $horario['id'] ?>"
            required
        >

        <label
            class="btn btn-outline-dark w-100"
            for="horario<?= $horario['id'] ?>"
            onclick="
                document.getElementById(
                    'horarioSelecionado<?= md5($data . $row['id']) ?>'
                ).innerHTML = 'Horário selecionado: <?= substr($horario['horario'], 0, 5) ?>';

                bootstrap.Modal.getInstance(
                    document.getElementById(
                        'modal<?= md5($data . $row['id']) ?>'
                    )
                ).hide();
            "
        >

            <?= substr($horario['horario'], 0, 5) ?>

        </label>

    </div>

<?php endforeach; ?>

                </div>

            </div>
        </div>

    </div>

    <!-- TEXTO HORÁRIO -->
    <div
        id="horarioSelecionado<?= md5($data . $row['id']) ?>"
        class="small text-success mb-3"
    ></div>

<?php endforeach; ?>

<?php else: ?>

    <p class="text-muted">
        Nenhum horário disponível
    </p>

<?php endif; ?>

            </div>

            <button type="submit" class="btn btn-primary w-100">
                Agendar
            </button>

        </form>

    </div>

</div>    

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-warning text-center">
                    Nenhum serviço cadastrado pelas clínicas ainda.
                </div>

            </div>

        <?php endif; ?>

    </div>

</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




</body>
</html>