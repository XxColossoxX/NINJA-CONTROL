<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header('Location: ../loginFuncionario/loginFuncionario.php');
    exit;
}
date_default_timezone_set('America/Sao_Paulo');
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerFuncionario.php');

$idFuncionario = $_SESSION['funcionario_id'];
$nomeFuncionario = isset($_SESSION['funcionario_nome']) ? $_SESSION['funcionario_nome'] : '';
$faceIdFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : '';
$fotoFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : ''; // Exemplo: usar faceId como url da foto
$rgFuncionario = isset($_SESSION['funcionario_rg']) ? $_SESSION['funcionario_rg'] : '';
$cpfFuncionario = isset($_SESSION['funcionario_cpf']) ? $_SESSION['funcionario_cpf'] : '';
$nomeEmpresa = isset($_SESSION['funcionario_nome_empresa']) ? $_SESSION['funcionario_nome_empresa'] : 'Nome da Empresa'; // Trocar para valor real depois
$dataNascimentoFuncionario = isset($_SESSION['funcionario_data_nascimento']) ? $_SESSION['funcionario_data_nascimento'] : 'Data de Nascimento'; // Trocar para valor real depois

$socialEmpresa = isset($_SESSION['empresa_razao_social']) ? $_SESSION['empresa_razao_social'] : 'Razão Social da Empresa'; 
$fantasiaEmpresa = isset($_SESSION['empresa_razao_fantasia']) ? $_SESSION['empresa_razao_fantasia'] : 'Razão Fantasia da Empresa'; 
$cnpjEmpresa = isset($_SESSION['empresa_cnpj']) ? $_SESSION['empresa_cnpj'] : 'CNPJ da Empresa'; 
$locEmpresa = isset($_SESSION['empresa_loc']) ? $_SESSION['empresa_loc'] : 'Localização da Empresa'; 
$dscEmpresa = isset($_SESSION['empresa_dsc']) ? $_SESSION['empresa_dsc'] : 'Descrição da Empresa'; 
$telEmpresa = isset($_SESSION['empresa_tel']) ? $_SESSION['empresa_tel'] : 'Telefone da Empresa'; 
$emailEmpresa = isset($_SESSION['empresa_email']) ? $_SESSION['empresa_email'] : 'Email da Empresa'; 


?>

<div class="min-h-screen flex flex-row items-center justify-center">
    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl p-6 mt-6 flex flex-col gap-6">
            <!-- Título e informações básicas -->
            <div class="w-full max-w-5xl bg-white shadow-xl rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex items-center gap-4">
                        <img src="<?php echo $fotoFuncionario; ?>" alt="Foto" class="w-16 h-16 rounded-full border-4 border-primary object-cover">
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">Histórico de Ponto</h1>
                            <p class="text-sm text-gray-500">Funcionário: <strong><?php echo htmlspecialchars($nomeFuncionario); ?></strong></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-4 md:mt-0">
                        <input type="date" class="border rounded px-3 py-2 text-sm text-gray-700" placeholder="Data início">
                        <span class="mx-1">até</span>
                        <input type="date" class="border rounded px-3 py-2 text-sm text-gray-700" placeholder="Data fim">
                        <button class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 text-sm ml-2">
                            <i class="fas fa-filter mr-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabela de histórico -->
            <div class="w-full max-w-5xl bg-white shadow-xl rounded-xl overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Entrada</th>
                            <th class="px-4 py-3">Saída</th>
                            <th class="px-4 py-3">Entrada (Tarde)</th>
                            <th class="px-4 py-3">Saída (Final)</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $diasFicticios = [
                            ['2025-08-19', '08:02', '12:01', '13:33', '18:01', 'Completo'],
                            ['2025-08-18', '08:00', '12:00', '13:30', '17:45', 'Completo'],
                            ['2025-08-17', '08:03', '12:05', '', '', 'Incompleto'],
                            ['2025-08-16', 'Feriado', '-', '-', '-', 'Não Trabalhado'],
                        ];

                        foreach ($diasFicticios as $dia) {
                            echo '<tr class="border-b hover:bg-gray-50">';
                            foreach ($dia as $i => $item) {
                                $style = ($item === 'Completo') ? 'text-green-600 font-semibold' : (($item === 'Incompleto') ? 'text-yellow-600 font-semibold' : 'text-gray-400');
                                echo "<td class='px-4 py-3 " . ($i === 5 ? $style : '') . "'>$item</td>";
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="text-xs text-gray-400 mt-4 text-center">
                Ninja Control &copy; <?php echo date('Y'); ?> - Todos os direitos reservados
            </div>
        </div>
    </div>
</div>

<script src="./js/historicoPonto.js"></script>
<link rel="stylesheet" href="./css/historicoPonto.css">
</body>
</html>
