<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/servicos.css">
    <script src="../JS/servicos.js" defer></script>

    <title>Bruma | Serviços</title>
</head>

<body>

<!-- HEADER -->
<header class="header-bruma">
    <div class="container d-flex justify-content-between align-items-center">

        <a href="../index.html" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            <img src="../ASSETS/IMG/logo-horizontal-roxo.png" class="bi me-2" width="150" role="img">
        </a>

        <div class="d-flex align-items-center gap-3">
            <a href="#" class="perfil-link">
                <i class="bi bi-person-circle"></i> Meu perfil
            </a>
            <a href="login.php" class="btn btn-outline-dark btn-sm">
                Sair
            </a>
        </div>
    </div>
</header>

<!-- FILTROS -->
<section class="container filtros">

    <div class="row g-3">

        <div class="col-md-3">
            <div class="filtro-box">
                <i class="bi bi-stars"></i>
                <select class="form-select filtro" data-filter="tipo">
                    <option value="">Serviço</option>
                    <option>Limpeza</option>
                    <option>Botox</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="filtro-box">
                <i class="bi bi-geo-alt"></i>
                <select class="form-select filtro" data-filter="bairro">
                    <option value="">Bairro</option>
                    <option>Moema</option>
                    <option>Pinheiros</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
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

<!-- RESULTADOS -->
<section class="container">

    <div class="result-count" id="resultado-count"></div>

    <div class="row g-3" id="lista-servicos"></div>

</section>

</body>
</html>