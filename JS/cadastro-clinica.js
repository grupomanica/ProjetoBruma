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

    const progress = ((currentStep + 1) / steps.length) * 100;
    progressBar.style.width = progress + "%";
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