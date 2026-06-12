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

    const sobrenomeCampo =
        document.getElementById('sobrenome');

    if(
        sobrenomeCampo &&
        sobrenomeCampo.value.trim() === ''
    ){
        alert('Informe seu sobrenome.');
        return;
    }

    const dataNascimentoCampo =
        document.getElementById('dataNascimento');

    if(
        dataNascimentoCampo &&
        dataNascimentoCampo.value.trim() === ''
    ){
        alert('Informe sua data de nascimento.');
        return;
    }

    nextStep();
}

function copiarPix() {

    const input =
        document.getElementById("pixInput");

    input.select();

    document.execCommand("copy");

    alert("PIX copiado!");
}

function atualizarProfissional(){

    if(profissionalSelect.value === ''){
        profissionalIdInput.value = '';
        profissionalNomeInput.value = '';
        return;
    }

    profissionalIdInput.value =
        profissionalSelect.value;

    profissionalNomeInput.value =
        profissionalSelect.options[
            profissionalSelect.selectedIndex
        ].text;
}

function validarEtapa1(){

    if(profissionalSelect.value === ''){

        alert('Selecione um profissional.');

        return;
    }

    nextStep();
}

const profissionalSelect = document.getElementById('profissionalSelect');
const profissionalIdInput = document.getElementById('profissionalIdInput');
const profissionalNomeInput = document.getElementById('profissionalNomeInput');

function atualizarProfissional(){
    profissionalIdInput.value =
        profissionalSelect.value;

    profissionalNomeInput.value =
        profissionalSelect.options[
            profissionalSelect.selectedIndex
        ].text;
}

atualizarProfissional();
profissionalSelect.addEventListener(
    'change',
    atualizarProfissional
);

    const campoDataNascimento = document.getElementById('dataNascimento');
    const inputHiddenNascimento = document.getElementById('dataNascimentoInput');

    campoDataNascimento.addEventListener('change', function(){
        inputHiddenNascimento.value = this.value;
    });

    if(campoDataNascimento && inputHiddenNascimento){
        campoDataNascimento.addEventListener(
            'change',
            function(){
                inputHiddenNascimento.value = this.value;
            }
        );
    }

function validarDados(){
const nome = document.querySelector('input[type="text"]').value;
const sobrenome = document.querySelectorAll('input[type="text"]')[1].value;
const telefone = document.getElementById('telefone').value;
const dataNascimento = document.getElementById('dataNascimento').value;
if(
    sobrenome.trim() === '' ||
    dataNascimento.trim() === ''
){
    alert('Preencha todos os campos.');
    return;
}

// VALIDA IDADE
const hoje = new Date();
const nascimento = new Date(dataNascimento);
let idade = hoje.getFullYear() - nascimento.getFullYear();
const mes = hoje.getMonth() - nascimento.getMonth();

if( mes < 0 ||
    (mes === 0 && hoje.getDate() < nascimento.getDate())
){idade--;}

if(idade < 18){
    alert('Você precisa ser maior de 18 anos para continuar o agendamento.');
    return;
}

// salva no hidden
document.getElementById('dataNascimentoInput').value =
    dataNascimento;
// vai para próxima etapa
nextStep();
}