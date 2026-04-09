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

    <link rel="stylesheet" href="../CSS/cadastro-clinica.css">
    <script src="../JS/cadastro-clinica.js" defer></script>

    <title>Bruma | Cadastro de Clínica</title>
</head>

<body>
    <!-- BOTÃO VOLTAR -->
    <a href="../index.html" class="btn-voltar">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="cadastro-container">

        <form class="cadastro-form" method="POST">
            <!-- LOGO -->
            <div class="text-center mb-3">
                <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="100">
            </div>

            <h3 class="text-center mb-2">Cadastrar clínica</h3>
            <p class="text-center subtitle">Atraia novos clientes com a Bruma</p>
            
            <!-- PROGRESSO -->
            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>

            <!-- ETAPA 1 -->
            <div class="form-step active">
                <h5 class="step-title">Dados da clínica</h5>

                <div class="input-group-custom">
                    <i class="bi bi-building"></i>
                    <input type="text" placeholder="Nome da clínica" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-card-text"></i>
                    <input type="text" placeholder="CNPJ" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-whatsapp"></i>
                    <input type="text" placeholder="WhatsApp" required>
                </div>

                <button type="button" class="btn cadastro-btn w-100 next-btn">
                    Continuar
                </button>
            </div>

            <p class="text-center mt-3">
                Já tem conta? 
                <a href="login-parceiro.php" class="link">
                    Entrar
                </a>
            </p>

            <!-- ETAPA 2 -->
            <div class="form-step">
                <h5 class="step-title">Localização</h5>

                <div class="input-group-custom">
                    <i class="bi bi-geo-alt"></i>
                    <input type="text" id="cep" placeholder="CEP">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-geo"></i>
                    <input type="text" id="cidade" placeholder="Cidade" readonly>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-pin-map"></i>
                    <input type="text" id="bairro" placeholder="Bairro" readonly>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-signpost"></i>
                    <input type="text" id="logradouro" placeholder="Endereço">
                </div>

                <div class="step-actions">
                    <button type="button" class="btn btn-outline-light prev-btn">Voltar</button>
                    <button type="button" class="btn cadastro-btn next-btn">Continuar</button>
                </div>
            </div>

            <!-- ETAPA 3 -->
            <div class="form-step">
                <h5 class="step-title">Serviços e acesso</h5>

                <div class="section-label">Procedimentos</div>

                <div class="procedimentos">
                    <label><input type="checkbox"> Limpeza</label>
                    <label><input type="checkbox"> Botox</label>
                    <label><input type="checkbox"> Preenchimento</label>
                </div>

                <div class="input-group-custom mt-2">
                    <i class="bi bi-plus-circle"></i>
                    <input type="text" placeholder="Outros procedimentos">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-currency-dollar"></i>
                    <select>
                        <option>Faixa de preço</option>
                        <option>Até R$100</option>
                        <option>R$100 - R$300</option>
                        <option>Acima de R$300</option>
                    </select>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" placeholder="E-mail" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input type="password" placeholder="Senha" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" placeholder="Confirmar senha" required>
                </div>

                <div class="step-actions">
                    <button type="button" class="btn btn-outline-light prev-btn">Voltar</button>
                    <button type="submit" class="btn cadastro-btn">Finalizar cadastro</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>