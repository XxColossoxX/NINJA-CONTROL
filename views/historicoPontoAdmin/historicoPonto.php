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

<div class="min-h-screen flex flex-col items-center justify-center px-3 sm:px-4 py-4 sm:py-6">
    <!-- Card principal -->
    <div class="w-full max-w-6xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_25px_#00ffff20] p-4 sm:p-6 md:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">
        
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 sm:gap-6">
            <div class="flex items-center gap-3 sm:gap-4 w-full lg:w-auto">
                <img id="fotoFuncionarioSelecionado" src="/../../assets/img/iconDefault.png" alt="Foto Funcionário" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full border-4 border-cyan-500 object-cover shadow-[0_0_15px_#00ffff50] flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-1">Histórico de Ponto</h1>
                    <p class="text-xs sm:text-sm md:text-base text-gray-300 truncate">Funcionário selecionado: <strong class="text-cyan-400" id="nomeFuncionarioSelecionado">Selecione um funcionário</strong></p>
                </div>
            </div>

            <div class="w-full lg:w-auto">
                <div class="relative w-full max-w-xs ml-auto">
                    <button id="dropdownBtnAdmin" type="button" class="w-full bg-slate-800/80 border border-cyan-400/40 text-white rounded-lg px-4 py-2 flex items-center justify-between shadow-[0_0_12px_#00ffff20] hover:shadow-[0_0_18px_#00ffff50] transition">
                        <span class="flex items-center gap-2 text-sm font-semibold">
                            <i class="fas fa-users text-cyan-300"></i>
                            <span id="textoDropdownAdmin">Selecionar funcionário</span>
                        </span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="dropdownListAdmin" class="absolute left-0 right-0 mt-2 bg-slate-900 border border-cyan-500/20 rounded-xl shadow-xl max-h-60 overflow-y-auto z-20 hidden"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-slate-900/70 border border-cyan-500/20 rounded-xl p-3 sm:p-4">
                <span class="text-xs text-gray-400">Empresa</span>
                <p class="text-sm sm:text-base font-semibold text-white mt-1 truncate"><?php echo htmlspecialchars($nomeEmpresa); ?></p>
            </div>
            <div class="bg-slate-900/70 border border-cyan-500/20 rounded-xl p-3 sm:p-4">
                <span class="text-xs text-gray-400">CPF</span>
                <p class="text-sm sm:text-base font-semibold text-white mt-1 truncate" id="cpfFuncionarioSelecionado">-</p>
            </div>
            <div class="bg-slate-900/70 border border-cyan-500/20 rounded-xl p-3 sm:p-4">
                <span class="text-xs text-gray-400">RG</span>
                <p class="text-sm sm:text-base font-semibold text-white mt-1 truncate" id="rgFuncionarioSelecionado">-</p>
            </div>
            <div class="bg-slate-900/70 border border-cyan-500/20 rounded-xl p-3 sm:p-4">
                <span class="text-xs text-gray-400">Nascimento</span>
                <p class="text-sm sm:text-base font-semibold text-white mt-1 truncate" id="nascimentoFuncionarioSelecionado">-</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full mt-2">
            <div class="flex items-center gap-2 flex-1 sm:flex-initial">
                <input type="date" id="dataInicio" class="flex-1 sm:w-auto min-w-[140px] border border-cyan-500/30 rounded-lg px-3 py-2 bg-slate-800/80 text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition" placeholder="Data início">
                <span class="text-gray-400 text-sm hidden sm:inline">até</span>
            </div>
            <input type="date" id="dataFim" class="flex-1 sm:w-auto min-w-[140px] border border-cyan-500/30 rounded-lg px-3 py-2 bg-slate-800/80 text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition" placeholder="Data fim">
            <button id="btnFiltrar" class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-4 py-2 rounded-lg shadow-[0_0_10px_#00ffff40] transition-all hover:shadow-[0_0_15px_#00ffff60] hover:scale-105 active:scale-95 flex items-center justify-center gap-2 text-sm font-semibold">
                <i class="fas fa-filter"></i> <span>Filtrar</span>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 rounded-xl bg-slate-900/50 border border-cyan-500/10 px-4 py-3">
            <p class="text-xs sm:text-sm text-gray-300">Escolha um funcionário e visualize até 3 registros por vez no mobile. Exportação disponível em PDF.</p>
            <button id="btnExportPDF" class="inline-flex items-center justify-center gap-2 text-sm font-semibold bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-4 py-2 rounded-lg shadow-[0_0_15px_#00ffff30] transition-all hover:shadow-[0_0_20px_#00ffff70]">
                <i class="fas fa-file-pdf"></i>
                <span>Exportar PDF</span>
            </button>
        </div>

        <div class="overflow-x-auto w-full rounded-lg border border-cyan-500/20 shadow-inner history-card">
            <table id="tblHistoricoPonto" class="min-w-full table-auto text-sm text-left">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white font-semibold uppercase text-xs sm:text-sm sticky top-0 z-10">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left">Data</th>
                        <th class="px-3 sm:px-4 py-3 text-center">Entrada</th>
                        <th class="px-3 sm:px-4 py-3 text-center">Saída</th>
                        <th class="px-3 sm:px-4 py-3 text-center hidden sm:table-cell">Entrada (Tarde)</th>
                        <th class="px-3 sm:px-4 py-3 text-center hidden sm:table-cell">Saída (Final)</th>
                        <th class="px-3 sm:px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800/50 divide-y divide-slate-700/50">
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">Selecione um funcionário para visualizar o histórico.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-white">
            <button id="btnPrev" class="w-full sm:w-auto bg-slate-800/70 hover:bg-slate-700/80 border border-cyan-500/30 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Anterior
            </button>
            <span id="pageInfo" class="text-xs sm:text-sm text-gray-300 font-semibold text-center">Página 1 de 1</span>
            <button id="btnNext" class="w-full sm:w-auto bg-slate-800/70 hover:bg-slate-700/80 border border-cyan-500/30 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition">
                Próxima <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <!-- Rodapé -->
        <div class="text-xs text-gray-400 mt-2 sm:mt-4 text-center pt-4 border-t border-slate-700/50">
            Ninja Control &copy; <?php echo date('Y'); ?> - Todos os direitos reservados
        </div>

    </div>
</div>

<div id="modalLinha" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-[60] hidden">
    <div class="w-full max-w-sm bg-slate-900 border border-cyan-500/20 rounded-2xl shadow-xl p-5 relative">
        <button id="btnFecharModal" class="absolute top-3 right-3 text-white/80 hover:text-white">
            <i class="fas fa-times text-lg"></i>
        </button>
        <h4 class="text-center text-white font-semibold text-lg mb-4">Detalhes do registro</h4>
        <div id="modalConteudo" class="space-y-2 text-sm text-gray-200"></div>
    </div>
</div>

<script src="./js/historicoPonto.js"></script>
<link rel="stylesheet" href="./css/historicoPonto.css">
