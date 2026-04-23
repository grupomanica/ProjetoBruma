<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="../CSS/painel-clinica.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../JS/painel-clinica.js" defer></script>

<title>Bruma | Painel da Clínica</title>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <img src="../ASSETS/IMG/logo-cNomeFullSize.png">

    <a href="#" class="menu-item active" data-page="dashboard">
        <i class="bi bi-house"></i> Dashboard
    </a>

    <a href="#" class="menu-item" data-page="servicos">
        <i class="bi bi-scissors"></i> Serviços
    </a>

    <a href="#" class="menu-item" data-page="horarios">
        <i class="bi bi-clock"></i> Horários
    </a>

    <a href="#" class="menu-item" data-page="agendamentos">
        <i class="bi bi-calendar-check"></i> Agendamentos
    </a>

    <a href="#" class="menu-item" data-page="profissionais">
        <i class="bi bi-people"></i> Profissionais
    </a>

    <a href="#" class="menu-item" data-page="perfil">
        <i class="bi bi-gear"></i> Perfil
    </a>

</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="user">
            <img src="https://i.pravatar.cc/100">
            <span>Clínica (sem BD)</span>
        </div>
    </div>

    <!-- CONTEÚDO -->
    <div class="content-box">

        <!-- DASHBOARD -->
        <div class="page" id="dashboard">

            <h3>Resumo da Clínica</h3>

            <div class="row g-3 mt-2">

                <div class="col-md-4">
                    <div class="card-mini">
                        <span>Agendamentos hoje</span>
                        <strong>--</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-mini">
                        <span>Serviços ativos</span>
                        <strong>--</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-mini">
                        <span>Profissionais</span>
                        <strong>--</strong>
                    </div>
                </div>

            </div>
        </div>

        <!-- SERVIÇOS -->
        <div class="page d-none" id="servicos">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Serviços</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalServico">
                    + Novo Serviço
                </button>
            </div>

            <div class="alert alert-warning">
                Nenhum serviço cadastrado (sem conexão com banco)
            </div>

        </div>

        <!-- HORÁRIOS -->
        <div class="page d-none" id="horarios">

            <h3>Horários Disponíveis</h3>

            <div class="alert alert-info">
                Configure os horários da clínica (dados não conectados)
            </div>

        </div>

        <!-- AGENDAMENTOS -->
        <div class="page d-none" id="agendamentos">

            <h3>Agendamentos</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Serviço</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">Nenhum agendamento (sem BD)</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <!-- PROFISSIONAIS -->
        <div class="page d-none" id="profissionais">

            <div class="d-flex justify-content-between mb-3">
                <h3>Profissionais</h3>
                <button class="btn btn-primary">+ Novo</button>
            </div>

            <div class="alert alert-secondary">
                Nenhum profissional cadastrado
            </div>

        </div>

        <!-- PERFIL -->
        <div class="page d-none" id="perfil">

            <h3>Dados da Clínica</h3>

            <form class="mt-3">

                <input class="form-control mb-2" placeholder="Nome da clínica">
                <input class="form-control mb-2" placeholder="Telefone">
                <input class="form-control mb-2" placeholder="Endereço">

                <button class="btn btn-success">Salvar</button>

            </form>

        </div>

    </div>

</div>

<!-- MODAL NOVO SERVIÇO -->
<div class="modal fade" id="modalServico" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="salvar_servico.php" method="POST">

        <div class="modal-header">
          <h5 class="modal-title">Novo Serviço</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <label class="form-label">Nome do serviço</label>
          <input type="text" name="nome" class="form-control mb-2" required>

          <label class="form-label">Descrição</label>
          <textarea name="descricao" class="form-control mb-2" required></textarea>

          <label class="form-label">Quantidade de sessões</label>
          <input type="number" name="sessoes" class="form-control mb-2" min="1" required>

          <label class="form-label">Valor (R$)</label>
          <input type="number" name="valor" class="form-control mb-2" step="0.01" required>

          <label class="form-label">Duração (minutos)</label>
          <input type="number" name="duracao" class="form-control mb-2" required>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-success">
            Salvar Serviço
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

</body>
</html>