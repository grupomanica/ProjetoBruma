<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../CSS/login.css">
    <title>Bruma | Login</title>
</head>

<body>
<!-- BOTÃO VOLTAR -->
<a href="../index.html" class="btn-voltar">
    <i class="bi bi-arrow-left"></i>
</a>

<div class="login-container">

        <form class="login-form" action="autenticar.php" method="POST">
            <!-- LOGO -->
            <div class="text-center mb-4">
                <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="140">
            </div>

            <h3 class="text-center mb-3">Bem-vindo de volta</h3>
            <p class="text-center subtitle">
                Acesse sua conta para continuar
            </p>
            <hr>

            <!-- Email -->
            <div class="input-group-custom">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" placeholder="E-mail do cliente" required>
            </div>

            <!--Senha-->
            <div class="input-group-custom">
                <i class="bi bi-lock"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>

            <!-- Botão -->
            <button type="submit" class="btn login-btn w-100">
                Entrar
            </button>
    </form>
</div>

</body>
</html>