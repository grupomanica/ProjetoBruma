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

    <link rel="stylesheet" href="../CSS/login-parceiro.css">

    <title>Bruma | Área da Clínica</title>
</head>

<body>

<!-- BOTÃO VOLTAR -->
<a href="../index.html" class="back-btn">
    <i class="bi bi-arrow-left"></i>
</a>

<div class="login-container">

    <form class="login-form" action="" method="POST">

        <!-- LOGO -->
        <div class="text-center mb-3">
             <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="100">
        </div>

        <h3 class="text-center mb-2">Área da clínica</h3>
        <p class="text-center subtitle">
            Acesse sua conta e gerencie seus serviços
        </p>

        <!-- EMAIL -->
        <div class="input-group-custom">
            <i class="bi bi-envelope"></i>
            <input type="email" name="email" placeholder="E-mail da clínica" required>
        </div>

        <!-- SENHA -->
        <div class="input-group-custom">
            <i class="bi bi-lock"></i>
            <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <!-- ESQUECI SENHA -->
        <div class="text-end mb-2">
            <a href="#" class="link small">Esqueci minha senha</a>
        </div>

        <!-- BOTÃO -->
        <button type="submit" class="btn login-btn w-100">
            Entrar
        </button>

        <!-- CADASTRO -->
        <p class="text-center mt-3">
            Ainda não é parceiro?
            <a href="cadastro-clinica.php" class="link">Cadastrar clínica</a>
        </p>

    </form>

</div>

</body>
</html>