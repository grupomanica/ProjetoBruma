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