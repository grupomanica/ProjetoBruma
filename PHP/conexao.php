<?php
function conectar() {
    //Definições de host, database, usuário e senha
    $host = "localhost";
    $dbname = "sistemabruma";
    $user = "root";
    $pass = "Sebrae@2026";

    //Conecta ao banco de dados
    try {
        $pdo = new PDO( 
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (PDOException $e) {
        die("Erro na conexão com o banco.");
    }
}

$tabela = "usuario";
?>