<?php
session_start();
$id_clinica = $_SESSION['clinica_id'];

// SANITIZAÇÃO
$servico = htmlspecialchars($_POST['servico'] ?? '');
$clinica = htmlspecialchars($_POST['clinica'] ?? '');
$endereco = htmlspecialchars($_POST['endereco'] ?? '');
$valor = floatval($_POST['valor'] ?? 0);
$data = htmlspecialchars($_POST['data'] ?? '');
$hora = htmlspecialchars($_POST['hora'] ?? '');

// VALIDAÇÃO
if (!$servico || !$clinica || !$valor) {
    die("Erro ao selecionar serviço");
}

$pix = "11999999999";

// payload fake
$payload = "BRUMA|$clinica|R$$valor|$data|$hora|$pix";

$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($payload);
?>
