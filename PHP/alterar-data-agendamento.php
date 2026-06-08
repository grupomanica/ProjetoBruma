<?php
    session_start();

    if (!isset($_SESSION['clinica_id'])) {
        header("Location: login-clinica.php");
        exit();
    }

    require_once("conexao.php");
    $pdo = conectar();
    $clinica_id = $_SESSION['clinica_id'];
    $agendamento_id = $_GET['id'] ?? 0;

    try {
        // Buscar agendamento atual
        $sqlAgendamento = "
            SELECT a.*, h.data_disponivel, h.horario
            FROM agendamentos a
            INNER JOIN horarios_disponiveis h
                ON h.id = a.horario_id
            WHERE a.id = :id
            AND a.clinica_id = :clinica_id
        ";

        $stmtAgendamento = $pdo->prepare($sqlAgendamento);

        $stmtAgendamento->execute([
            ':id' => $agendamento_id,
            ':clinica_id' => $clinica_id
        ]);

        $agendamento = $stmtAgendamento->fetch(PDO::FETCH_ASSOC);

        if (!$agendamento) {
            die("Agendamento não encontrado.");
        }

        // Buscar datas disponíveis
        $sqlDatas = "
            SELECT * FROM horarios_disponiveis
            WHERE clinica_id = :clinica_id
            AND status = 'livre'
            ORDER BY data_disponivel ASC,
                    horario ASC
        ";

        $stmtDatas = $pdo->prepare($sqlDatas);
        $stmtDatas->execute([
            ':clinica_id' => $clinica_id
        ]);
        $datasDisponiveis = $stmtDatas->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        die("Erro: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Data</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="mb-4">
                    Alterar Data do Agendamento
                </h3>

                <p>
                    <strong>Data atual:</strong>

                    <?= date(
                        'd/m/Y',
                        strtotime($agendamento['data_disponivel'])
                    ) ?>

                    às

                    <?= substr($agendamento['horario'], 0, 5) ?>
                </p>

                <form action="salvar-nova-data.php" method="POST">
                    <input type="hidden" name="agendamento_id" value="<?= $agendamento_id ?>">

                    <label class="form-label">Selecione nova data/horário</label>
                    <select name="novo_horario_id" class="form-control mb-4" required>
                        <option value=""> Escolha uma opção </option>

                        <?php foreach($datasDisponiveis as $data): ?>
                            <option value="<?= $data['id'] ?>">
                                <?= date(
                                    'd/m/Y',
                                    strtotime($data['data_disponivel'])
                                ) ?>
                                às
                                <?= substr($data['horario'], 0, 5) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-success">
                        Salvar nova data
                    </button>

                    <a href="painel-clinica.php" class="btn btn-secondary">
                        Voltar
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>