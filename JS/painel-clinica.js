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

// FILTRO DE CLIENTES
const btnPesquisar =
    document.getElementById("btnPesquisar");

const btnLimpar =
    document.getElementById("btnLimpar");

if(btnPesquisar){
    btnPesquisar.addEventListener(
        "click",
        buscarClientes
    );
}

if(btnLimpar){

    btnLimpar.addEventListener("click", () => {

        document.getElementById("filtroNome").value = "";
        document.getElementById("filtroData").value = "";

        document.getElementById(
            "resultadoClientes"
        ).innerHTML = `
            <div class="alert alert-info">
                Digite um nome ou selecione uma data.
            </div>
        `;
    });
}

function buscarClientes() {

    const nome =
        document.getElementById("filtroNome").value;

    const data =
        document.getElementById("filtroData").value;

    fetch(
        "buscar-clientes.php?nome="
        + encodeURIComponent(nome)
        + "&data="
        + encodeURIComponent(data)
    )

    .then(response => response.json())

    .then(clientes => {

        let html = "";

        if(clientes.length === 0){

            html = `
                <div class="alert alert-info">
                    Nenhum cliente encontrado.
                </div>
            `;

        }else{

            html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Serviço</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            clientes.forEach(cliente => {

             const dataBrasil = new Date(cliente.data_disponivel)
    .toLocaleDateString('pt-BR');

html += `
    <tr>
        <td>${cliente.cliente}</td>
        <td>${dataBrasil}</td>
        <td>${cliente.horario.substring(0,5)}</td>
        <td>${cliente.servico}</td>
    </tr>
`;   

            });

            html += `
                    </tbody>
                </table>
            `;
        }

        document.getElementById(
            "resultadoClientes"
        ).innerHTML = html;

    })

    .catch(error => {

        console.error(error);

        document.getElementById(
            "resultadoClientes"
        ).innerHTML = `
            <div class="alert alert-danger">
                Erro ao buscar clientes.
            </div>
        `;

    });

}