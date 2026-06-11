document.getElementById("cep").addEventListener("blur", function() {

    const cep = this.value.replace(/\D/g, "");

    if (cep.length !== 8) return;

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {

            if (data.erro) return;

            document.getElementById("cidade").value = data.localidade;
            document.getElementById("bairro").value = data.bairro;
            document.getElementById("logradouro").value = data.logradouro;

        });
});

const steps = document.querySelectorAll(".form-step");
const nextBtns = document.querySelectorAll(".next-btn");
const prevBtns = document.querySelectorAll(".prev-btn");

let currentStep = 0;

function updateSteps() {

    steps.forEach((step, i) => {
        step.classList.toggle("active", i === currentStep);
    });

}

// avançar
nextBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            updateSteps();
        }
    });
});

// voltar
prevBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        if (currentStep > 0) {
            currentStep--;
            updateSteps();
        }
    });
});

// iniciar
updateSteps();
// CADASTRO VIA AJAX

const form = document.getElementById("formCadastroClinica");

if (form) {

    form.addEventListener("submit", function (e) {

        e.preventDefault();

        const mensagem = document.getElementById("mensagemCadastroClinica");

        const campos = form.querySelectorAll("input, select");

    let vazio = false;

    campos.forEach(campo => {
        if (!campo.value.trim()) {
            vazio = true;
        }
    });

    if (vazio) {

        mensagem.innerHTML = `
            <div class="alert alert-danger">
                Todos os campos devem ser preenchidos.
            </div>
        `;

        return;
    }

        const dados = new FormData(this);

        fetch("cadastrar-clinica.php", {
            method: "POST",
            body: dados
        })
        .then(response => response.json())
        .then(data => {

            mensagem.innerHTML = `
                <div class="alert alert-${data.tipo}">
                    ${data.mensagem}
                </div>
            `;

            if (data.sucesso) {

                form.reset();

                setTimeout(() => {
                    window.location.href = "login-clinica.php";
                }, 2500);

            }

        })
        .catch(error => {

            console.error(error);

            mensagem.innerHTML = `
                <div class="alert alert-danger">
                    Erro ao processar cadastro.
                </div>
            `;

        });

    });

}