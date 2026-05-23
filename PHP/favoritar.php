<?php
session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

require_once("conexao.php");

$pdo = conectar();

$usuario_id = $_SESSION['usuario_id'];

$clinica_id = $_POST['clinica_id'] ?? null;

if(!$clinica_id){
    header("Location: servicos.php");
    exit();
}

try {

    // verifica se já existe
    $stmt = $pdo->prepare("
        SELECT id
        FROM favoritos
        WHERE usuario_id = ?
        AND clinica_id = ?
    ");

    $stmt->execute([
        $usuario_id,
        $clinica_id
    ]);

    $favorito = $stmt->fetch();

    // remove favorito
    if($favorito){

        $delete = $pdo->prepare("
            DELETE FROM favoritos
            WHERE usuario_id = ?
            AND clinica_id = ?
        ");

        $delete->execute([
            $usuario_id,
            $clinica_id
        ]);

    } else {

        // adiciona favorito
        $insert = $pdo->prepare("
            INSERT INTO favoritos (
                usuario_id,
                clinica_id
            ) VALUES (?, ?)
        ");

        $insert->execute([
            $usuario_id,
            $clinica_id
        ]);

    }

} catch(PDOException $e){

    die("Erro: " . $e->getMessage());

}

header("Location: servicos.php");
exit();