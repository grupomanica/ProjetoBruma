<?php
session_start();

// Verifica se a clínica está logada
if (!isset($_SESSION['clinica_id'])) {
    header("Location: login-clinica.php");
    exit();
}

// Dados da sessão
$nomeClinica = $_SESSION['clinica_nome'];
$emailClinica = $_SESSION['clinica_email'];
$telefoneClinica = $_SESSION['clinica_telefone'] ?? '';
$cepClinica = $_SESSION['clinica_cep'] ?? '';
$clinica_id = $_SESSION['clinica_id'];




require_once("conexao.php");

$pdo = conectar();

$clinica_id = $_SESSION['clinica_id'];

try {
    $sql = "SELECT * FROM servicos WHERE clinica_id = :clinica_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':clinica_id' => $clinica_id
    ]);

    $servicos = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erro ao buscar serviços: " . $e->getMessage());
}

try {
    $sqlProfissionaisAtivos = "
        SELECT COUNT(*) as total_profissionais
        FROM profissionais
        WHERE clinica_id = :clinica_id
        AND status = 'ativo'
    ";

    $stmtProfissionaisAtivos = $pdo->prepare($sqlProfissionaisAtivos);

    $stmtProfissionaisAtivos->execute([
        ':clinica_id' => $clinica_id
    ]);

    $resultadoProfissionais = $stmtProfissionaisAtivos->fetch(PDO::FETCH_ASSOC);

    $totalProfissionaisAtivos = $resultadoProfissionais['total_profissionais'];

} catch (PDOException $e) {
    $totalProfissionaisAtivos = 0;
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

        <a href="logout-clinica.php">
            <i class="bi bi-box-arrow-right"></i> Sair
        </a>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="topbar">
            <div class="user">
                <img src="https://i.pravatar.cc/100">
                <span><?= htmlspecialchars($nomeClinica) ?></span>
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
                            <strong><?= count($servicos) ?></strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-mini">
                            <span>Profissionais</span>
                            <strong><?= $totalProfissionaisAtivos ?></strong>
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

                <?php if (count($servicos) > 0): ?>

                    <div class="row">
                        <?php foreach ($servicos as $servico): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card p-3 shadow-sm">
                                    <h5><?= htmlspecialchars($servico['nome']) ?></h5>

                                    <p class="text-muted small">
                                        <?= htmlspecialchars($servico['descricao']) ?>
                                    </p>

                                    <p>
                                        <strong>Duração:</strong>
                                        <?= $servico['duracao'] ?> min
                                    </p>

                                    <p class="text-success fw-bold">
                                        R$ <?= number_format($servico['valor'], 2, ',', '.') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Nenhum serviço cadastrado ainda.
                    </div>
                <?php endif; ?>
            </div>

            <!-- HORÁRIOS -->
            <div class="page d-none" id="horarios">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Todos os Horários</h3>
                    
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalHorarios">
                            + Novo Horário
                        </button>
                </div>
    <?php

    try {

    $sqlHorarios = "
        SELECT h.*,
            CASE
                WHEN a.id IS NOT NULL THEN 'Ocupado'
                ELSE 'Livre'
            END AS status_horario
        FROM horarios_disponiveis h

        LEFT JOIN agendamentos a
            ON a.horario_id = h.id
        WHERE h.clinica_id = :clinica_id
        ORDER BY h.data_disponivel ASC, h.horario ASC
    ";

        $stmtHorarios = $pdo->prepare($sqlHorarios);
        $stmtHorarios->execute([
            ':clinica_id' => $clinica_id
        ]);

        $horarios = $stmtHorarios->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $horarios = [];
    }
    ?>

    <?php if(count($horarios) > 0): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php foreach($horarios as $horario): ?>
                            <tr>
                                <td>
                                    <?= date('d/m/Y', strtotime($horario['data_disponivel'])) ?>
                                </td>
                                <td>
                                    <a href="alterar-horario-agendamento.php?id=<?= $horario['id'] ?>"
                                        class="text-decoration-none fw-semibold">
                                        <?= substr($horario['horario'], 0, 5) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if($horario['status_horario'] == 'Livre'): ?>
                                        <span class="badge bg-success">
                                            Livre
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            Ocupado
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>     
                </table>
            </div>
        </div>
        <?php else: ?>
            <div class="alert alert-warning">
                Nenhum horário disponível.
            </div>
        <?php endif; ?>  
    </div>


    <!-- MODAL HORÁRIOS -->
    <div class="modal fade" id="modalHorarios" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="salvar_horario.php" method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Novo Horário Disponível
                        </h5>

                        <button 
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>
                    </div>

                    <div class="modal-body">

                    <label class="mb-1">Serviço</label>

    <select
        name="servico_id"
        class="form-control mb-3"
        required
    >

        <option value="">
            Selecione um serviço
        </option>

        <?php foreach($servicos as $servico): ?>

            <option value="<?= $servico['id'] ?>">

                <?= htmlspecialchars($servico['nome']) ?>

            </option>

        <?php endforeach; ?>

    </select>

                        <label class="mb-1">Data</label>

                        <input 
                            type="date"
                            name="data"
                            class="form-control mb-3"
                            required
                        >

                        <label class="mb-1">Horário</label>

                        <input 
                            type="time"
                            name="hora"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="modal-footer">

                        <button 
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button 
                            type="submit"
                            class="btn btn-success"
                        >
                            Salvar horário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AGENDAMENTOS -->
    <div class="page d-none" id="agendamentos">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Agendamentos</h3>
        </div>

    <?php

    try {
        $sqlAgendamentos = "
        SELECT a.id,
            a.status_agendamento,
            a.status_pagamento,
            a.created_at,

            u.nome AS cliente_nome,
            u.sobrenome AS cliente_sobrenome,
            u.telefone AS telefone,
            u.email AS cliente_email,

            s.nome AS servico_nome,

            p.nome AS profissional_nome,

            h.id AS horario_id,
            h.data_disponivel,
            h.horario

            FROM agendamentos a

            INNER JOIN usuarios u
                ON u.id = a.usuario_id

            INNER JOIN servicos s
                ON s.id = a.servico_id

            INNER JOIN horarios_disponiveis h
                ON h.id = a.horario_id

            LEFT JOIN profissionais p
                ON p.id = a.profissional_id

            WHERE a.clinica_id = :clinica_id

            ORDER BY h.data_disponivel DESC, h.horario DESC
        ";

        $stmtAgendamentos = $pdo->prepare($sqlAgendamentos);

        $stmtAgendamentos->execute([
            ':clinica_id' => $clinica_id
        ]);

        $agendamentos = $stmtAgendamentos->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $agendamentos = [];
    }
    ?>

    <?php if(count($agendamentos) > 0): ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Serviço</th>
                                <th>Profissional</th>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Pagamento</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($agendamentos as $agendamento): ?>
                            <tr>
                                <td>
                                    <a href="#" class="text-decoration-none fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#modalCliente<?= $agendamento['id'] ?>">
                                        <?= htmlspecialchars(
                                            $agendamento['cliente_nome'] . ' ' .
                                            $agendamento['cliente_sobrenome']
                                        ) ?>
                                    </a>
                                </td>

        <!-- MODAL ALTERAR HORÁRIO -->
    <div
        class="modal fade"
        id="modalHorario<?= $agendamento['id'] ?>"
        tabindex="-1"
    >

        <div class="modal-dialog">

            <div class="modal-content">

    <?php

    $sqlHorariosDisponiveis = "

        SELECT
            h.id,
            h.horario

        FROM horarios_disponiveis h

        LEFT JOIN agendamentos a
            ON a.horario_id = h.id

        WHERE h.clinica_id = :clinica_id

        AND h.data_disponivel = :data

        AND (
            a.id IS NULL
            OR h.id = :horario_atual
        )

        ORDER BY h.horario ASC

    ";

    $stmtHorariosDisponiveis = $pdo->prepare($sqlHorariosDisponiveis);

    $stmtHorariosDisponiveis->execute([
        ':clinica_id' => $clinica_id,
        ':data' => $agendamento['data_disponivel'],
        ':horario_atual' => $agendamento['horario_id']
    ]);

    $horariosDisponiveis = $stmtHorariosDisponiveis->fetchAll(PDO::FETCH_ASSOC);

    ?>

                <form action="alterar-horario-agendamento.php" method="POST">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Alterar horário
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <input
                            type="hidden"
                            name="agendamento_id"
                            value="<?= $agendamento['id'] ?>"
                        >

                        <label class="form-label">
                            Horários disponíveis
                        </label>

                        <select
                            name="horario_id"
                            class="form-control"
                            required
                        >

    <?php foreach($horariosDisponiveis as $horarioDisponivel): ?>

        <option
            value="<?= $horarioDisponivel['id'] ?>"

            <?= $horarioDisponivel['id'] == $agendamento['horario_id']
                ? 'selected'
                : ''
            ?>
        >

            <?= substr($horarioDisponivel['horario'], 0, 5) ?>

        </option>

    <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Salvar
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

        <td>
            <?= htmlspecialchars($agendamento['servico_nome']) ?>
        </td>

        <td>

        

    <?php if(!empty($agendamento['profissional_nome'])): ?>

        <a
            href="alterar-profissional-agendamento.php?id=<?= $agendamento['id'] ?>"
            class="text-decoration-none fw-semibold"
        >
            <?= htmlspecialchars($agendamento['profissional_nome']) ?>
        </a>

    <?php else: ?>

        <a
            href="alterar-profissional-agendamento.php?id=<?= $agendamento['id'] ?>"
            class="text-danger text-decoration-none"
        >
            Selecionar profissional
        </a>

    <?php endif; ?>

    </td>
        <td>

        <a
            href="alterar-data-agendamento.php?id=<?= $agendamento['id'] ?>"
            class="text-decoration-none fw-semibold"
        >
            <?= date(
                'd/m/Y',
                strtotime($agendamento['data_disponivel'])
            ) ?>
        </a>

    </td>

    <!-- HORÁRIO -->
    <td>

        <a
            href="#"
            class="text-decoration-none fw-semibold"
            data-bs-toggle="modal"
            data-bs-target="#modalHorario<?= $agendamento['id'] ?>"
        >
            <?= substr($agendamento['horario'], 0, 5) ?>
        </a>

    </td>

    <!-- PAGAMENTO -->
    <td>

        <?php if($agendamento['status_pagamento'] == 'pago'): ?>
                <span class="badge bg-success">
                    Pago
                </span>

            <?php else: ?>

                <span class="badge bg-warning text-dark">
                    Pendente
                </span>

            <?php endif; ?>

        </td>

        <td>

            <?php if($agendamento['status_agendamento'] == 'confirmado'): ?>

                <span class="badge bg-success">
                    Confirmado
                </span>

            <?php elseif($agendamento['status_agendamento'] == 'cancelado'): ?>

                <span class="badge bg-danger">
                    Cancelado
                </span>

            <?php elseif($agendamento['status_agendamento'] == 'concluido'): ?>

                <span class="badge bg-primary">
                    Concluído
                </span>

            <?php else: ?>

                <span class="badge bg-secondary">
                    Pendente
                </span>

            <?php endif; ?>

        </td>

    </tr>

    <!-- MODAL CLIENTE -->
    <div
        class="modal fade"
        id="modalCliente<?= $agendamento['id'] ?>"
        tabindex="-1"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Informações do Cliente
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <p>
                        <strong>Nome:</strong><br>

                        <?= htmlspecialchars(
                            $agendamento['cliente_nome'] . ' ' .
                            $agendamento['cliente_sobrenome']
                        ) ?>
                    </p>

                    <p>
                        <strong>Telefone:</strong><br>

                        <?= htmlspecialchars(
                            $agendamento['telefone']
                        ) ?>
                    </p>

                    <p>
                        <strong>E-mail:</strong><br>

                        <?= htmlspecialchars(
                            $agendamento['cliente_email']
                        ) ?>
                    </p>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Fechar
                    </button>

                </div>

            </div>

        </div>

    </div>

    <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    <?php else: ?>

        <div class="alert alert-warning">
            Nenhum agendamento encontrado.
        </div>

    <?php endif; ?>

    </div>

    </div>

            <!-- PROFISSIONAIS -->
            <div class="page d-none" id="profissionais">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Profissionais</h3>

                    <button 
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalProfissional"
                    >
                        + Novo Profissional
                    </button>
                </div>

                <?php
                try {
                    $sqlProfissionais = "SELECT * FROM profissionais 
                                        WHERE clinica_id = :clinica_id
                                        ORDER BY nome ASC";

                    $stmtProfissionais = $pdo->prepare($sqlProfissionais);
                    $stmtProfissionais->execute([
                        ':clinica_id' => $clinica_id
                    ]);

                    $profissionais = $stmtProfissionais->fetchAll();

                } catch (PDOException $e) {
                    $profissionais = [];
                }
                ?>

                <?php if(count($profissionais) > 0): ?>

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table align-middle">

                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Registro</th>
                                            <th>Especialidade</th>
                                            <th>Atendimento</th>
                                            <th>Contato</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach($profissionais as $profissional): ?>
                                            <tr>

                                                <!-- Nome exibido ao cliente -->
                                                <td>
                                                    <?= htmlspecialchars($profissional['nome']) ?>
                                                </td>

                                                <!-- Registro exibido ao cliente -->
                                                <td>
                                                    <?= htmlspecialchars($profissional['registro']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($profissional['especialidade']) ?>
                                                </td>

                                                <td>
                                                    <small>
                                                        <strong>Dias:</strong><br>

                                                        <?= htmlspecialchars(str_replace(',', ', ', $profissional['dias_semana'])
                                                        ) ?>

                                                        <br><br>

                                                        <strong>Horário:</strong><br>

                                                        <?= substr($profissional['hora_inicio'], 0, 5) ?>
                                                        às <?= substr($profissional['hora_fim'], 0, 5) ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    <small>
                                                        <?= htmlspecialchars($profissional['telefone']) ?><br>
                                                        <?= htmlspecialchars($profissional['email']) ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    <?php if($profissional['status'] == 'ativo'): ?>
                                                        <span class="badge bg-success">Ativo</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inativo</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <div class="d-flex gap-2">

                                                        <a 
                                                            href="editar-profissional.php?id=<?= $profissional['id'] ?>"
                                                            class="btn btn-sm btn-warning"
                                                        >
                                                            <i class="bi bi-pencil"></i>
                                                        </a>

                                                        <a 
                                                            href="remover-profissional.php?id=<?= $profissional['id'] ?>"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Deseja remover este profissional?')"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </a>

                                                    </div>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>

                <?php else: ?>

                    <div class="alert alert-secondary">
                        Nenhum profissional cadastrado ainda.
                    </div>

                <?php endif; ?>

            </div>


            <!-- MODAL PROFISSIONAL -->
            <div class="modal fade" id="modalProfissional" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="salvar-profissional.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Cadastrar Profissional</h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <!-- Nome -->
                                <label class="form-label">Nome completo</label>
                                <input type="text" name="nome" class="form-control mb-3"
                                    placeholder="Nome do profissional" required>

                                <!-- Registro -->
                                <label class="form-label">Registro profissional</label>
                                <input type="text" name="registro" class="form-control mb-3"
                                    placeholder="Ex: CRBM, CRO, CRM..." required>

                                <!-- Especialidade -->
                                <label class="form-label">Especialidade</label>
                                <input type="text" name="especialidade" class="form-control mb-3"
                                    placeholder="Ex: Biomédico Esteta" required >

                                <!-- Telefone -->
                                <label class="form-label">Telefone</label>
                                <input type="text" name="telefone" class="form-control mb-3"
                                    placeholder="(11) 99999-9999" required>

                                <!-- Email -->
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control mb-3" placeholder="email@exemplo.com" required>

    <!-- Horário de trabalho -->
    <label class="form-label">Horário de trabalho</label>

    <div class="row mb-3">
        <div class="col-6">
            <input type="time" name="hora_inicio" class="form-control" required>
        </div>

        <div class="col-6">
            <input type="time" name="hora_fim" class="form-control" required>
        </div>
    </div>

    <!-- Dias da semana -->
    <label class="form-label">Dias da semana</label>
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Segunda">
            <label class="form-check-label">Segunda-feira</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Terça">
            <label class="form-check-label">Terça-feira</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Quarta">
            <label class="form-check-label">Quarta-feira</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Quinta">
            <label class="form-check-label">Quinta-feira</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Sexta">
            <label class="form-check-label">Sexta-feira</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Sábado">
            <label class="form-check-label">Sábado</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias_semana[]" value="Domingo">
            <label class="form-check-label">Domingo</label>
        </div>
    </div>
                                <!-- Status -->
                                <label class="form-label">Status</label>
                                <select 
                                    name="status"
                                    class="form-control mb-3"
                                    required
                                >
                                    <option value="ativo">Ativo</option>
                                    <option value="inativo">Inativo</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">
                                    Cadastrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PERFIL -->
            <div class="page d-none" id="perfil">
                <h3>Dados da Clínica</h3>
                <form class="mt-3">
                    <label for="nome">Nome da clínica:</label>
                    <input class="form-control mb-2" name="nome"
                        value="<?= htmlspecialchars($nomeClinica) ?>">

                    <label for="email">E-mail:</label>
                    <input class="form-control mb-2" name="email"
                        value="<?= htmlspecialchars($emailClinica) ?>">

                    <label for="telefone">Telefone:</label>
                    <input class="form-control mb-2" name="telefone"
                        value="<?= htmlspecialchars($telefoneClinica) ?>">

                    <label for="cep">CEP:</label>
                    <input class="form-control mb-2" name="cep"
                        value="<?= htmlspecialchars($cepClinica) ?>">

                    <button class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modalServico" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="salvar-servico.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Serviço</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Tipo do procedimento -->
                        <label class="form-label">Tipo do procedimento</label>
                        <select name="tipo_procedimento" class="form-control mb-3"required>
                            <option value="">Selecione o tipo</option>
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

                        <!-- Nome do serviço -->
                        <label class="form-label">Nome comercial do serviço</label>
                        <input type="text" name="nome" class="form-control mb-3"
                            placeholder="Ex: Botox Premium Full Face" required>

                        <!-- Descrição -->
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control mb-3"
                            placeholder="Descreva o procedimento..." rows="3" required>
                        </textarea>

                        <!-- Sessões -->
                        <label class="form-label">Quantidade de sessões</label>
                        <input type="number" name="sessoes" class="form-control mb-3"
                            placeholder="Ex: 1" min="1" required>

                        <!-- Valor -->
                        <label class="form-label">Valor (R$)</label>
                        <input type="number" name="valor" class="form-control mb-3"
                            placeholder="Ex: 350" step="0.01" min="0" required>

                        <!-- Duração -->
                        <label class="form-label">Duração (minutos)</label>
                        <input type="number" name="duracao" class="form-control mb-2"
                            placeholder="Ex: 60" min="1" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>