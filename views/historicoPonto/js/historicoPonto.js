$(document).ready(async function () {
    document.getElementById("bemVindo")?.classList.add("hidden");
    await getPontos();
    setTimeout(() => {
        document.getElementById("controlador").classList.remove("hidden");
    }, 40);
    setTimeout(() => {
        const tbl = document.getElementById("tblHistoricoPonto");
        tbl.classList.remove("opacity-0", "translate-y-4");
    }, 80);
});

// ----------------------------
// CONFIGURAÇÕES
// ----------------------------
let paginaAtual = 1;
const itensPorPagina = 4;
let dadosTabela = [];
let dadosFiltrados = [];

// ----------------------------
// ICONES
// ----------------------------
const icones = {
    "ADIANTADO": `<i class="fas fa-exclamation-circle text-blue-300"></i>`,
    "CONCLUIDO": `<i class="fas fa-check text-green-400"></i>`,
    "ATRASADO": `<i class="fas fa-triangle-exclamation text-red-400"></i>`,
    "NAO_TRABALHADO": `<i class="fas fa-times-circle text-gray-400"></i>`
};

// ----------------------------
// STATUS
// ----------------------------
function determinarStatus(p) {
    const partes = [
        p.STATUS_ENTRADA1,
        p.STATUS_SAIDA1,
        p.STATUS_ENTRADA2,
        p.STATUS_SAIDA2
    ];

    if (partes.every(s => !s)) {
        return { label: "Não trabalhado", class: "text-gray-400 border-gray-500", icon: icones.NAO_TRABALHADO };
    }
    if (partes.includes("ADIANTADO")) {
        return { label: "Adiantado", class: "text-blue-300 border-blue-400", icon: icones.ADIANTADO };
    }
    if (partes.includes("ATRASADO")) {
        return { label: "Atrasado", class: "text-red-300 border-red-400", icon: icones.ATRASADO };
    }
    return { label: "Concluído", class: "text-green-300 border-green-400", icon: icones.CONCLUIDO };
}

// ----------------------------
// MONTAR TABELA
// ----------------------------
function montarTabela() {
    const tbody = document.querySelector("#tblHistoricoPonto tbody");
    tbody.innerHTML = "";

    const totalPaginas = Math.ceil(dadosFiltrados.length / itensPorPagina) || 1;
    if (paginaAtual > totalPaginas) paginaAtual = totalPaginas;

    const inicio = (paginaAtual - 1) * itensPorPagina;
    const fim = inicio + itensPorPagina;

    const pagina = dadosFiltrados.slice(inicio, fim);

    if (pagina.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-400">
                    Nenhum registro encontrado
                </td>
            </tr>
        `;
    } else {
        pagina.forEach(ponto => {
            const s = determinarStatus(ponto);

            tbody.innerHTML += `
                <tr class="bg-slate-800/50 border-b border-slate-700/30 hover:bg-slate-700/30 transition">
                    <td class="px-4 py-3 font-medium text-white">${ponto.DATA}</td>
                    <td class="px-4 py-3 text-center text-cyan-300">${ponto.ENTRADA1 ?? "-"}</td>
                    <td class="px-4 py-3 text-center text-cyan-300">${ponto.SAIDA1 ?? "-"}</td>
                    <td class="px-4 py-3 text-center text-cyan-300 hidden sm:table-cell">${ponto.ENTRADA2 ?? "-"}</td>
                    <td class="px-4 py-3 text-center text-cyan-300 hidden sm:table-cell">${ponto.SAIDA2 ?? "-"}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border ${s.class}">
                            ${s.icon} ${s.label}
                        </span>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById("pageInfo").innerText =
        `Página ${paginaAtual} de ${totalPaginas}`;
}

// ----------------------------
// PAGINAÇÃO
// ----------------------------
document.getElementById("btnPrev").onclick = () => {
    if (paginaAtual > 1) {
        paginaAtual--;
        montarTabela();
    }
};

document.getElementById("btnNext").onclick = () => {
    const total = Math.ceil(dadosFiltrados.length / itensPorPagina);
    if (paginaAtual < total) {
        paginaAtual++;
        montarTabela();
    }
};

// ----------------------------
// EXPORT CSV
// ----------------------------
document.getElementById("btnExportCSV").onclick = () => {
    let csv = "DATA,ENTRADA1,SAIDA1,ENTRADA2,SAIDA2,STATUS\n";

    dadosFiltrados.forEach(p => {
        const s = determinarStatus(p).label;
        csv += `${p.DATA},${p.ENTRADA1 || "-"},${p.SAIDA1 || "-"},${p.ENTRADA2 || "-"},${p.SAIDA2 || "-"},${s}\n`;
    });

    const blob = new Blob([csv], { type: "text/csv" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");

    a.href = url;
    a.download = "historico_pontos.csv";
    a.click();

    URL.revokeObjectURL(url);
};

// ----------------------------
// EXPORT PDF
// ----------------------------
document.getElementById("btnExportPDF").onclick = () => {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "pdf/gerarpdf.php";
    form.target = "_blank";

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "dados";
    input.value = JSON.stringify(dadosFiltrados);

    form.appendChild(input);
    document.body.appendChild(form);

    form.submit();
    form.remove();
};

// ----------------------------
// CARREGAR DO BACKEND
// ----------------------------
async function getPontos() {
    try {
        const res = await axios.post("/backend/backend.php", {
            function: "loadPontosFuncionario"
        });

        dadosTabela = res.data.pontos || [];
        dadosFiltrados = [...dadosTabela];

        montarTabela();
    } catch (e) {
        console.error("Erro ao carregar pontos:", e);
    }
}

// ----------------------------
// FILTRO
// ----------------------------
document.getElementById("btnFiltrar").addEventListener("click", () => {
    const inicio = document.getElementById("dataInicio").value;
    const fim = document.getElementById("dataFim").value;

    dadosFiltrados = dadosTabela.filter(p => {
        if (inicio && p.DATA < inicio) return false;
        if (fim && p.DATA > fim) return false;
        return true;
    });

    paginaAtual = 1;
    montarTabela();
});
