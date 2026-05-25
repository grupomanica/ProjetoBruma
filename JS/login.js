document.getElementById("login-form")
.addEventListener("submit", function(e){

    e.preventDefault();

    let formulario = new FormData(this);

    fetch("../PHP/autenticar.php", {
        
        method: "POST",        
        body: formulario

    })

    .then(response => response.json())

    .then(resposta => {

        let mensagem = document.getElementById("mensagem");

        mensagem.innerHTML = resposta.mensagem;

        if(resposta.status === "erro"){

            mensagem.style.color = "#fa7c47",
            mensagem.style.fontWeight = "bold";

        } else{

            mensagem.style.color = "#fa7c47",
            mensagem.style.fontWeight = "bold";
          
            setTimeout(() => {

                window.location.href = resposta.redirect;

            }, 3000)
        }
    })

    .catch(error => {

        console.error("Erro:", error);
    });
});