document.addEventListener("DOMContentLoaded", function () {

    const menuItems = document.querySelectorAll(".menu-item");
    const pages = document.querySelectorAll(".page");

    menuItems.forEach(item => {

        item.addEventListener("click", function (e) {
            e.preventDefault();

            // menu ativo
            menuItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            // esconder páginas
            pages.forEach(p => p.classList.add("d-none"));

            // mostrar página
            const pageId = this.dataset.page;
            const page = document.getElementById(pageId);

            if (page) {
                page.classList.remove("d-none");
            }
        });

    });

});

//Atualizar seção 'serviços' sem recarregar
document.addEventListener("click", function(e){

    if(e.target.closest(".btn-editar-servico")){

        const botao =
            e.target.closest(".btn-editar-servico");

        const id = botao.dataset.id;

        fetch(
            "buscar-servico.php?id=" + id
        )

        .then(res => res.text())

        .then(html => {

            document
                .getElementById(
                    "conteudo-edicao-servico"
                )
                .innerHTML = html;

            document
                .getElementById(
                    "area-edicao-servico"
                )
                .classList.remove("d-none");

            document
                .getElementById(
                    "area-edicao-servico"
                )
                .scrollIntoView({
                    behavior: "smooth"
                });

        });

    }

});

//Botão cancelar
document.addEventListener("click", function(e){

    if(e.target.id === "cancelar-edicao"){

        document
            .getElementById(
                "area-edicao-servico"
            )
            .classList.add("d-none");

        document
            .getElementById(
                "conteudo-edicao-servico"
            )
            .innerHTML = "";

    }

});