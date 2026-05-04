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

} catch (PDOException $e) {
    die("Erro ao buscar serviços: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
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
                            <input 
                                type="hidden" 
                                name="servico_id" 
                                value="<?= $row['id'] ?>"
                            >

                            <input 
                                type="hidden" 
                                name="servico" 
                                value="<?= htmlspecialchars($row['nome']) ?>"
                            >

                            <input 
                                type="hidden" 
                                name="clinica" 
                                value="<?= htmlspecialchars($row['nome_clinica']) ?>"
                            >

                            <input 
                                type="hidden" 
                                name="valor" 
                                value="<?= $row['valor'] ?>"
                            >

                            <button class="btn btn-primary w-100">
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

</body>
</html>