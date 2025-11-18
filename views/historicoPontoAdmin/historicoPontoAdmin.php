<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    header('Location: ../loginEmpresa/loginEmpresa.php');
    exit;
}
date_default_timezone_set('America/Sao_Paulo');
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerEmpresa.php');

$idEmpresa = $_SESSION['empresa_id'] ?? '';
$nomeEmpresa = $_SESSION['empresa_nome'] ?? '';
$fotoEmpresa = $_SESSION['empresa_faceid'] ?? '';
$rgEmpresa = $_SESSION['empresa_rg'] ?? '';
$cpfEmpresa = $_SESSION['empresa_cpf'] ?? '';
$nomeEmpresa = $_SESSION['empresa_nome_empresa'] ?? 'Nome da Empresa';
?>

<div class="min-h-screen flex flex-col items-center justify-center px-3 sm:px-4 py-4 sm:py-6">
    <!-- Card principal -->
    <div class="w-full max-w-6xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_25px_#00ffff20] p-4 sm:p-6 md:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">
        
        <!-- Header: Foto e filtro -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6">
            <div class="flex items-center gap-3 sm:gap-4 w-full md:w-auto">
                <img src="<?php echo $fotoEmpresa; ?>" alt="Foto Funcionário" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full border-4 border-cyan-500 object-cover shadow-[0_0_15px_#00ffff50] flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-1">Histórico de Ponto</h1>
                    <p class="text-xs sm:text-sm md:text-base text-gray-300 truncate">Funcionário: <strong class="text-cyan-400"><?php echo htmlspecialchars($nomeEmpresa); ?></strong></p>
                </div>
            </div>

            <!-- Filtro de datas -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2 flex-1 sm:flex-initial">
                    <input type="date" id="dataInicio" class="flex-1 sm:w-auto min-w-[140px] border border-cyan-500/30 rounded-lg px-3 py-2 bg-slate-800/80 text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition" placeholder="Data início">
                    <span class="text-gray-400 text-sm hidden sm:inline">até</span>
                </div>
                <input type="date" id="dataFim" class="flex-1 sm:w-auto min-w-[140px] border border-cyan-500/30 rounded-lg px-3 py-2 bg-slate-800/80 text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500 transition" placeholder="Data fim">
                <button class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-4 py-2 rounded-lg shadow-[0_0_10px_#00ffff40] transition-all hover:shadow-[0_0_15px_#00ffff60] hover:scale-105 active:scale-95 flex items-center justify-center gap-2 text-sm font-semibold">
                    <i class="fas fa-filter"></i> <span>Filtrar</span>
                </button>
            </div>
        </div>

        <!-- Tabela de histórico -->
        <div class="overflow-x-auto w-full rounded-lg border border-cyan-500/20 shadow-inner">
            <table class="min-w-full table-auto text-sm text-left">
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
                    <?php
                    $diasFicticios = [
                        ['2025-08-19', '08:02', '12:01', '13:33', '18:01', 'Completo'],
                        ['2025-08-18', '08:00', '12:00', '13:30', '17:45', 'Completo'],
                        ['2025-08-17', '08:03', '12:05', '', '', 'Incompleto'],
                        ['2025-08-16', 'Feriado', '-', '-', '-', 'Não Trabalhado'],
                    ];

                    foreach ($diasFicticios as $dia) {
                        $status = $dia[5];
                        $statusClass = '';
                        $statusIcon = '';
                        if ($status === 'Completo') {
                            $statusClass = 'bg-green-500/20 text-green-400 border-green-500/50';
                            $statusIcon = 'fa-check-circle';
                        } elseif ($status === 'Incompleto') {
                            $statusClass = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50';
                            $statusIcon = 'fa-exclamation-circle';
                        } else {
                            $statusClass = 'bg-gray-500/20 text-gray-400 border-gray-500/50';
                            $statusIcon = 'fa-times-circle';
                        }
                        
                        echo '<tr class="border-b border-slate-700/30 hover:bg-slate-700/30 transition-colors duration-200">';
                        $dataFormatada = ($dia[0] === 'Feriado') ? 'Feriado' : date('d/m/Y', strtotime($dia[0]));
                        echo "<td class='px-3 sm:px-4 py-3 sm:py-4 text-white font-medium whitespace-nowrap'>" . $dataFormatada . "</td>";
                        echo "<td class='px-3 sm:px-4 py-3 sm:py-4 text-center text-cyan-300 font-semibold'>" . ($dia[1] !== 'Feriado' ? $dia[1] : '<span class="text-gray-500">-</span>') . "</td>";
                        echo "<td class='px-3 sm:px-4 py-3 sm:py-4 text-center text-cyan-300 font-semibold'>" . ($dia[2] !== '-' ? $dia[2] : '<span class="text-gray-500">-</span>') . "</td>";
                        echo "<td class='px-3 sm:px-4 py-3 sm:py-4 text-center text-cyan-300 font-semibold hidden sm:table-cell'>" . ($dia[3] ? $dia[3] : '<span class="text-gray-500">-</span>') . "</td>";
                        echo "<td class='px-3 sm:px-4 py-3 sm:py-4 text-center text-cyan-300 font-semibold hidden sm:table-cell'>" . ($dia[4] ? $dia[4] : '<span class="text-gray-500">-</span>') . "</td>";
                        echo "<td class='px-3 sm:px-4 py-3 sm:py-4 text-center'><span class='inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border $statusClass'><i class='fas $statusIcon'></i> $status</span></td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Rodapé -->
        <div class="text-xs text-gray-400 mt-2 sm:mt-4 text-center pt-4 border-t border-slate-700/50">
            Ninja Control &copy; <?php echo date('Y'); ?> - Todos os direitos reservados
        </div>

    </div>
</div>

<script src="./js/historicoPonto.js"></script>
<link rel="stylesheet" href="./css/historicoPonto.css">
