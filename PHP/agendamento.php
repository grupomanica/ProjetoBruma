<?php
// Incluindo o autoload do DOM PDF
require_once '../ASSETS/dompdf/autoload.inc.php';

$servico = $_POST['servico'] ?? 'Limpeza de Pele';
$clinica = $_POST['clinica'] ?? 'Bella Estética';
$endereco = $_POST['endereco'] ?? 'Rua Exemplo, 123 - Moema - São Paulo';
$valor = $_POST['valor'] ?? '120';
$data = $_POST['data'] ?? '06/04/2026';
$hora = $_POST['hora'] ?? '10:00';

$pix = "11999999999";

// payload fake
$payload = "BRUMA|$clinica|R$$valor|$data|$hora|$pix";

$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($payload);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                <p><strong>Serviço:</strong> <?= $servico ?></p>
                <p><strong>Clínica:</strong> <?= $clinica ?></p>
                <p><strong>Endereço:</strong> <?= $endereco ?></p>
                <p><strong>Data:</strong> <?= $data ?></p>
                <p><strong>Horário:</strong> <?= $hora ?></p>
                <p class="valor">R$ <?= $valor ?></p>
            </div>

            <div class="text-end mt-3">
                <button class="btn next-btn" onclick="nextStep()">Continuar</button>
            </div>

        </div>

        <!-- ETAPA 2 -->
        <div class="step-content" id="content-2">

            <h4>Seus dados</h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name">Nome:</label>
                    <input type="text" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="dataNascimento">Data de Nascimento</label>
                    <input type="date" class="form-control" id="dataNascimento" required>
                </div>

                <div class="col-md-6">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" class="form-control" id="telefone" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="idade">
                <label class="form-check-label">Confirmo que sou maior de 18 anos</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="alergia">
                <label class="form-check-label">Afirmo que não possuo alergias impeditivas</label>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-outline-secondary" onclick="prevStep()">Voltar</button>
                <button class="btn next-btn" onclick="validarEtapa2()">Continuar</button>
            </div>

        </div>

        <!-- ETAPA 3 -->
        <div class="step-content" id="content-3">

            <h4>Confirmação</h4>

            <p>Seu agendamento será enviado para a clínica <strong><?= $clinica ?></strong>.</p>

            <div class="alert alert-warning">
                Realize o pagamento para garantir sua vaga.
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-outline-secondary" onclick="prevStep()">Voltar</button>
                <button class="btn next-btn" onclick="nextStep()">Ir para pagamento</button>
            </div>

        </div>

        <!-- ETAPA 4 -->
        <div class="step-content" id="content-4">
            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-outline-secondary" onclick="prevStep()">Voltar</button>
            </div>

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
                <button class="btn confirmar-btn">Já paguei</button>
            </div>

        </div>

    </div>

</section>

</body>
</html>