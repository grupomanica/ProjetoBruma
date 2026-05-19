let currentStep = 1;

function showStep(step) {

    document
        .querySelectorAll(".step-content")
        .forEach(el => el.classList.remove("active"));

    document
        .getElementById("content-" + step)
        .classList.add("active");

    document
        .querySelectorAll(".step")
        .forEach(el => el.classList.remove("active"));

    document
        .getElementById("step-" + step)
        .classList.add("active");
}

function nextStep() {

    currentStep++;

    showStep(currentStep);
}

function prevStep() {

    currentStep--;

    showStep(currentStep);
}

function validarEtapa2(){

    const nome =
        document.getElementById('nome').value;

    const sobrenome =
        document.getElementById('sobrenome').value;

    const telefone =
        document.getElementById('telefone').value;

    const dataNascimento =
        document.getElementById('dataNascimento').value;

    if(
        nome.trim() === '' ||
        sobrenome.trim() === '' ||
        telefone.trim() === '' ||
        dataNascimento.trim() === ''
    ){

        alert('Preencha todos os campos.');

        return;
    }

    // VALIDA IDADE
    const hoje = new Date();

    const nascimento = new Date(dataNascimento);

    let idade =
        hoje.getFullYear() -
        nascimento.getFullYear();

    const mes =
        hoje.getMonth() -
        nascimento.getMonth();

    if(
        mes < 0 ||
        (mes === 0 && hoje.getDate() < nascimento.getDate())
    ){
        idade--;
    }

    if(idade < 18){

        alert(
            'Você deve ser maior de idade para continuar o agendamento.'
        );

        return;
    }

    // salva hidden
    document.getElementById(
        'dataNascimentoInput'
    ).value = dataNascimento;

    nextStep();
}

function copiarPix() {

    const input =
        document.getElementById("pixInput");

    input.select();

    document.execCommand("copy");

    alert("PIX copiado!");
}