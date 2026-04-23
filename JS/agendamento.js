let currentStep = 1;

function showStep(step) {
    document.querySelectorAll(".step-content").forEach(el => el.classList.remove("active"));
    document.getElementById("content-" + step).classList.add("active");

    document.querySelectorAll(".step").forEach(el => el.classList.remove("active"));
    document.getElementById("step-" + step).classList.add("active");
}

function nextStep() {
    currentStep++;
    showStep(currentStep);
}

function prevStep() {
    currentStep--;
    showStep(currentStep);
}

function validarEtapa2() {
    const idade = document.getElementById("idade").checked;
    const alergia = document.getElementById("alergia").checked;

    if (!idade || !alergia) {
        alert("Confirme os requisitos para continuar");
        return;
    }

    nextStep();
}

function copiarPix() {
    const input = document.getElementById("pixInput");

    input.select();
    document.execCommand("copy");

    alert("PIX copiado!");
}