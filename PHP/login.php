<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fav Icon --><link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" type="image/x-icon">
    <!-- Bootstrap --><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones --><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS --><link rel="stylesheet" href="../CSS/login.css">
    <title>Bruma | Login</title>
</head>

<body>
    <a href="../index.html" class="btn-voltar" aria-label="Voltar" aria-describedby="desc-voltar"><i class="bi bi-arrow-left"></i></a>
    <span id="desc-voltar" class="visually-hidden">Retorna para a página inicial do sistema.</span>

    <div class="login-container">
        <form id="login-form" class="login-form">
            <div class="text-center mb-4">
                <a href="painel.php">
                    <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="140"  alt=" Logo da Plataforma Bruma">
                </a>
            </div>

            <h3 class="text-center mb-3">Bem-vindo de volta</h3>
            <p class="text-center subtitle">
                Acesse sua conta para continuar
            </p>

            <hr>
            
            <div class="input-group-custom">
                <i class="bi bi-envelope" aria-label="email"></i>
                <input type="email" name="email" placeholder="E-mail do cliente" style="color:white;" required>
            </div>

            <div class="input-group-custom">
                <i class="bi bi-lock" aria-labelledby="senha"></i>
                <input type="password" name="senha" placeholder="Senha" style="color:white;" required>
            </div>
            <div id="mensagem"></div><br>

            <button type="submit" class="btn login-btn w-100" aria-label="Entrar na página do cliente">
                Entrar
            </button>
        </form>
    </div>
    <script src="../JS/login.js"></script>
</body>
</html>