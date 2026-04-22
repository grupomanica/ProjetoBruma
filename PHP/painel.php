<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/painel.css">
</head>

<body>

<!-- 🔝 NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">Meu Painel</span>
    <!-- 🔌 BACKEND -->
    <span class="text-white" id="userNameNav">Olá, Usuário</span>
</nav>

<div class="d-flex">

    <!-- 📚 SIDEBAR -->
    <div class="sidebar p-3">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Perfil</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Configurações</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="#">Sair</a></li>
        </ul>
    </div>

    <!-- 📊 CONTEÚDO -->
    <div class="container-fluid p-4">

        <div class="row">

            <!-- 📅 AGENDAMENTOS -->
            <div class="col-lg-8">
                <h3>Meus Agendamentos</h3>

                <!-- Estados -->
                <div id="loadingState">Carregando...</div>
                <div id="errorState" class="text-danger d-none">Erro ao carregar dados.</div>
                <div id="emptyState" class="d-none">Nenhum agendamento encontrado.</div>

                <!-- Lista -->
                <div id="appointmentsList"></div>
            </div>

            <!-- 👤 PERFIL -->
            <div class="col-lg-4">
                <h3>Meu Perfil</h3>

                <div class="card p-3">
                    <p><strong>Nome:</strong> <span id="userName">-</span></p>
                    <p><strong>Email:</strong> <span id="userEmail">-</span></p>
                    <p><strong>Telefone:</strong> <span id="userPhone">-</span></p>

                    <button class="btn btn-primary mt-2">Editar dados</button>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JS -->
<script src="js/painel.js"></script>

</body>
</html>