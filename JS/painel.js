document.addEventListener("DOMContentLoaded", function () {

    const menuItems = document.querySelectorAll(".menu-item");
    const pages = document.querySelectorAll(".page");

    menuItems.forEach(item => {

        item.addEventListener("click", function (e) {

            // Se não possui data-page, é um link normal
            if (!this.dataset.page) {
                return;
            }

            e.preventDefault();

            // ativa menu
            menuItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            // esconde páginas
            pages.forEach(p => p.classList.add("d-none"));

            // mostra página
            const pageId = this.dataset.page;
            const page = document.getElementById(pageId);

            if (page) {
                page.classList.remove("d-none");
            }

        });

    });

});

//Script do modal de exibição dos dados da clínica
document.querySelectorAll('.abrir-clinica').forEach(link => {

    link.addEventListener('click', function() {

        document.getElementById('clinicaNome').textContent =
            this.dataset.nome;

        document.getElementById('clinicaTelefone').textContent =
            this.dataset.telefone;

        document.getElementById('clinicaEmail').textContent =
            this.dataset.email;

        document.getElementById('clinicaBairro').textContent =
            this.dataset.bairro;

    });

});