<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../ASSETS/IMG/favicon/logo-iconeFullSize.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <form class="cadastro-form" action="cadastrar-clinica.php" method="POST">

            <!-- LOGO -->
            <div class="text-center mb-3">
                <img src="../ASSETS/IMG/logo-cNomeFullSize.png" width="100">
            </div>

            <h3 class="text-center mb-2">Cadastrar clínica</h3>
            <hr>

            <!-- ETAPA 1 -->
            <div class="form-step active">            
                <div class="input-group-custom">
                    <i class="bi bi-building"></i>
                    <input 
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Nome da clínica"
                        required
                    >
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-card-text"></i>
                    <input 
                        type="text"
                        id="cnpj"
                        name="cnpj"
                        placeholder="CNPJ"
                        maxlength="14"
                        required
                    >
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-whatsapp"></i>
                    <input 
                        type="text"
                        id="telefone"
                        name="telefone"
                        placeholder="WhatsApp (sem ddd)"
                        maxlength="9"
                        required
                    >
                </div>

                <button type="button" class="btn cadastro-btn w-100 next-btn">
                    Continuar
                </button>

                <br><br>

                <p class="text-center mt-1">
                    Já tem conta?
                    <a href="login-clinica.php" class="link">
                        Entrar
                    </a>
                </p>
            </div>

            <!-- ETAPA 2 -->
            <div class="form-step">
                <div class="input-group-custom">
                    <i class="bi bi-geo-alt"></i>
                    <input 
                        type="text"
                        id="cep"
                        name="cep"
                        placeholder="CEP"
                    >
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-geo"></i>
                    <input 
                        type="text"
                        id="cidade"
                        name="cidade"
                        placeholder="Cidade"
                        readonly
                    >
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-pin-map"></i>
                    <input 
                        type="text"
                        id="bairro"
                        name="bairro"
                        placeholder="Bairro"
                        readonly
                    >

                    <select name="regiao" class="form-select" required>
                        <option value="">Selecione a região</option>
                        <option value="Centro">Centro</option>
                        <option value="Norte">Zona Norte</option>
                        <option value="Sul">Zona Sul</option>
                        <option value="Leste">Zona Leste</option>
                        <option value="Oeste">Zona Oeste</option>
                    </select>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-signpost"></i>
                    <input 
                        type="text"
                        id="logradouro"
                        name="logradouro"
                        placeholder="Endereço"
                    >
                </div>

                <div class="step-actions">
                    <button type="button" class="btn cadastro-btn prev-btn">
                        Voltar
                    </button>

                    <button type="button" class="btn cadastro-btn next-btn">
                        Continuar
                    </button>
                </div>
            </div>

            <!-- ETAPA 3 -->
            <div class="form-step">
                <div class="input-group-custom">
                    <i class="bi bi-currency-dollar"></i>
                    <select 
                        id="faixa_preco"
                        name="faixa_preco"
                    >
                        <option value="">Faixa de preço</option>
                        <option value="ate_100">Até R$100</option>
                        <option value="100_300">R$100 - R$300</option>
                        <option value="acima_300">Acima de R$300</option>
                    </select>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input 
                        type="email"
                        id="email"
                        name="email"
                        placeholder="E-mail"
                        required
                    >
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input 
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Senha"
                        required
                    >
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock-fill"></i>
                    <input 
                        type="password"
                        id="confirmar_senha"
                        name="confirmar_senha"
                        placeholder="Confirmar senha"
                        required
                    >
                </div>

                <div class="step-actions">
                    <button type="button" class="btn cadastro-btn prev-btn">
                        Voltar
                    </button>

                    <button type="submit" class="btn cadastro-btn">
                        Finalizar cadastro
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>