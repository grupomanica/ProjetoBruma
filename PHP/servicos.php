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
    <script src="../JS/servicos.js" defer></script>

    <title>Bruma | Serviços</title>
</head>

<body>

<header class="header-bruma">
    <div class="container d-flex justify-content-between align-items-center">
        <img src="../ASSETS/IMG/logo-horizontal-roxo.png" width="150">

        <div class="d-flex align-items-center gap-3">
            <a href="painel.php" class="perfil-link">
                <i class="bi bi-person-circle"></i> Meu perfil
            </a>
            <a href="login.php" class="btn btn-outline-dark btn-sm">Sair</a>
        </div>
    </div>
</header>

<section class="container filtros">
    <div class="row g-3">

        <div class="col-md-4">
            <div class="filtro-box">
                <i class="bi bi-stars"></i>
                <select class="form-select filtro" data-filter="nome">
                    <option value="">Serviço</option>
                    <option>Limpeza</option>
                    <option>Botox</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="filtro-box">
                <i class="bi bi-geo-alt"></i>
                <select class="form-select filtro" data-filter="regiao">
                    <option value="">Região</option>
                    <option value="central">Central</option>
                    <option value="norte">Norte</option>
                    <option value="sul">Sul</option>
                    <option value="leste">Leste</option>
                    <option value="oeste">Oeste</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="filtro-box">
                <i class="bi bi-currency-dollar"></i>
                <select class="form-select filtro" data-filter="preco">
                    <option value="">Preço</option>
                    <option value="0-100">Até R$100</option>
                    <option value="100-200">R$100 - R$200</option>
                    <option value="200-400">R$200 - R$400</option>
                    <option value="400+">Acima de R$400</option>
                </select>
            </div>
        </div>

    </div>
</section>

<section class="container">
    <div class="result-count" id="resultado-count"></div>
    <div class="row g-4" id="lista-servicos"></div>
</section>

<form id="form-agendamento" action="agendamento.php" method="POST" style="display: none;">
    <input type="hidden" name="servico" id="input-servico">
    <input type="hidden" name="clinica" id="input-clinica">
    <input type="hidden" name="valor" id="input-valor">
    <input type="hidden" name="data" id="input-data">
    <input type="hidden" name="hora" id="input-hora">
</form>

</body>
</html>