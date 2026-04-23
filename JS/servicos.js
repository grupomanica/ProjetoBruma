const servicos = [
    {
        nome: "Limpeza",
        clinica: "Bella Estética",
        regiao: "Sul",
        descricao: "Limpeza profunda com hidratação",
        valor: 120,
        datas: [
            {
                dia: "06/04",
                semana: "Seg",
                horarios: ["10:00", "11:30", "14:00"]
            },
            {
                dia: "07/04",
                semana: "Ter",
                horarios: ["09:00", "13:00"]
            }
        ]
    },
    {
        nome: "Botox",
        clinica: "Estética Prime",
        regiao: "Oeste",
        descricao: "Aplicação de toxina botulínica",
        valor: 350,
        datas: [
            {
                dia: "08/04",
                semana: "Qua",
                horarios: ["09:00", "13:00", "16:30"]
            },
            {
                dia: "09/04",
                semana: "Qui",
                horarios: ["10:00", "15:00"]
            }
        ]
    }
];

const lista = document.getElementById("lista-servicos");
const filtros = document.querySelectorAll(".filtro");
const contador = document.getElementById("resultado-count");

function formatarValor(v) {
    return `R$ ${v.toFixed(2).replace(".", ",")}`;
}

function renderizar(listaFiltrada) {

    lista.innerHTML = "";
    contador.innerText = `${listaFiltrada.length} resultados encontrados`;

    listaFiltrada.forEach(servico => {

        lista.innerHTML += `
        <div class="col-md-4">
            <div class="servico-card">

                <div class="servico-nome">${servico.nome}</div>
                <div class="clinica">${servico.clinica}</div>

                <div class="descricao">${servico.descricao}</div>

                <span class="tag">${servico.regiao}</span>

                <div class="agenda-scroll mt-3">
                    ${servico.datas.map(data => `
                        <div class="dia-bloco">

                            <div class="dia-header">
                                ${data.semana} • ${data.dia}
                            </div>

                            <div class="horarios-linha">
                                ${data.horarios.map(h => `
                                    <span 
                                        class="hora"
                                        data-servico="${servico.nome}"
                                        data-clinica="${servico.clinica}"
                                        data-valor="${servico.valor}"
                                        data-data="${data.dia}"
                                        data-hora="${h}"
                                    >
                                        ${h}
                                    </span>
                                `).join("")}
                            </div>

                        </div>
                    `).join("")}
                </div>

                <div class="servico-footer">

                    <div class="preco-box">
                        <span class="preco-label">A partir de</span>
                        <span class="preco-valor">${formatarValor(servico.valor)}</span>
                    </div>

                    <form action="agendamento.php" method="POST">
                        <input type="hidden" name="servico" value="${servico.nome}">
                        <input type="hidden" name="clinica" value="${servico.clinica}">
                        <input type="hidden" name="valor" value="${servico.valor}">
                        <button class="btn agendar-btn">Agendar</button>
                    </form>

                </div>

            </div>
        </div>
        `;
    });
}

function aplicarFiltros() {

    let filtrados = [...servicos];

    filtros.forEach(filtro => {

        if (filtro.value) {

            const tipo = filtro.dataset.filter;

            if (tipo === "preco") {

                filtrados = filtrados.filter(s => {
                    const v = s.valor;

                    if (filtro.value === "0-100") return v <= 100;
                    if (filtro.value === "100-200") return v > 100 && v <= 200;
                    if (filtro.value === "200-400") return v > 200 && v <= 400;
                    if (filtro.value === "400+") return v > 400;

                    return true;
                });

            } else {
                filtrados = filtrados.filter(s => s[tipo] == filtro.value);
            }
        }
    });

    renderizar(filtrados);
}

filtros.forEach(f => f.addEventListener("change", aplicarFiltros));

renderizar(servicos);

document.addEventListener("click", function(e) {

    const horaEl = e.target.closest(".hora");

    if (!horaEl) return;

    // pega dados
    const servico = horaEl.dataset.servico;
    const clinica = horaEl.dataset.clinica;
    const valor = horaEl.dataset.valor;
    const data = horaEl.dataset.data;
    const hora = horaEl.dataset.hora;

    // debug
    console.log("Enviando:", { servico, clinica, valor, data, hora });

    // preenche form
    document.getElementById("input-servico").value = servico;
    document.getElementById("input-clinica").value = clinica;
    document.getElementById("input-valor").value = valor;
    document.getElementById("input-data").value = data;
    document.getElementById("input-hora").value = hora;

    // envia
    document.getElementById("form-agendamento").submit();
});