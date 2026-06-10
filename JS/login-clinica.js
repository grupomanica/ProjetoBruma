document.getElementById("login-clinica-form")
.addEventListener("submit", function(e){

    e.preventDefault();

    let formulario = new FormData(this);

    fetch("autenticar-clinica.php", {

        method: "POST",
        body: formulario

    })

    .then(response => response.json())

    .then(resposta => {

        let mensagem =
            document.getElementById("mensagem");

        let botao =
            document.querySelector(".login-btn");

        if(resposta.status === "erro"){

            mensagem.innerHTML =
                resposta.mensagem;

            mensagem.style.color =
                "#fa7c47";

            mensagem.style.fontWeight =
                "bold";

        } else {

            let segundos = 3;

            botao.disabled = true;
            botao.innerHTML =
                "Redirecionando...";

            mensagem.style.color =
                "#fa7c47";

            mensagem.style.fontWeight =
                "bold";

            mensagem.innerHTML = `
                ${resposta.mensagem}
                <br>
                <small id="contador">
                    Redirecionando em ${segundos}.
                </small>
            `;

            let contador = setInterval(() => {

                segundos--;

                if(segundos > 0){

                    document.getElementById("contador")
                    .innerHTML =
                    `Redirecionando em ${segundos}.`;

                } else {

                    clearInterval(contador);

                }

            }, 1000);

            setTimeout(() => {

                window.location.href =
                    resposta.redirect;

            }, 3000);

        }

    })

    .catch(error => {

        console.error(error);

        document.getElementById("mensagem")
        .innerHTML =
        "Erro ao conectar com o servidor.";

    });

});