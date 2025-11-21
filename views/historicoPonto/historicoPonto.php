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

    <div class="w-full max-w-6xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_25px_#00ffff20] p-4 sm:p-6 md:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6">

            <div class="flex items-center gap-3 sm:gap-4 w-full md:w-auto">
                <img src="<?php echo $fotoFuncionario; ?>" 
                     class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full border-4 border-cyan-500 object-cover shadow-[0_0_15px_#00ffff50] flex-shrink-0">

                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-1">
                        Histórico de Ponto
                    </h1>
                    <p class="text-xs sm:text-sm md:text-base text-gray-300 truncate">
                        Funcionário: 
                        <strong class="text-cyan-400">
                            <?= htmlspecialchars($nomeFuncionario); ?>
                        </strong>
                    </p>
                </div>
            </div>

            <!-- FILTRO -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2 flex-1 sm:flex-initial">
                    <input type="date" id="dataInicio"
                        class="flex-1 sm:w-auto min-w-[140px] border border-cyan-500/30 rounded-lg px-3 py-2 bg-slate-800/80 text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition">
                    <span class="text-gray-400 text-sm hidden sm:inline">até</span>
                </div>

                <input type="date" id="dataFim"
                    class="flex-1 sm:w-auto min-w-[140px] border border-cyan-500/30 rounded-lg px-3 py-2 bg-slate-800/80 text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition">

                <button id="btnFiltrar"
                    class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-4 py-2 rounded-lg shadow-[0_0_10px_#00ffff40] transition-all hover:shadow-[0_0_15px_#00ffff60] hover:scale-105 active:scale-95 flex items-center justify-center gap-2 text-sm font-semibold">
                    <i class="fas fa-filter"></i> <span>Filtrar</span>
                </button>
            </div>

        </div>

        <!-- CONTEÚDO -->
        <div class="w-full flex flex-col gap-4">

            <div class="flex gap-3">
                <button id="btnExportCSV" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 rounded-lg text-white font-semibold transition">
                    Exportar CSV
                </button>

                <button id="btnExportPDF" class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white font-semibold transition">
                    Exportar PDF
                </button>
            </div>

            <div class="overflow-x-auto">
                <table id="tblHistoricoPonto"
                    class="w-full text-sm text-left border border-slate-700 rounded-lg overflow-hidden opacity-0 translate-y-4 transition-all duration-500">
                    <thead class="bg-slate-800 text-cyan-300">
                        <tr>
                            <th class="px-4 py-3 text-center text-white">DATA</th>
                            <th class="px-4 py-3 text-center text-white">ENTRADA 1</th>
                            <th class="px-4 py-3 text-center text-white">SAÍDA 1</th>
                            <th class="px-4 py-3 text-center text-white hidden sm:table-cell">ENTRADA 2</th>
                            <th class="px-4 py-3 text-center text-white hidden sm:table-cell">SAÍDA 2</th>
                            <th class="px-4 py-3 text-center text-white">STATUS</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="flex justify-center items-center gap-4 mt-2">
                <button id="btnPrev" class="px-3 py-1 bg-slate-700 text-white rounded hover:bg-slate-600">Anterior</button>
                <span id="pageInfo" class="text-white"></span>
                <button id="btnNext" class="px-3 py-1 bg-slate-700 text-white rounded hover:bg-slate-600">Próximo</button>
            </div>
        </div>

        <div class="text-xs text-gray-400 mt-2 sm:mt-4 text-center pt-4 border-t border-slate-700/50">
            Ninja Control &copy; <?= date('Y'); ?> - Todos os direitos reservados
        </div>

    </div>
</div>

<script src="./js/historicoPonto.js"></script>
<link rel="stylesheet" href="./css/historicoPonto.css">
