<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/cadastro.css">

    <title>Bruma | Cadastro</title>
</head>
<body>
    <!-- BOTÃO VOLTAR -->
    <a href="../index.html" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

<div class="cadastro-container">

    <div class="cadastro-box">

        <div class="text-center mb-4">
            <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="140">
        </div>

        <h3 class="text-center mb-3">Crie sua conta</h3>
        <p class="text-center subtitle">É rápido, gratuito e leva menos de 1 minuto</p>

        <form action="processa_cadastro.php" method="POST">

            <div class="input-group-custom">
                <i class="bi bi-person"></i>
                <input type="text" name="nome" placeholder="Nome completo" required>
            </div>

            <div class="input-group-custom">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" placeholder="E-mail" required>
            </div>

            <div class="input-group-custom">
                <i class="bi bi-lock"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>

            <div class="input-group-custom">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
            </div>

            <button type="submit" class="btn cadastro-btn w-100">
                Criar conta
            </button>

        </form>

        <p class="text-center mt-3">
            Já tem conta? 
            <a href="login.php" class="link">Entrar</a>
        </p>

    </div>

</div>

</body>
</html>