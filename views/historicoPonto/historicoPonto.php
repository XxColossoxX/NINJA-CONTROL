<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header('Location: ../loginFuncionario/loginFuncionario.php');
    exit;
}

date_default_timezone_set('America/Sao_Paulo');
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerFuncionario.php');

$idFuncionario = $_SESSION['funcionario_id'] ?? '';
$nomeFuncionario = $_SESSION['funcionario_nome'] ?? '';
$fotoFuncionario = $_SESSION['funcionario_faceid'] ?? '';
$rgFuncionario = $_SESSION['funcionario_rg'] ?? '';
$cpfFuncionario = $_SESSION['funcionario_cpf'] ?? '';
$nomeEmpresa = $_SESSION['funcionario_nome_empresa'] ?? 'Nome da Empresa';
$dataNascimentoFuncionario = $_SESSION['funcionario_data_nascimento'] ?? 'Data de Nascimento';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histórico de Ponto</title>

<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
/* 🎨 Personalização Visual */
:root {
  --primary-color: #125f7a;
  --secondary-color: #0f172a;
  --accent-color: #00ffff;
  --success-color: #10b981;
  --warning-color: #f59e0b;
  --error-color: #ef4444;
}

/* Transição suave na tabela */
#tblHistoricoPonto {
  transition: all 0.4s ease;
}
</style>

</head>
<body class="bg-[#0f172a] min-h-screen flex flex-col items-center justify-center px-2 py-3">

<div id="controlador" class="w-full max-w-3xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-xl shadow-[0_0_20px_#00ffff20] p-4 sm:p-6 flex flex-col gap-4">

  <!-- TOPO COM FOTO E NOME -->
  <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
      <div class="flex items-center gap-3 w-full md:w-auto">
          <img src="<?= htmlspecialchars($fotoFuncionario); ?>" 
               class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-full border-4 border-cyan-500 object-cover shadow-[0_0_10px_#00ffff50]">
          <div class="min-w-0 flex-1">
              <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-white">Histórico de Ponto</h1>
              <p class="text-xs sm:text-sm text-gray-300 truncate">
                  Funcionário: <strong class="text-cyan-400"><?= htmlspecialchars($nomeFuncionario); ?></strong>
              </p>
          </div>
      </div>

      <!-- FILTROS DE DATA -->
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto mt-2 sm:mt-0">
          <input type="date" id="dataInicio"
                 class="flex-1 border border-cyan-500/30 rounded-lg px-2 py-1.5 bg-slate-800/80 text-white text-xs focus:ring-2 focus:ring-cyan-500/50">
          <span class="text-gray-400 text-xs hidden sm:inline">até</span>
          <input type="date" id="dataFim"
                 class="flex-1 border border-cyan-500/30 rounded-lg px-2 py-1.5 bg-slate-800/80 text-white text-xs focus:ring-2 focus:ring-cyan-500/50">
          <button class="btn-filtrar bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-3 py-2 rounded-lg text-xs font-semibold flex items-center gap-2">
              <i class="fas fa-filter"></i> Filtrar
          </button>
      </div>
  </div>

  <!-- EXPORTAÇÃO PDF -->
  <div class="flex gap-3 mt-3">
      <button class="btn-pdf px-3 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white text-xs font-semibold flex items-center gap-2">
          <i class="fas fa-file-pdf"></i> PDF
      </button>
  </div>

  <!-- TABELA DE PONTOS -->
  <div class="overflow-x-auto mt-2">
      <table id="tblHistoricoPonto"
             class="w-full text-xs text-left border border-slate-700 rounded-lg overflow-hidden opacity-0 translate-y-2">
          <thead class="bg-slate-800 text-cyan-300">
              <tr>
                  <th class="px-3 py-2 text-center text-white">DATA</th>
                  <th class="px-3 py-2 text-center text-white">ENTRADA 1</th>
                  <th class="px-3 py-2 text-center text-white">SAÍDA 1</th>
                  <th class="px-3 py-2 text-center text-white hidden sm:table-cell">ENTRADA 2</th>
                  <th class="px-3 py-2 text-center text-white hidden sm:table-cell">SAÍDA 2</th>
                  <th class="px-3 py-2 text-center text-white">STATUS</th>
              </tr>
          </thead>
          <tbody></tbody>
      </table>
  </div>

  <!-- PAGINAÇÃO -->
  <div class="flex justify-center items-center gap-3 mt-2">
      <button class="btn-prev px-3 py-1 bg-slate-700 text-white text-xs rounded hover:bg-slate-600">Anterior</button>
      <span id="pageInfo" class="text-white text-xs"></span>
      <button class="btn-next px-3 py-1 bg-slate-700 text-white text-xs rounded hover:bg-slate-600">Próximo</button>
  </div>

  <!-- RODAPÉ -->
  <div class="text-[10px] text-gray-400 mt-2 text-center pt-3 border-t border-slate-700/50">
      Ninja Control © <?= date('Y'); ?> - Todos os direitos reservados
  </div>

</div>

<!-- MODAL DETALHE -->
<div id="modalDetalhe" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="w-full max-w-sm bg-slate-900 border border-cyan-500/30 rounded-xl p-5 shadow-lg">
      <div id="modalConteudo"></div>
      <button id="fecharModalDetalhe"
              class="mt-5 w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:opacity-80 text-white py-2 rounded-lg font-semibold">
          Fechar
      </button>
  </div>
</div>

<script>
// ========== HISTÓRICO DE PONTO - FUNCIONAL ==========

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("bemVindo")?.classList.add("hidden");
    init();
});

let paginaAtual = 1;
const itensPorPagina = 3;
let dadosTabela = [];
let dadosFiltrados = [];

const icones = {
  "ADIANTADO": `<i class="fas fa-exclamation-circle text-blue-300"></i>`,
  "CONCLUIDO": `<i class="fas fa-check text-green-400"></i>`,
  "ATRASADO": `<i class="fas fa-triangle-exclamation text-red-400"></i>`,
  "NAO_TRABALHADO": `<i class="fas fa-times-circle text-gray-400"></i>`
};

function determinarStatus(p) {
  const partes = [p.STATUS_ENTRADA1, p.STATUS_SAIDA1, p.STATUS_ENTRADA2, p.STATUS_SAIDA2];
  if (partes.every(s => !s)) return { label: "Não trabalhado", class: "text-gray-400 border-gray-500", icon: icones.NAO_TRABALHADO };
  if (partes.includes("ADIANTADO")) return { label: "Adiantado", class: "text-blue-300 border-blue-400", icon: icones.ADIANTADO };
  if (partes.includes("ATRASADO")) return { label: "Atrasado", class: "text-red-300 border-red-400", icon: icones.ATRASADO };
  return { label: "Concluído", class: "text-green-300 border-green-400", icon: icones.CONCLUIDO };
}

function atualizarBotoesPagina() {
  const totalPaginas = Math.max(1, Math.ceil(dadosFiltrados.length / itensPorPagina));
  document.querySelector(".btn-prev").disabled = paginaAtual <= 1;
  document.querySelector(".btn-next").disabled = paginaAtual >= totalPaginas;
  document.getElementById("pageInfo").innerText = `Página ${paginaAtual} de ${totalPaginas}`;
}

function montarTabela() {
  const tbody = document.querySelector("#tblHistoricoPonto tbody");
  if (!tbody) return;
  tbody.innerHTML = "";

  const totalPaginas = Math.max(1, Math.ceil(dadosFiltrados.length / itensPorPagina));
  if (paginaAtual > totalPaginas) paginaAtual = totalPaginas;

  const inicio = (paginaAtual - 1) * itensPorPagina;
  const fim = inicio + itensPorPagina;
  const pagina = dadosFiltrados.slice(inicio, fim);

  if (pagina.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-400">Nenhum registro encontrado</td></tr>`;
  } else {
    pagina.forEach(ponto => {
      const s = determinarStatus(ponto);
      const tr = document.createElement("tr");
      tr.className = "cursor-pointer bg-slate-800/50 border-b border-slate-700/30 hover:bg-slate-700/30 transition";
      tr.innerHTML = `
        <td class="px-4 py-3 font-medium text-white">${ponto.DATA ?? "-"}</td>
        <td class="px-4 py-3 text-center text-cyan-300">${ponto.ENTRADA1 ?? "-"}</td>
        <td class="px-4 py-3 text-center text-cyan-300">${ponto.SAIDA1 ?? "-"}</td>
        <td class="px-4 py-3 text-center text-cyan-300 hidden sm:table-cell">${ponto.ENTRADA2 ?? "-"}</td>
        <td class="px-4 py-3 text-center text-cyan-300 hidden sm:table-cell">${ponto.SAIDA2 ?? "-"}</td>
        <td class="px-4 py-3 text-center">
          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border ${s.class}">${s.icon} ${s.label}</span>
        </td>
      `;
      tr.addEventListener("click", () => abrirModalDetalhe(ponto));
      tbody.appendChild(tr);
    });
  }
  atualizarBotoesPagina();
  document.getElementById("tblHistoricoPonto")?.classList.remove("opacity-0", "translate-y-4");
}

// Delegation de clicks
document.addEventListener("click", (e) => {
  const t = e.target;
  if (t.closest(".btn-filtrar")) { e.preventDefault(); aplicarFiltroERecarregar(); return; }
  if (t.closest(".btn-prev")) { e.preventDefault(); if(paginaAtual>1){paginaAtual--; montarTabela();} return; }
  if (t.closest(".btn-next")) { e.preventDefault(); const total = Math.ceil(dadosFiltrados.length/itensPorPagina)||1; if(paginaAtual<total){paginaAtual++; montarTabela();} return; }
  if (t.closest(".btn-pdf")) { e.preventDefault(); exportarPDF(); return; }
  if (t.closest("#fecharModalDetalhe")) { e.preventDefault(); document.getElementById("modalDetalhe")?.classList.add("hidden"); return; }
});

function aplicarFiltroERecarregar() {
  const inicio = document.getElementById("dataInicio")?.value;
  const fim = document.getElementById("dataFim")?.value;
  dadosFiltrados = dadosTabela.filter(p => {
    if (!p || !p.DATA) return false;
    if (inicio && p.DATA < inicio) return false;
    if (fim && p.DATA > fim) return false;
    return true;
  });
  paginaAtual = 1;
  montarTabela();
}

function exportarPDF() {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "pdf/gerarpdf.php";
  form.target = "_blank";
  const input = document.createElement("input");
  input.type = "hidden";
  input.name = "dados";
  input.value = JSON.stringify(dadosFiltrados.length ? dadosFiltrados : dadosTabela);
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
  form.remove();
}

async function getPontos() {
  try {
    const res = await axios.post("/backend/backend.php", { function: "loadPontosFuncionario" });
    dadosTabela = Array.isArray(res.data.pontos) ? res.data.pontos : [];
    dadosFiltrados = [...dadosTabela];
    paginaAtual = 1;
    montarTabela();
  } catch (err) {
    console.error("Erro ao carregar pontos:", err);
    dadosTabela = [];
    dadosFiltrados = [];
    montarTabela();
  }
}

function abrirModalDetalhe(p) {
  const modal = document.getElementById("modalDetalhe");
  const conteudo = document.getElementById("modalConteudo");
  const status = determinarStatus(p);
  conteudo.innerHTML = `
    <div class="flex flex-col gap-3 text-white text-sm">
        <h2 class="text-lg font-bold text-cyan-300 text-center">Detalhes do Registro</h2>
        <div><strong>Data:</strong> ${p.DATA}</div>
        <div><strong>Entrada 1:</strong> ${p.ENTRADA1 ?? "-"}</div>
        <div><strong>Saída 1:</strong> ${p.SAIDA1 ?? "-"}</div>
        <div><strong>Entrada 2:</strong> ${p.ENTRADA2 ?? "-"}</div>
        <div><strong>Saída 2:</strong> ${p.SAIDA2 ?? "-"}</div>
        <div class="mt-2">
            <strong>Status:</strong>
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border ${status.class}">${status.icon} ${status.label}</span>
        </div>
    </div>`;
  modal.classList.remove("hidden");
}

async function init() { await getPontos(); }
</script>

</body>
</html>
