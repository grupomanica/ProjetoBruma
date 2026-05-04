<?php

require_once '../ASSETS/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$servico = $_POST['servico'] ?? '';
$clinica = $_POST['clinica'] ?? '';
$endereco = $_POST['endereco'] ?? '';
$valor = $_POST['valor'] ?? '';
$data = $_POST['data'] ?? '';
$hora = $_POST['hora'] ?? '';

$nomeCliente = "Cliente Bruma";
$cpfCliente = "000.000.000-00";

$vencimento = date('d/m/Y', strtotime('+3 days'));
$linhaDigitavel = "34191.79001 01043.510047 91020.150008 8 91230000012000";
$nossoNumero = rand(10000000,99999999);
$numeroDocumento = rand(10000,99999);

$html = "
<html>
<head>
<meta charset='UTF-8'>

<style>

body{
    font-family: Arial;
    font-size: 11px;
    margin: 20px;
}

table{
    width:100%;
    border-collapse: collapse;
}

td{
    border:1px solid #000;
    padding:6px;
    vertical-align: top;
}

.sem-borda{
    border:none;
}

.header-boleto{
    font-size:20px;
    font-weight:bold;
    text-align:center;
}

.linha-digitavel{
    font-size:18px;
    font-weight:bold;
    text-align:center;
    padding:15px;
}

.codigo-barras{
    margin-top:20px;
    text-align:center;
    letter-spacing:2px;
    font-size:26px;
    font-weight:bold;
}

.recibo{
    margin-top:40px;
    border-top:2px dashed #000;
    padding-top:20px;
}

.titulo{
    font-weight:bold;
    background:#f2f2f2;
}

</style>
</head>

<body>

<table>
    <tr>
        <td width='20%'>
            <strong>BRUMA PAY</strong>
        </td>
        <td width='15%'>
            <strong>237-2</strong>
        </td>
        <td class='linha-digitavel'>
            {$linhaDigitavel}
        </td>
    </tr>
</table>

<table>
    <tr>
        <td class='titulo'>Beneficiário</td>
        <td>{$clinica}</td>
    </tr>

    <tr>
        <td class='titulo'>Endereço</td>
        <td>{$endereco}</td>
    </tr>

    <tr>
        <td class='titulo'>Pagador</td>
        <td>{$nomeCliente}</td>
    </tr>

    <tr>
        <td class='titulo'>CPF</td>
        <td>{$cpfCliente}</td>
    </tr>
</table>

<table>
    <tr>
        <td class='titulo'>Serviço contratado</td>
        <td>{$servico}</td>

        <td class='titulo'>Data agendada</td>
        <td>{$data}</td>
    </tr>

    <tr>
        <td class='titulo'>Horário</td>
        <td>{$hora}</td>

        <td class='titulo'>Vencimento</td>
        <td>{$vencimento}</td>
    </tr>
</table>

<table>
    <tr>
        <td class='titulo'>Nosso Número</td>
        <td>{$nossoNumero}</td>

        <td class='titulo'>Nº Documento</td>
        <td>{$numeroDocumento}</td>

        <td class='titulo'>Valor Documento</td>
        <td><strong>R$ {$valor}</strong></td>
    </tr>
</table>

<div class='codigo-barras'>
|||| |||| ||||| |||| ||||| |||| ||||| ||||
</div>

<div style='text-align:center; margin-top:10px;'>
    {$linhaDigitavel}
</div>

<div class='recibo'>
    <h3>Recibo do Pagador</h3>

    <p><strong>Clínica:</strong> {$clinica}</p>
    <p><strong>Serviço:</strong> {$servico}</p>
    <p><strong>Valor:</strong> R$ {$valor}</p>
    <p><strong>Data do agendamento:</strong> {$data} às {$hora}</p>
</div>

</body>
</html>
";

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream(
    "boleto_bruma.pdf",
    ["Attachment" => false]
);