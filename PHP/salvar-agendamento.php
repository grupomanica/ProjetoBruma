<?php

session_start();

require_once("conexao.php");
require_once '../ASSETS/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

try {

    $pdo = conectar();

    $usuario_id = $_SESSION['usuario_id'];

    $servico_id = $_POST['servico_id'] ?? '';
    $clinica_id = $_POST['clinica_id'] ?? '';
    $horarioSelecionado = $_POST['horario'] ?? '';
    $profissional_id = $_POST['profissional_id'] ?? '';
    $dataNascimento = $_POST['data_nascimento'] ?? '';

    // VALIDA CAMPOS
    if(
        empty($servico_id) ||
        empty($clinica_id) ||
        empty($horarioSelecionado)
    ){

        die("Erro: dados do agendamento não enviados.");
    }

    // VALIDA IDADE
    if(empty($dataNascimento)){

        die("Data de nascimento não informada.");
    }

    $hoje = new DateTime();

    $nascimento = new DateTime($dataNascimento);

    $idade = $hoje->diff($nascimento)->y;

    if($idade < 18){

        echo "
            <script>
                alert('Você deve ser maior de idade para continuar o agendamento.');
                window.history.back();
            </script>
        ";

        exit();
    }

    // separa horario
    $partes = explode('|', $horarioSelecionado);

if(count($partes) < 3){

    die("Horário inválido.");
}

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

exit();

$horario_id = $partes[0];
$data = $partes[1];
$hora = $partes[2];
    // BUSCA VALOR
    $stmtServico = $pdo->prepare("
        SELECT valor
        FROM servicos
        WHERE id = :id
    ");

    $stmtServico->execute([
        ':id' => $servico_id
    ]);

    $servico = $stmtServico->fetch(PDO::FETCH_ASSOC);

    if(!$servico){

        die("Serviço não encontrado.");
    }

    $valor = $servico['valor'];

        if(empty($profissional_id)){

    //Busca data e hora do horário escolhido
    $sqlHorario = "
        SELECT
            data_disponivel,
            horario,
            clinica_id
        FROM horarios_disponiveis
        WHERE id = :horario_id
    ";

    $stmtHorario = $pdo->prepare($sqlHorario);

    $stmtHorario->execute([
        ':horario_id' => $horario_id
    ]);

    $horario = $stmtHorario->fetch(PDO::FETCH_ASSOC);

    $dataAgendamento = $horario['data_disponivel'];

    $horaAgendamento = $horario['horario'];

    // Dias da semana
    $diasSemana = [
        'Domingo',
        'Segunda',
        'Terça',
        'Quarta',
        'Quinta',
        'Sexta',
        'Sábado'
    ];

    $diaSemana = $diasSemana[
        date('w', strtotime($dataAgendamento))
    ];

    // Busca profissional disponível
    $sqlProfissional = "
    SELECT id
    FROM profissionais
    WHERE clinica_id = :clinica_id
    AND status = 'ativo'
    AND dias_semana LIKE :dia_semana
    AND hora_inicio <= :hora_inicio
    AND hora_fim >= :hora_fim
    LIMIT 1
";

    $stmtProfissional = $pdo->prepare($sqlProfissional);

    $stmtProfissional->execute([
    ':clinica_id' => $horario['clinica_id'],
    ':dia_semana' => '%' . $diaSemana . '%',
    ':hora_inicio' => $horaAgendamento,
    ':hora_fim' => $horaAgendamento
]);

    $profissional = $stmtProfissional->fetch(PDO::FETCH_ASSOC);

    if($profissional){

        $profissional_id = $profissional['id'];

    } else {

        die("Nenhum profissional disponível para este horário.");

    }
}

    // INSERT AGENDAMENTO
    $sql = "
        INSERT INTO agendamentos (
            usuario_id,
            clinica_id,
            servico_id,
            profissional_id,
            horario_id,
            valor,
            status_pagamento,
            status_agendamento
        )
        VALUES (
            :usuario_id,
            :clinica_id,
            :servico_id,
            :profissional_id,
            :horario_id,
            :valor,
            :status_pagamento,
            :status_agendamento
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ':usuario_id' => $usuario_id,

        ':clinica_id' => $clinica_id,

        ':servico_id' => $servico_id,

        ':profissional_id' => $profissional_id,

        ':horario_id' => $horario_id,

        ':valor' => $valor,

        ':status_pagamento' => 'pendente',

        ':status_agendamento' => 'confirmado'
    ]);

    // OCUPA HORÁRIO
    $stmtHorario = $pdo->prepare("
        UPDATE horarios_disponiveis
        SET status = 'ocupado'
        WHERE id = :horario_id
    ");

    $servicoNome = $_POST['servico'] ?? '';
    $clinicaNome = $_POST['clinica'] ?? '';
    $endereco = $_POST['endereco'] ?? '';

    $vencimento = date('d/m/Y', strtotime('+3 days'));

    $linhaDigitavel = "34191.79001 01043.510047 91020.150008 8 91230000012000";

    $nossoNumero = rand(10000000,99999999);
    $numeroDocumento = rand(10000,99999);

    $nomeCliente = $_POST['nome'] ?? 'Cliente Bruma';
    $cpfCliente = '000.000.000-00';

    $stmtHorario->execute([
        ':horario_id' => $horario_id
    ]);

} catch(PDOException $e){

    die("Erro no banco: " . $e->getMessage());

}
?>