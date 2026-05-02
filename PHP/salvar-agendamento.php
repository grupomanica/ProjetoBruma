<?php
require_once("conexao.php");

try {
    $pdo = conectar();

    $id_usuario = $_SESSION['usuario_id'] ?? 1;

    $id_servico = $_POST['id_servico'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $nome = htmlspecialchars($_POST['nome']);
    $telefone = htmlspecialchars($_POST['telefone']);

    $stmt = $pdo->prepare("
        INSERT INTO agendamentos 
        (id_usuario, id_servico, data, hora, nome_cliente, telefone, status)
        VALUES (?, ?, ?, ?, ?, ?, 'pendente')
    ");

    $stmt->execute([
        $id_usuario,
        $id_servico,
        $data,
        $hora,
        $nome,
        $telefone
    ]);

    $id_agendamento = $pdo->lastInsertId();

    // REDIRECIONA PARA PAGAMENTO
    header("Location: ?id=$id_agendamento");
    exit;

} catch(Exception $e) {
    echo $e->getMessage();
}
?>