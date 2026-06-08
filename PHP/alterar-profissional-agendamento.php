<?php
session_start();

if(!isset($_SESSION['clinica_id'])){
    header("Location: login-clinica.php");
    exit();
}

require_once("conexao.php");
$pdo = conectar();

$agendamento_id = $_GET['id'] ?? 0;

if(!$agendamento_id){
    die("Agendamento inválido.");
}

try {
    $sqlAgendamento = "
        SELECT
            a.*,
            u.nome,
            u.sobrenome,
            s.nome AS servico_nome,
            h.data_disponivel,
            h.horario
        FROM agendamentos a
        INNER JOIN usuarios u
            ON u.id = a.usuario_id
        INNER JOIN servicos s
            ON s.id = a.servico_id
        INNER JOIN horarios_disponiveis h
            ON h.id = a.horario_id
        WHERE a.id = :id
    ";

    $stmtAgendamento = pdo->prepare($sqlAgendamento);

    $stmtAgendamento->execute([
        ':id' => $agendamento_id
    ]);

    $agendamento = $stmtAgendamento->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    die("Erro ao buscar agendamento.");
}

try {
    $dataAgendamento =
        $agendamento['data_disponivel'];
    $horaAgendamento = date(
        'H:i:s', strtotime($agendamento['horario'])
    );

    //Descobre dia da semana
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
        'l',strtotime($dataAgendamento)
        );

    $diaSemana = $diasSemana[$diaSemanaIngles];

    $sqlProfissionais = "
        SELECT * FROM profissionais
            WHERE clinica_id = :clinica_id
            AND status = 'ativo'
            AND dias_semana LIKE :dia_semana
            AND hora_inicio <= :hora
            AND hora_fim >= :hora
        ORDER BY nome ASC
        ";

    $stmtProfissionais = $pdo->prepare($sqlProfissionais);
    $stmtProfissionais->execute([
        ':clinica_id' => $_SESSION['clinica_id'],
        ':dia_semana' => '%' . $diaSemana . '%',
        ':hora' => $horaAgendamento
    ]);

    $profissionais = $stmtProfissionais->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
    $profissionais = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Alterar Profissional</title>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">

                <h3 class="mb-4">Alterar profissional</h3>

                <p>
                    <strong>Cliente:</strong>
                    <?= htmlspecialchars(
                        $agendamento['nome'] . ' ' .
                        $agendamento['sobrenome']
                    ) ?>
                </p>

                <p>
                    <strong>Serviço:</strong>
                    <?= htmlspecialchars(
                        $agendamento['servico_nome']
                    ) ?>
                </p>

                <form action="salvar-alteracao-profissional.php" method="POST">
                    
                    <input type="hidden" name="agendamento_id" value="<?= $agendamento['id'] ?>">
                    
                    <label class="form-label mt-3">Profissional</label>
                        <select name="profissional_id" class="form-control" required>
                            <option value="">Selecione</option>
                                <?php foreach($profissionais as $profissional): ?>
                            <option value="<?= $profissional['id'] ?>"
                            <?php if($profissional['id'] == $agendamento['profissional_id']): ?>
                                selected
                            <?php endif; ?>
                            >
                                <?= htmlspecialchars($profissional['nome']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    
                    <?php if(count($profissionais) == 0): ?>
                        <div class="alert alert-warning mt-3">
                            Nenhum profissional disponível neste dia e horário.
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 d-flex gap-2">
                        <a href="painel-clinica.php" class="btn btn-secondary">
                            Voltar
                        </a>

                        <button type="submit" class="btn btn-success">
                            Salvar alteração
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>