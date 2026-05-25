<?php
session_start();
require_once("conexao.php");

// impede acesso direto sem login
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

$nome = $_SESSION['usuario_nome'];
$usuario_id = $_SESSION['usuario_id'];

$usuario = [];
$agendamentos = [];

try {
    $pdo = conectar();

    $stmt = $pdo->prepare("
        SELECT nome, sobrenome, email, telefone, data_nascimento
        FROM usuarios
        WHERE id = ?
    ");

    $stmt->execute([$usuario_id]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        // BUSCAR AGENDAMENTOS DO USUÁRIO
    $stmtAgendamentos = $pdo->prepare("
    SELECT 
        a.status_agendamento,

        s.nome AS servico,

        c.nome AS clinica,

        h.data_disponivel,
        h.horario

    FROM agendamentos a

    INNER JOIN servicos s
        ON a.servico_id = s.id

    INNER JOIN clinicas c
        ON a.clinica_id = c.id

    INNER JOIN horarios_disponiveis h
        ON a.horario_id = h.id

    WHERE a.usuario_id = ?

    ORDER BY h.data_disponivel DESC, h.horario DESC
");
    $stmtAgendamentos->execute([$usuario_id]);

    $agendamentos = $stmtAgendamentos->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $usuario = [];
}
$favoritos = [];

$stmtFavoritos = $pdo->prepare("

    SELECT DISTINCT

        c.nome,
        c.email,
        c.telefone,
        c.bairro

    FROM favoritos f

    INNER JOIN clinicas c
        ON c.id = f.clinica_id

    WHERE f.usuario_id = ?

");

$stmtFavoritos->execute([
    $usuario_id
]);

$favoritos = $stmtFavoritos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" alt="logo" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../CSS/painel.css">
    <script src="../JS/painel.js" defer></script>
    <title>Bruma | Meu Painel</title>
</head>

<body>
    <!-- MENU LATERAL/SIDEBAR -->
    <div class="sidebar">
        <!-- Logo -->
        <img src="../ASSETS/IMG/logo-cNomeFullSize.png">
        
        <!-- Itens do Menu -->
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

        <a href="servicos.php">
            <i class="bi bi-grid"></i> Explorar serviços
        </a>

        <a href="login.php">
            <i class="bi bi-box-arrow-right"></i> Sair
        </a>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="user">                
                <img src="https://i.pravatar.cc/100">
                <span><?php echo $nome; ?></span>
            </div>
        </div>

        <!-- CONTEÚDO -->
        <div class="content-box">

            <!-- DASHBOARD -->
            <div class="page" id="dashboard">
                <h3>Olá, <?php echo $nome; ?> 👋 </h3>
                <p>Boas-vindas ao seu painel Bruma</p>

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

                <div class="mt-4">
                    <a href="servicos.php" class="btn btn-primary">
                        Agendar novo serviço
                    </a>
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
            <th>Horário</th>
            <th>Status</th>
     </tr></thead>
                  <tbody>

<?php if(count($agendamentos) > 0): ?>

    <?php foreach($agendamentos as $agendamento): ?>

        <tr>

            <!-- Clínica -->
            <td>
                <?= htmlspecialchars($agendamento['clinica']) ?>
            </td>

            <!-- Serviço -->
            <td>
                <?= htmlspecialchars($agendamento['servico']) ?>
            </td>

            <!-- Data -->
            <td>
                <?= date(
                    'd/m/Y',
                    strtotime($agendamento['data_disponivel'])
                ) ?>
            </td>

            <!-- Horário -->
            <td>
                <?= substr($agendamento['horario'], 0, 5) ?>
            </td>

            <!-- Status -->
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

    <?php endforeach; ?>

<?php else: ?>

    <tr>
        <td colspan="5">
            Nenhum agendamento encontrado
        </td>
    </tr>

<?php endif; ?>

</tbody>
                </table>
            </div>

            <!-- FAVORITOS -->
            <div class="page d-none" id="favoritos">
                <h3>Clínicas favoritas</h3>
                <?php if(count($favoritos) > 0): ?>

    <div class="row mt-3">

        <?php foreach($favoritos as $favorito): ?>

            <div class="col-md-4 mb-3">

                <div class="card shadow-sm p-3 h-100">

                    <h5>
                        <?= htmlspecialchars($favorito['nome']) ?>
                    </h5>

                    <p class="mb-1">
                        <strong>Região:</strong>
                        <?= htmlspecialchars($favorito['bairro']) ?>
                    </p>

                    <p class="mb-1">
                        <strong>Telefone:</strong>
                        <?= htmlspecialchars($favorito['telefone']) ?>
                    </p>

                    <p class="text-muted small">
                        <?= htmlspecialchars($favorito['email']) ?>
                    </p>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="alert alert-secondary mt-3">
        Nenhuma clínica favoritada ainda
    </div>

<?php endif; ?>
            </div>

            <!-- PERFIL -->
            <div class="page d-none" id="perfil">
                <h3>Meus dados</h3>
                <form class="mt-3" action="atualizar-perfil.php" method="POST">
                    <input
                        class="form-control mb-2"
                        name= "nome"
                        placeholder="Nome"
                        value="<?= $usuario['nome'] ?? '' ?>"
                    >

                    <input
                        class="form-control mb-2"
                        name= "sobrenome"
                        placeholder="Sobrenome"
                        value="<?= $usuario['sobrenome'] ?? '' ?>"
                    >

                    <input
                        class="form-control mb-2"
                        name= "email"
                        placeholder="E-mail"
                        value="<?= $usuario['email'] ?? '' ?>"
                    >

                    <input
                        class="form-control mb-2"
                        name= "telefone"
                        placeholder="Telefone"
                        value="<?= $usuario['telefone'] ?? '' ?>"
                    >

                    <input 
                        type="date"
                        class="form-control mb-2"
                        name="data_nascimento"
                        value="<?= $usuario['data_nascimento'] ?? '' ?>"
                    >

                    <button class="btn btn-success">
                        Salvar alterações
                    </button>
                </form>
            </div>

        </div>

    </div>
</body>
</html>