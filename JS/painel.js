document.addEventListener("DOMContentLoaded", function () {

    const menuItems = document.querySelectorAll(".menu-item");
    const pages = document.querySelectorAll(".page");

    menuItems.forEach(item => {

        item.addEventListener("click", function (e) {
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