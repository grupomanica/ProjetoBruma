<?php
    // Inicia a sessão
    session_start();

    // Verifica se o usuário está autenticado.
    if(!isset($_SESSION['usuario_id'])){
        // Se a sessão não existir, o usuário será redirecionado para a tela de login.
        header("Location: login.php");
        exit();
    }

    // Incluindo o autoload do DOM PDF
    require_once '../ASSETS/dompdf/autoload.inc.php';
    require_once("conexao.php");

    //Conexão com o banco
    $pdo = conectar();


    //Buscar as informações do usuário
    $usuarioId = $_SESSION['usuario_id'];
    $sqlUsuario = "SELECT nome, sobrenome, telefone, data_nascimento FROM usuarios WHERE id = :id";

    $stmtUsuario = $pdo->prepare($sqlUsuario);

    $stmtUsuario->execute([':id' => $usuarioId]);

    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);


    //Recebendo dados da página anterior
    $servico_id = $_POST['servico_id'] ?? 0;
    $servico = $_POST['servico'] ?? '';
    $clinica = $_POST['clinica'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $valor = $_POST['valor'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $horarioSelecionado = $_POST['horario'] ?? '';
    $profissional_id = $_POST['profissional_id'] ?? '';
    $profissional_nome = $_POST['profissional_nome'] ?? '';
    $clinica_id = $_POST['clinica_id'] ?? 0;

    // Descobre dia da semana
    $dataBanco = '';
    $horaBanco = '';

    //Processamento do horário
    if (!empty($horarioSelecionado)) {
        //Separação dos dados
        list(
            $horarioId,
            $dataBanco,
            $horaBanco) =
            explode('|', $horarioSelecionado);
    }

    //Converte dia da semana
    $diasSemana = [
        'Sunday' => 'Domingo',
        'Monday' => 'Segunda',
        'Tuesday' => 'Terça',
        'Wednesday' => 'Quarta',
        'Thursday' => 'Quinta',
        'Friday' => 'Sexta',
        'Saturday' => 'Sábado'
    ];

    $diaSemanaIngles = date(
    'l',
    strtotime($dataBanco)
    );

    $diaSemana = $diasSemana[$diaSemanaIngles];

    //Busca todos os profissionais ativos da clínica escolhida
    $sqlProfissionais = "
        SELECT *
        FROM profissionais
        WHERE clinica_id = :clinica_id
        AND status = 'ativo'
        AND dias_semana LIKE :dia_semana
        AND hora_inicio <= :hora_inicio
        AND hora_fim >= :hora_fim
        ORDER BY nome ASC
    ";

    //Uso de 'Prepared Statement' para previnir SQL Injection
    $stmtProfissionais = $pdo->prepare($sqlProfissionais);
    $stmtProfissionais->execute([
        ':clinica_id' => $clinica_id,
        ':dia_semana' => '%' . $diaSemana . '%',
        ':hora_inicio' => $horaBanco,
        ':hora_fim' => $horaBanco
    ]);

    $profissionais = $stmtProfissionais->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($horarioSelecionado)) {
        list($horarioId, $dataBanco, $horaBanco) = explode('|', $horarioSelecionado);
        $data = date('d/m/Y', strtotime($dataBanco));
        $hora = substr($horaBanco, 0, 5);
    } else {
        $data = '06/04/2026';
        $hora = '10:00';
    }

    //Simulação de PIX
    $pix = "11999999999";
    // Payload fake
    $payload = "BRUMA | $clinica | R$$valor | $data | $hora | $pix";
    //API que gera uma imagem QRCode
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($payload);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="ASSETS/IMG/favicon/logo-iconeFullSize.png" alt="logo" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../CSS/agendamento.css">
    <script src="../JS/agendamento.js" defer></script>

    <title>Agendamento | Bruma</title>
</head>

<body>
    <header class="header-bruma">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="painel.php"><img src="../ASSETS/IMG/logo-horizontal.png" width="90"></a>
            <a href="servicos.php" class="btn btn-outline-dark btn-sm">Voltar</a>
        </div>
    </header>

    <section class="container mt-5">
        <!-- STEPS -->
        <div class="steps-bar mb-4">
            <div class="step active" id="step-1">Consulta</div>
            <div class="step" id="step-2">Seus dados</div>
            <div class="step" id="step-3">Confirmação</div>
            <div class="step" id="step-4">Pagamento</div>
        </div>

        <div class="card agendamento-card">
            <!-- ETAPA 1 -->
            <div class="step-content active" id="content-1">
                <h4>Revise sua consulta</h4>

                <div class="resumo-box">
                    <p><strong>Serviço:</strong><?= htmlspecialchars($servico) ?></p>
                    <p><strong>Clínica:</strong><?= htmlspecialchars($clinica) ?></p>
                    <p><strong>Endereço:</strong><?= htmlspecialchars($endereco) ?></p>

                    <p class="mt-3"><strong>Data:</strong><?= $data ?></p>
                    <p><strong>Horário:</strong><?= $hora ?></p>

                    <label class="mt-3"><strong>Escolha um profissional:</strong></label>

                    <select class="form-control mt-2" id="profissionalSelect">
                        <?php foreach($profissionais as $profissional): ?>
                            <option
                                value="<?= $profissional['id'] ?>"
                                data-nome="<?= htmlspecialchars($profissional['nome']) ?>"
                            >
                                <?= htmlspecialchars($profissional['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <p class="valor mt-3">R$ <?= number_format($valor, 2, ',', '.') ?></p>
                </div>

                <div class="text-end mt-4">
                    <button class="btn next-btn" onclick="validarEtapa1()" > Continuar </button>
                </div>
            </div>

            <!-- ETAPA 2 -->
            <div class="step-content" id="content-2">
                <h4>Seus dados</h4>

                <p class="text-muted mb-4">
                    Confira seus dados cadastrais.
                    Caso precise alterá-los, acesse seu perfil.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome</label>
                        <div class="form-control bg-light">
                            <?= htmlspecialchars($usuario['nome']) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sobrenome</label>
                        <?php if(!empty($usuario['sobrenome'])): ?>
                            <div class="form-control bg-light">
                                <?= htmlspecialchars($usuario['sobrenome']) ?>
                            </div>
                            <input type="hidden" id="sobrenome" name="sobrenome" value="<?= htmlspecialchars($usuario['sobrenome']) ?>">
                        <?php else: ?>
                            <input
                                type="text"
                                class="form-control"
                                id="sobrenome"
                                name="sobrenome"
                                placeholder="Informe seu sobrenome"
                                required>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Data de nascimento</label>
                        <?php if(!empty($usuario['data_nascimento'])): ?>
                            <div class="form-control bg-light">
                                <?= date('d/m/Y',strtotime($usuario['data_nascimento'])) ?>
                            </div>
                            <input type="hidden" id="dataNascimento" name="data_nascimento" value="<?= $usuario['data_nascimento'] ?>">

                        <?php else: ?>
                            <input
                                type="date"
                                class="form-control"
                                id="dataNascimento"
                                name="data_nascimento"
                                required
                            >
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="telefone">Telefone</label>
                        <div class="form-control bg-light">
                            <?= htmlspecialchars($usuario['telefone']) ?>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="painel.php" class="small">Atualizar meus dados cadastrais</a>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-outline-secondary" onclick="prevStep()">Voltar</button>
                    <button class="btn next-btn" onclick="validarEtapa2()">Continuar</button>
                </div>
            </div>

            <!-- ETAPA 3 -->
            <div class="step-content" id="content-3">
                <h4>Confirmação</h4>
                <p>
                    Seu agendamento será enviado para a clínica
                    <strong><?= $clinica ?></strong>.
                </p>

                <div class="alert alert-warning">
                    Realize o pagamento para garantir sua vaga.
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-secondary" onclick="prevStep()">
                        Voltar
                    </button>

                    <button class="btn next-btn" onclick="nextStep()">
                        Ir para pagamento
                    </button>
                </div>
            </div>
                    
            <!-- ETAPA 4 -->
            <div class="step-content" id="content-4">
                <div class="d-flex justify-content-between mt-4"></div>

                <h4 class="text-center">Pagamento via PIX</h4>

                <div class="text-center resumo-box mb-3">
                    <strong><?= $clinica ?></strong><br>
                    <?= $servico ?><br>
                    <?= $data ?> às <?= $hora ?><br>
                    <span class="valor">R$ <?= $valor ?></span>
                </div>

                <div class="text-center">
                    <img src="<?= $qrCodeUrl ?>" alt="QR Code PIX">
                </div>

                <p class="text-center small mt-2">Escaneie com seu banco</p>

                <div class="pix-code">
                    <input type="text" id="pixInput" value="<?= $payload ?>" readonly>
                    <button onclick="copiarPix()">Copiar</button>
                </div>

                <div class="text-center mt-4">
                    <form action="salvar-agendamento.php" method="POST">
                        <input type="hidden" name="servico" value="<?= $servico ?>">
                        <input type="hidden" name="clinica" value="<?= $clinica ?>">
                        <input type="hidden" name="endereco" value="<?= $endereco ?>">
                        <input type="hidden" name="valor" value="<?= $valor ?>">
                        <input type="hidden" name="data" value="<?= $data ?>">
                        <input type="hidden" name="hora" value="<?= $hora ?>">

                        <input type="hidden" name="servico_id" value="<?= $_POST['servico_id'] ?>">
                        <input type="hidden" name="clinica_id" value="<?= $_POST['clinica_id'] ?>">

                        <input type="hidden" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>">
                        <input type="hidden" name="sobrenome" value="<?= htmlspecialchars($usuario['sobrenome']) ?>">
                        <input type="hidden" name="data_nascimento" value="<?= ($usuario['data_nascimento'])?>">
                        <input type="hidden" name="telefone" id="telefoneHidden">

                        <input type="hidden" name="dataBanco" id="dataBancoInput">
                        <input type="hidden" name="horaBanco" id="horaBancoInput">
                        <input type="hidden" name="horario_id" id="horarioIdInput">
                        <input type="hidden" name="horario" id="horarioCompletoInput">
                        <input type="hidden" name="data_nascimento" id="dataNascimentoInput">
                        <input type="hidden" name="profissional_id" id="profissionalIdInput">
                        <input type="hidden" name="profissional_nome" id="profissionalNomeInput">
                        <input type="hidden" name="horario" value="<?= $horarioSelecionado ?>">

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" name="forma_pagamento" value="boleto" class="btn confirmar-btn">
                                Gerar boleto
                            </button>
                            
                            <button
                                type="submit"
                                name="forma_pagamento"
                                value="pix"
                                class="btn btn-outline-sucess btn-sm"
                                onclick="return confirm('Confirmar pagamento PIX simulado?);"
                            >
                                <i class="bi bi-check-circle"></i>
                                Confirmar pagamento PIX
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>