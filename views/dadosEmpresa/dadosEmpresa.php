<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header('Location: ../loginFuncionario/loginFuncionario.php');
    exit;
}
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerFuncionario.php');

$idFuncionario = $_SESSION['funcionario_id'];
$nomeFuncionario = isset($_SESSION['funcionario_nome']) ? $_SESSION['funcionario_nome'] : '';
$faceIdFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : '';
$fotoFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : '';
$rgFuncionario = isset($_SESSION['funcionario_rg']) ? $_SESSION['funcionario_rg'] : '';
$cpfFuncionario = isset($_SESSION['funcionario_cpf']) ? $_SESSION['funcionario_cpf'] : '';
$dataNascimentoFuncionario = isset($_SESSION['funcionario_data_nascimento']) ? $_SESSION['funcionario_data_nascimento'] : 'Data de Nascimento'; 

$socialEmpresa = isset($_SESSION['empresa_razao_social']) ? $_SESSION['empresa_razao_social'] : 'Razão Social da Empresa'; 
$fantasiaEmpresa = isset($_SESSION['empresa_razao_fantasia']) ? $_SESSION['empresa_razao_fantasia'] : 'Razão Fantasia da Empresa'; 
$cnpjEmpresa = isset($_SESSION['empresa_cnpj']) ? $_SESSION['empresa_cnpj'] : 'CNPJ da Empresa'; 
$locEmpresa = isset($_SESSION['empresa_loc']) ? $_SESSION['empresa_loc'] : 'Localização da Empresa'; 
$dscEmpresa = isset($_SESSION['empresa_dsc']) ? $_SESSION['empresa_dsc'] : 'Descrição da Empresa'; 
$telEmpresa = isset($_SESSION['empresa_tel']) ? $_SESSION['empresa_tel'] : 'Telefone da Empresa'; 
$emailEmpresa = isset($_SESSION['empresa_email']) ? $_SESSION['empresa_email'] : 'Email da Empresa'; 

?>

<!-- Modal Empresa (como tela principal) -->
<div class="min-h-screen flex flex-col items-center justify-center w-full px-4">
    <div class="modalDadosEmpresa w-full max-w-4xl glow-box bg-slate-900/90 backdrop-blur-md rounded-2xl shadow-[0_0_25px_#00ffff20] p-6 md:p-10 flex flex-col md:flex-row gap-6 md:gap-10">
        
        <!-- Coluna Esquerda: Informações -->
        <div class="flex-1">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-white mb-4 md:mb-6 flex items-center gap-2 md:gap-3">
                <i class="fas fa-building text-cyan-500 text-base sm:text-lg md:text-xl"></i>
                Informações da Empresa
            </h2>

            <ul class="space-y-3 text-white text-[11px] sm:text-xs md:text-sm">
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-id-badge text-cyan-500"></i>
                    <span><strong>Nome:</strong> <?php echo $socialEmpresa; ?></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-id-card text-cyan-500"></i>
                    <span><strong>CNPJ:</strong> <?php echo $cnpjEmpresa; ?></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-map-marker-alt text-cyan-500"></i>
                    <span><strong>Endereço:</strong> <?php echo $locEmpresa; ?></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-phone-alt text-cyan-500"></i>
                    <span><strong>Telefone:</strong> <?php echo $telEmpresa; ?></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-envelope text-cyan-500"></i>
                    <span><strong>Email:</strong> <?php echo $emailEmpresa; ?></span>
                </li>
            </ul>
        </div>

        <!-- Coluna Direita: Sobre e Ações -->
        <div class="flex-1 flex flex-col justify-between">
            <div class="bg-slate-800/80 border border-cyan-500/20 shadow-[0_0_12px_#00ffff20] rounded-xl p-4 md:p-6 w-full mb-4 md:mb-6">
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-info-circle text-cyan-500"></i>
                    <span class="text-white font-bold">Sobre a Empresa</span>
                </h3>
                <p class="text-[10px] sm:text-xs md:text-sm leading-relaxed text-white">
                    <?php echo $dscEmpresa; ?>
                </p>
            </div>
    </div>
</div>


<script src="./js/dadosEmpresa.js"></script>
<link rel="stylesheet" href="./css/dadosEmpresa.css">
<script>
    const faceIdFuncionario = "<?php echo isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : ''; ?>";
    const nomeFuncionario   = "<?php echo isset($_SESSION['funcionario_nome']) ? $_SESSION['funcionario_nome'] : ''; ?>";
    const cpfFuncionario    = "<?php echo isset($_SESSION['funcionario_cpf']) ? $_SESSION['funcionario_cpf'] : ''; ?>";
    const rgFuncionario     = "<?php echo isset($_SESSION['funcionario_rg']) ? $_SESSION['funcionario_rg'] : ''; ?>";
    const nomeEmpresa       = "<?php echo isset($_SESSION['funcionario_nome_empresa']) ? $_SESSION['funcionario_nome_empresa'] : ''; ?>";
    const dataNascimentoFuncionario = "<?php echo isset($_SESSION['funcionario_data_nascimento']) ? $_SESSION['funcionario_data_nascimento'] : ''; ?>";
    const localizacaoEmpresa = "<?php echo $localizacaoEmpresa; ?>";

    console.log("Face ID do Funcionário:", faceIdFuncionario);
    console.log("Nome do Funcionário:", nomeFuncionario);
    console.log("CPF do Funcionário:", cpfFuncionario);
    console.log("RG do Funcionário:", rgFuncionario);
    console.log("Nome da Empresa:", nomeEmpresa);
    console.log("Data de Nascimento do Funcionário:", dataNascimentoFuncionario);
    console.log("Localização da Empresa:", localizacaoEmpresa);

    $(document).ready(function() {
        $('#btn-mais-info').on('click', function() {
            $('#modal-dados-funcionario').removeClass('hidden');
        });
        $('#btn-fechar-modal, #btn-fechar-modal-mobile').on('click', function() {
            $('#modal-dados-funcionario').addClass('hidden');
        });
        $('#btn-menu-mobile').on('click', function(e) {
            e.stopPropagation();
            $('#menu-mobile').toggleClass('hidden');
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#menu-mobile, #btn-menu-mobile').length) {
                $('#menu-mobile').addClass('hidden');
            }
        });
    });
</script>
</body>
</html>