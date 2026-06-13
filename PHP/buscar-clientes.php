<?php

require_once("conexao.php");

$pdo = conectar();

$nome = $_GET['nome'] ?? '';
$data = $_GET['data'] ?? '';

$sql = "
SELECT
    u.nome AS cliente,
    h.data_disponivel,
    h.horario,
    s.nome AS servico

FROM agendamentos a

INNER JOIN usuarios u
    ON u.id = a.usuario_id

INNER JOIN servicos s
    ON s.id = a.servico_id

INNER JOIN horarios_disponiveis h
    ON h.id = a.horario_id

WHERE 1=1";

$params = [];

if(!empty($nome)){
    $sql .= " AND u.nome LIKE ?";
    $params[] = "%".$nome."%";
}

if(!empty($data)){
    $sql .= " AND h.data_disponivel = ?";
    $params[] = $data;
}

$sql .= "
ORDER BY h.data_disponivel DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultados);