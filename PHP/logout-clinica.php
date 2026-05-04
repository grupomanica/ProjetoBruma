<?php
session_start();

// Remove todas as variáveis da sessão
$_SESSION = [];

// Remove o cookie da sessão (opcional, mas recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para login da clínica
header("Location: login-clinica.php");
exit();
?>