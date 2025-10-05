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

    <div class="min-h-screen flex flex-col items-center justify-center">
        <!-- Modal Empresa (como tela principal) -->
        <div class="modalDadosEmpresa w-full max-w-6xl bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-10 md:p-14 flex flex-col md:flex-row gap-10">
            
            <!-- Coluna Esquerda -->
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-teal-700 mb-6 flex items-center gap-3">
                    <i class="fas fa-building text-teal-600 text-xl"></i>
                    Informações da Empresa
                </h2>

                <ul class="space-y-4 text-gray-800 text-sm md:text-base">
                    <li class="flex items-center gap-3">
                        <i class="fas fa-id-badge text-teal-500"></i>
                        <span><strong>Nome:</strong> <?php echo $socialEmpresa; ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-id-card text-teal-500"></i>
                        <span><strong>CNPJ:</strong> <?php echo $cnpjEmpresa; ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-teal-500"></i>
                        <span><strong>Endereço:</strong> <?php echo $locEmpresa; ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-teal-500"></i>
                        <span><strong>Telefone:</strong> <?php echo $telEmpresa; ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-teal-500"></i>
                        <span><strong>Email:</strong> <?php echo $emailEmpresa; ?></span>
                    </li>
                </ul>
            </div>

            <!-- Coluna Direita -->
            <div class="flex-1 flex items-center">
                <div class="bg-teal-600 text-white p-6 md:p-8 rounded-xl shadow-md w-full">
                    <h3 class="text-lg md:text-xl font-semibold mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Sobre a Empresa
                    </h3>
                    <p class="text-sm md:text-base leading-relaxed">
                        <?php echo $dscEmpresa; ?>
                    </p>
                </div>
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


    
    let divBemVindo = $("#bemVindo");
    let conteudo = `
    <div id="welcome-message" class="fixed inset-0 flex items-center justify-center z-50 text-center">
        <div>
            <h1 class="text-2xl text-white font-sans"><i><strong>BEM-VINDO(A) DE VOLTA</strong></i></h1>
            <h2 id="tituloEmpresa" class="text-lg text-white font-sans underline text-center"><i><strong>${nomeFuncionario}</strong></i></h2>
        </div>
    </div>`;
    divBemVindo.append(conteudo);

    // Animação de entrada
    setTimeout(() => {
        document.getElementById("welcome-message").classList.add("entrada");
    }, 100);
    setTimeout(() => {
        const el = document.getElementById("welcome-message");
        el.classList.remove("entrada");
        el.classList.add("saida");
        setTimeout(() => {
            el.remove();
            document.getElementById("controlador")?.classList.remove("hidden");
        }, 500);
    }, 1500);

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