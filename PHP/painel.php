<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="../CSS/painel.css">
<script src="../JS/painel.js" defer></script>

<title>Bruma | Meu Painel</title>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <img src="../ASSETS/IMG/logo-cNomeFullSize.png">

    <a href="#" class="menu-item active" data-page="dashboard">
        <i class="bi bi-house"></i> Início
    </a>

    <a href="#" class="menu-item" data-page="agendamentos">
        <i class="bi bi-calendar-check"></i> Meus agendamentos
    </a>

    <a href="#" class="menu-item" data-page="favoritos">
        <i class="bi bi-heart"></i> Favoritos
    </a>

    <a href="#" class="menu-item" data-page="perfil">
        <i class="bi bi-person"></i> Meu perfil
    </a>

    <a href="login.php">
        <i class="bi bi-box-arrow-right"></i> Sair
    </a>

</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="user">
            <img src="https://i.pravatar.cc/100">
            <span>Usuário (sem BD)</span>
        </div>
    </div>

    <!-- CONTEÚDO -->
    <div class="content-box">

        <!-- DASHBOARD -->
        <div class="page" id="dashboard">

            <h3>Olá 👋</h3>
            <p>Bem-vindo ao seu painel Bruma</p>

            <div class="row g-3 mt-2">

                <div class="col-md-4">
                    <div class="card-mini">
                        <span>Próximo agendamento</span>
                        <strong>--</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-mini">
                        <span>Agendamentos realizados</span>
                        <strong>--</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-mini">
                        <span>Favoritos</span>
                        <strong>--</strong>
                    </div>
                </div>

            </div>
        </div>

        <!-- AGENDAMENTOS -->
        <div class="page d-none" id="agendamentos">

            <h3>Meus agendamentos</h3>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Clínica</th>
                        <th>Serviço</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">Nenhum agendamento encontrado</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <!-- FAVORITOS -->
        <div class="page d-none" id="favoritos">

            <h3>Clínicas favoritas</h3>

            <div class="alert alert-secondary mt-3">
                Nenhuma clínica favoritada ainda
            </div>

        </div>

        <!-- PERFIL -->
        <div class="page d-none" id="perfil">

            <h3>Meus dados</h3>

            <form class="mt-3">

                <input class="form-control mb-2" placeholder="Nome">
                <input class="form-control mb-2" placeholder="E-mail">
                <input class="form-control mb-2" placeholder="Telefone">

                <button class="btn btn-success">Salvar</button>

            </form>

        </div>

    </div>

</div>

</body>
</html>