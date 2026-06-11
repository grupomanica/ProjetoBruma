<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once("conexao.php");

$pdo = conectar();

$servicoFiltro = $_GET['servico'] ?? '';
$regiaoFiltro = $_GET['regiao'] ?? '';
$precoFiltro = $_GET['preco'] ?? '';

try {

    $sql = "
        SELECT DISTINCT 
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

        INNER JOIN horarios_disponiveis h
            ON h.servico_id = s.id

        WHERE h.status = 'livre'
        AND h.data_disponivel >= CURDATE()
    ";

    $params = [];

    //FILTRO SERVIÇO
    if(!empty($servicoFiltro)){

        $sql .= " AND s.tipo_procedimento = :servico";

        $params[':servico'] = $servicoFiltro;
    }

    // FILTRO REGIÃO
    if(!empty($regiaoFiltro)){

        $sql .= " AND c.bairro = :regiao";

        $params[':regiao'] = $regiaoFiltro;
    }

    // FILTRO PRECO
    if(!empty($precoFiltro)){

        if($precoFiltro == '100'){

            $sql .= " AND s.valor <= 100";

        } elseif($precoFiltro == '200'){
            
            $sql .= " AND s.valor > 100
                    AND s.valor <= 200";

        } elseif($precoFiltro == '400'){

            $sql .= " AND s.valor > 200
                        AND s.valor <= 400";

        } elseif($precoFiltro == '999999'){

            $sql .= " AND s.valor > 400";

        }

    }

    $sql .= " ORDER BY s.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

$stmtTipos = $pdo->prepare("
    SELECT DISTINCT tipo_procedimento
    FROM servicos
    ORDER BY tipo_procedimento
");

$stmtTipos->execute();

$tiposProcedimentos = $stmtTipos->fetchAll(PDO::FETCH_COLUMN);

$stmtBairros = $pdo->prepare("
    SELECT DISTINCT bairro
    FROM clinicas
    ORDER BY bairro
");

$stmtBairros->execute();

$bairros = $stmtBairros->fetchAll(PDO::FETCH_COLUMN);

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

    <!-- Aviso de Antecedência -->
    <?php if(isset($_GET['erro']) && $_GET['erro'] == 'antecedencia'): ?>
        <div class="alert alert-warning">
            Os agendamentos devem ser realizados com pelo menos 24 horas de antecedência.
        </div>
    <?php endif; ?>

    <!-- FILTROS -->
    <section class="container filtros mt-4">

    <form method="GET">
        <div class="row g-3">
            <!-- filtro serviço -->
            <div class="col-md-4">
                <div class="filtro-box">
                    <i class="bi bi-stars"></i>

                    <select name="servico" class="form-select">
                        <option value="">
                            Todos os serviços
                        </option>
                        
                        <?php foreach($tiposProcedimentos as $tipo): ?>

                        <option
                            value="<?= htmlspecialchars($tipo) ?>"
                            <?= $servicoFiltro == $tipo ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($tipo) ?>
                        </option>

                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
            
            <!-- BAIRRO -->
            <div class="col-md-4">
                <div class="filtro-box">
                    <i class="bi bi-geo-alt"></i>

                    <select name="regiao" class="form-select">
                        <option value="">
                            Todos os bairros
                        </option>

                        <?php foreach($bairros as $bairro): ?>

                        <option
                            value="<?= htmlspecialchars($bairro)?>"
                            <?= $regiaoFiltro == $bairro ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($bairro) ?>
                        </option>

                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

            <!-- filtro preço -->
            <div class="col-md-4">
                <div class="filtro-box">
                    <i class="bi bi-currency-dollar"></i>

                    <select name="preco" class="form-select">
                        <option value="">Todos os preços</option>
                        <option value="100">Até R$100</option>
                        <option value="200">Até R$200</option>
                        <option value="400">Até R$400</option>
                        <option value="999999">Acima de R$400</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="mt-3">
            <button class="btn btn-primary">
                Filtrar
            </button>

            <a href="servicos.php" class="btn btn-outline-secondary">
                Limpar
            </a>
        </div>

    </form>
    </section>

    <!-- LISTA DE SERVIÇOS -->
    <section class="container mt-5">
        <h3 class="mb-4">Serviços disponíveis</h3>

        <!-- BOTÃO 'LIMPAR SELEÇÃO' -->
        <div class="mb-3">
            <button type="button" id="limparSelecao" class="btn btn-outline-secondary" style="display:none;">
                Trocar serviço
            </button>
        </div>

        <!-- AVISO AO CLIENTE SOBRE A TROCA DE SERVIÇO -->
        <div id="servicoEscolhido" class="alert alert-info py-2 mt-3" style="display:none;"></div>

        <div class="row g-4">
            <?php if(count($servicos) > 0): ?>
                <?php foreach($servicos as $row): ?>
                    
                    <div class="col-md-4">
                        <!-- CARDS QUE EXIBEM OS SERVIÇOS -->
                        <div class="card shadow-sm h-100 p-3">

                            <!-- BOTÃO FAVORITAR -->
                            <div class="d-flex justify-content-end mb-2">
                                <form action="favoritar.php" method="POST">
                                    <input type="hidden" name="clinica_id" value="<?= $row['clinica_id'] ?>">

                                    <button type="submit" class="btn border-0 bg-transparent p-0">
                                        <?php if(in_array($row['clinica_id'], $favoritos)): ?>
                                            <i class="bi bi-heart-fill text-danger fs-4"></i>
                                        <?php else: ?>
                                            <i class="bi bi-heart text-dark fs-4"></i>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </div>

                            <h5><?= htmlspecialchars($row['nome']) ?></h5>

                            <p class="text-muted mb-1">
                                <strong>Clínica:</strong>
                                <?= htmlspecialchars($row['nome_clinica']) ?>
                            </p>

                            <p><?= htmlspecialchars($row['descricao']) ?></p>

                            <p>
                                <strong>Região:</strong>
                                <?= htmlspecialchars($row['bairro']) ?>
                            </p>

                            <p class="text-success fw-bold">
                                A partir de R$ <?= number_format($row['valor'], 2, ',', '.') ?>
                            </p>

                            <form action="agendamento.php" method="POST"
                            class="form-servico" data-servico="<?= $row['id']?>">

                                <!-- IDs -->
                                <input type="hidden" name="servico_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="clinica_id" value="<?= $row['clinica_id'] ?>">
                                <input type="hidden" name="valor" value="<?= $row['valor'] ?>">
                                <input type="hidden" name="servico" value="<?= $row['nome'] ?>">
                                <input type="hidden" name="clinica" value="<?= $row['nome_clinica'] ?>">
                                <input type="hidden" name="endereco" value="<?= $row['bairro'] ?>">

                                <?php
                                    $stmtHorarios = $pdo->prepare("
                                        SELECT * FROM horarios_disponiveis
                                        WHERE servico_id = ?
                                        AND status = 'livre'
                                        AND data_disponivel >= CURDATE()
                                        ORDER BY data_disponivel ASC, horario ASC
                                    ");
                                    
                                    //Busca os horários
                                    $stmtHorarios->execute([$row['id']]);
                                    $horarios = $stmtHorarios->fetchAll(PDO::FETCH_ASSOC);

                                    //Filtra os horários e exibe apenas o que estão, no mínimo, 24h à frente
                                    $horariosFiltrados = [];
                                    foreach($horarios as $h){
                                        $dataHoraHorario = strtotime(
                                            $h['data_disponivel'] . ' ' . $h['horario']
                                        );
                                        $agora = time();
                                        if(($dataHoraHorario - $agora) >= 86400){
                                            $horariosFiltrados[] = $h;
                                        }
                                    }
                                    $horarios = $horariosFiltrados;

                                    $primeiroHorario = $horarios[0] ?? null;
                                ?>

                                <?php if($primeiroHorario): ?>
                                    <input
                                        type="hidden"
                                        name="horario"
                                        id="horarioPadrao<?= $row['id'] ?>"
                                        value=""
                                    >
                                    <div
                                        id="horarioSelecionado<?= $row['id'] ?>"
                                        class="small text-success fw-bold mb-2"
                                    >
                                    </div>
                                <?php endif; ?>

                                <div class="horarios-box mb-3">
                                    <?php
                                    $datas = [];

                                    foreach($horarios as $h){
                                        $datas[$h['data_disponivel']][] = $h;
                                    }
                                    ?>

                                    <?php if($primeiroHorario): ?>
                                        <!-- Próximo horário -->
                                        <div class="border rounded p-2 mb-3 bg-light">

                                            <small class="text-muted">
                                                Próximo horário disponível
                                            </small>

                                            <div class="fw-bold text-success">
                                                <?= date(
                                                    'd/m/Y',
                                                    strtotime($primeiroHorario['data_disponivel'])
                                                ) ?>

                                                às
                                                <?= substr(
                                                    $primeiroHorario['horario'],
                                                    0,
                                                    5
                                                ) ?>
                                            </div>
                                        </div>

                                        <!-- Botão -->
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary w-100 mb-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHorarios<?= $row['id'] ?>"
                                        >
                                            Escolher outro horário
                                        </button>

                                        <!-- Modal -->
                                        <div
                                            class="modal fade"
                                            id="modalHorarios<?= $row['id'] ?>"
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
                                                        <?php foreach($datas as $data => $listaHorarios): ?>
                                                            <h6 class="mt-3 mb-2">
                                                                <?= date(
                                                                    'd/m/Y',
                                                                    strtotime($data)
                                                                ) ?>
                                                            </h6>

                                                            <?php foreach($listaHorarios as $horario): ?>
                                                                <div class="mb-2">
                                                                    <input
                                                                        type="radio"
                                                                        class="btn-check horario-radio"
                                                                        name="selecaoHorario<?= $row['id'] ?>"
                                                                        data-servico="<?= $row['id'] ?>"
                                                                        id="horario<?= $horario['id'] ?>"
                                                                        value="<?= $horario['id'] ?>|<?= $horario['data_disponivel'] ?>|<?= $horario['horario'] ?>"
                                                                    >

                                                                    <label
                                                                        class="btn btn-outline-dark w-100"
                                                                        for="horario<?= $horario['id'] ?>"
                                                                        onclick="
                                                                           document.getElementById('horarioPadrao<?= $row['id'] ?>').value =
                                                                            '<?= $horario['id'] ?>|<?= $horario['data_disponivel'] ?>|<?= $horario['horario'] ?>';

                                                                            document.getElementById('btnAgendar<?= $row['id'] ?>').disabled = false;

                                                                            document.getElementById('horarioSelecionado<?= $row['id'] ?>').innerHTML =
                                                                            'Horário selecionado: <?= date('d/m/Y', strtotime($horario['data_disponivel'])) ?> às <?= substr($horario['horario'],0,5) ?>';

                                                                            bootstrap.Modal.getInstance(
                                                                                document.getElementById(
                                                                                    'modalHorarios<?= $row['id'] ?>'
                                                                                )
                                                                            ).hide();
                                                                        "
                                                                    >

                                                                        <?= substr(
                                                                            $horario['horario'],
                                                                            0,
                                                                            5
                                                                        ) ?>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <p class="text-muted">
                                            Nenhum horário disponível
                                        </p>

                                    <?php endif; ?>
                                </div>
                                <button type="submit" id="btnAgendar<?= $row['id'] ?>" class="btn btn-primary w-100 btn-agendar">Agendar</button>
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
            
    <!--SCRIPT QUE IMPEDE O USUÁRIO DE SELECIONAR MAIS DE UM SERVIÇO POR VEZ-->
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const radios =
                document.querySelectorAll('.horario-radio');
            const btnLimpar =
                document.getElementById('limparSelecao');
            const aviso =
                document.getElementById('servicoEscolhido');
            
            radios.forEach(function(radio){
                radio.addEventListener('change', function(){
                    const servico =
                        this.dataset.servico;
                    radios.forEach(function(r){
                        if(r.dataset.servico !== servico){
                            r.disabled = true;
                        }
                    });
                    btnLimpar.style.display = 'inline-block';

                    aviso.innerHTML =
                        'Um horário foi selecionado. Para escolher outro serviço, clique em "Trocar serviço".';

                    aviso.style.display = 'block';
                });
            });

            btnLimpar.addEventListener('click', function(){
                radios.forEach(function(r){
                    r.disabled = false;
                    r.checked = false;

                    document
                        .querySelectorAll('[id^="btnAgendar"]')
                        .forEach(function(btn){
                            btn.disabled = true;
                        });

                    document
                        .querySelectorAll('[id^="horarioPadrao"]')
                        .forEach(function(input){
                            input.value = '';
                        });
                });

                document.querySelectorAll('[id^="horarioSelecionado"]')
                    .forEach(function(el){
                        el.innerHTML = '';
                    });
                btnLimpar.style.display = 'none';
                aviso.style.display = 'none';
                aviso.innerHTML = '';
            });
        });
    </script>
</body>
</html>