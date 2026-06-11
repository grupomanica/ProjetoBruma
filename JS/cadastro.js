document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formCadastro");

    const mensagem = document.getElementById("mensagemCadastro");

    if (!form) {
        console.error("Formulário não encontrado");
        return;
    }

    form.addEventListener("submit", function (e) {

        e.preventDefault();

        const dados = new FormData(form);

        fetch("PHP/processa_cadastro.php", {

            method: "POST",

            body: dados

        })
        .then(response => response.json())
        .then(data => {

            mensagem.innerHTML = `
                <div class="alert alert-${data.tipo} mt-3">
                    ${data.mensagem}
                </div>
            `;

            if (data.sucesso) {

                form.reset();

                setTimeout(() => {

                    window.location.href = "PHP/login.php";

                }, 2000);

            }

        })
        .catch(error => {

            console.error(error);

            mensagem.innerHTML = `
                <div class="alert alert-danger mt-3">
                    Erro ao processar cadastro.
                </div>
            `;

        });

    });

});