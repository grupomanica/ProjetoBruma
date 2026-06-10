<?php

function registrarHistorico(
    $pdo,
    $clinica_id,
    $tipo,
    $modulo,
    $descricao
){
    $sql = "
        INSERT INTO historico_alteracoes
        (
            clinica_id,
            tipo,
            modulo,
            descricao
        )
        VALUES
        (
            :clinica_id,
            :tipo,
            :modulo,
            :descricao
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':clinica_id' => $clinica_id,
        ':tipo' => $tipo,
        ':modulo' => $modulo,
        ':descricao' => $descricao
    ]);
}