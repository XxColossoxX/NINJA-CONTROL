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

    
    <!-- Card principal -->
    <div class="w-full max-w-6xl bg-slate-800/80 backdrop-blur-md rounded-2xl shadow-[0_0_25px_#00ffff20] p-6 md:p-10 flex flex-col gap-8">
        
        <!-- Header: Foto e filtro -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-4">
            <div class="flex items-center gap-4">
                <img src="<?php echo $fotoFuncionario; ?>" alt="Foto Funcionário" class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-cyan-500 object-cover shadow-md">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">Histórico de Ponto</h1>
                    <p class="text-sm md:text-base text-gray-300">Funcionário: <strong><?php echo htmlspecialchars($nomeFuncionario); ?></strong></p>
                </div>
            </div>

            <!-- Filtro de datas -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
                <input type="date" class="w-full sm:w-auto border border-gray-600 rounded px-3 py-2 bg-slate-700 text-white placeholder-gray-400 text-sm" placeholder="Data início">
                <span class="text-gray-300 mx-1">até</span>
                <input type="date" class="w-full sm:w-auto border border-gray-600 rounded px-3 py-2 bg-slate-700 text-white placeholder-gray-400 text-sm" placeholder="Data fim">
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2 text-sm">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </div>

        <!-- Tabela de histórico -->
        <div class="overflow-x-auto w-full">
            <table class="min-w-full table-auto text-sm text-left text-white/90">
                <thead class="bg-slate-700 text-white/80 font-semibold uppercase text-xs sm:text-sm">
                    <tr>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3">Entrada</th>
                        <th class="px-4 py-3">Saída</th>
                        <th class="px-4 py-3">Entrada (Tarde)</th>
                        <th class="px-4 py-3">Saída (Final)</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800">
                    <?php
                    $diasFicticios = [
                        ['2025-08-19', '08:02', '12:01', '13:33', '18:01', 'Completo'],
                        ['2025-08-18', '08:00', '12:00', '13:30', '17:45', 'Completo'],
                        ['2025-08-17', '08:03', '12:05', '', '', 'Incompleto'],
                        ['2025-08-16', 'Feriado', '-', '-', '-', 'Não Trabalhado'],
                    ];

                    foreach ($diasFicticios as $dia) {
                        echo '<tr class="border-b border-slate-700 hover:bg-slate-700/50 transition">';
                        foreach ($dia as $i => $item) {
                            $style = ($item === 'Completo') ? 'text-green-400 font-semibold' : (($item === 'Incompleto') ? 'text-yellow-400 font-semibold' : 'text-gray-400');
                            echo "<td class='px-4 py-3 " . ($i === 5 ? $style : '') . "'>$item</td>";
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Rodapé -->
        <div class="text-xs text-gray-400 mt-4 text-center">
            Ninja Control &copy; <?php echo date('Y'); ?> - Todos os direitos reservados
        </div>

    </div>
</div>

<script src="./js/historicoPonto.js"></script>
<link rel="stylesheet" href="./css/historicoPonto.css">
