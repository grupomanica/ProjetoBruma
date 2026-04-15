<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.   " rel="stylesheet">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/login.css">
    <style>
        /* BOTÃO VOLTAR */
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;

            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);

            color: white;
            font-size: 18px;
            text-decoration: none;

            transition: 0.3s;
            z-index: 1000;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
    </style>

    <script src="../JS/login.js" defer></script>

    <title>Bruma | Login</title>
</head>
<body>
    <!-- BOTÃO VOLTAR -->
    <a href="../index.html" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="login-container">

        <div class="login-box">

            <!-- LOGO -->
            <div class="text-center mb-4">
                <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="140">
            </div>

            <h3 class="text-center mb-3">Bem-vindo de volta</h3>
            <p class="text-center subtitle">Acesse sua conta para continuar</p>

            <!-- FORM -->
            <form action="autenticar.php" method="POST">
                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="senha" placeholder="Senha" required>
                </div>

                <button type="submit" class="btn login-btn w-100">
                    Entrar
                </button>
            </form>

            <!-- DIVISOR -->
            <div class="divider">
            </div>

            <!-- CRIAR CONTA -->
            <p class="text-center mt-3">
                <br>
                Não tem conta? 
                <a href="../PHP/cadastro.php" class="link">Criar agora</a>
            </p>

        </div>

    </div>

</body>
</html>