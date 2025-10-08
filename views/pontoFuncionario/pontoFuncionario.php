<?php
session_start();

$idFuncionario = $_SESSION['funcionario_id'];
$nomeFuncionario = isset($_SESSION['funcionario_nome']) ? $_SESSION['funcionario_nome'] : '';
$faceIdFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : '';
$fotoFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : ''; // Exemplo: usar faceId como url da foto
$rgFuncionario = isset($_SESSION['funcionario_rg']) ? $_SESSION['funcionario_rg'] : '';
$cpfFuncionario = isset($_SESSION['funcionario_cpf']) ? $_SESSION['funcionario_cpf'] : '';
$nomeEmpresa = isset($_SESSION['empresa_razao_fantasia']) ? $_SESSION['empresa_razao_fantasia'] : 'Nome da Empresa'; // Trocar para valor real depois
$dataNascimentoFuncionario = isset($_SESSION['funcionario_data_nascimento']) ? $_SESSION['funcionario_data_nascimento'] : 'Data de Nascimento'; // Trocar para valor real depois
$localizacaoEmpresa = isset($_SESSION['empresa_loc']) ? $_SESSION['empresa_loc'] : '';

if (!isset($_SESSION['funcionario_nome'])) {
    header('Location: ../loginFuncionario/loginFuncionario.php');
    exit;
}

if (!isset($_SESSION['funcionario_id'])) {
    header('Location: ../loginFuncionario/loginFuncionario.php');
    exit;
}
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerFuncionario.php');

?>

<div class="min-h-screen flex flex-col items-center justify-center px-4">
    <div class="w-full max-w-6xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_20px_#00ffff20] p-0 mt-4 flex flex-col md:flex-row">

        <!-- Header do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-start md:p-4 p-2 border-b md:border-b-0 md:border-r border-cyan-400">

            <!-- Header mobile -->
            <div class="md:hidden flex items-center justify-center gap-2 mb-2 pl-1">
                <img id="imgFuncionario" 
                    src="<?php echo $fotoFuncionario; ?>" 
                    alt="Foto do Funcionário"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-cyan-500 ml-1">
                
                <span class="font-bold text-sm text-white flex items-center gap-2 text-center">
                    SEJA MUITO BEM VINDO!<br><?php echo htmlspecialchars($nomeFuncionario); ?>
                </span>
            </div>

            <!-- Header desktop -->
            <div class="hidden md:block relative text-center mt-4 mb-10">
                <h2 class="text-xl font-bold text-white">
                    SEJA MUITO BEM VINDO!<br><?php echo htmlspecialchars($nomeFuncionario); ?>
                </h2>
            </div>

            <!-- Modal mobile estilizado -->
            <div id="modal-dados-funcionario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden px-4">
                <div class="w-full max-w-lg bg-[#0d1628]/95 backdrop-blur-md border border-cyan-500/30 rounded-2xl shadow-[0_0_25px_#00ffff30] p-6 relative">
                    <h3 class="text-xl font-bold text-center text-white mb-6">Dados do Funcionário</h3>

                    <div class="flex flex-col gap-4 text-sm">
                        <!-- Nome -->
                        <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                            <span class="text-cyan-400 font-semibold flex items-center gap-2">
                                <i class="fas fa-user"></i> Nome:
                            </span>
                            <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($nomeFuncionario); ?></span>
                        </div>

                        <!-- Empresa -->
                        <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                            <span class="text-cyan-400 font-semibold flex items-center gap-2">
                                <i class="fas fa-building"></i> Empresa:
                            </span>
                            <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($nomeEmpresa); ?></span>
                        </div>

                        <!-- RG -->
                        <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                            <span class="text-cyan-400 font-semibold flex items-center gap-2">
                                <i class="fas fa-id-card"></i> RG:
                            </span>
                            <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($rgFuncionario); ?></span>
                        </div>

                        <!-- CPF -->
                        <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                            <span class="text-cyan-400 font-semibold flex items-center gap-2">
                                <i class="fas fa-address-card"></i> CPF:
                            </span>
                            <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($cpfFuncionario); ?></span>
                        </div>

                        <!-- Nascimento -->
                        <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                            <span class="text-cyan-400 font-semibold flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i> Nascimento:
                            </span>
                            <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($dataNascimentoFuncionario); ?></span>
                        </div>
                    </div>

                    <!-- Botão fechar -->
                    <button id="btn-fechar-modal-mobile" class="mt-6 w-full bg-cyan-500/90 text-white font-bold py-3 rounded-lg shadow-[0_0_20px_#00ffff40] transition flex items-center justify-center gap-2 hover:bg-cyan-400">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                </div>
            </div>


            <div class="flex flex-col items-center">
                <img src="<?php echo $fotoFuncionario; ?>" alt="Foto do Funcionário"
                     class="w-24 h-24 rounded-full border-4 border-cyan-400 object-cover mb-4 hidden md:block">

                <div class="hidden md:block bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_20px_#00ffff20] md:p-6 p-3 w-full mb-0 mt-5" id="card-dados-funcionario">
                    <div class="flex flex-col gap-2">
                        <!-- Nome -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-user mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">Nome:</span>
                            </div>
                            <span class="text-sm font-bold text-white truncate min-w-[120px] max-w-[180px]"><?php echo htmlspecialchars($nomeFuncionario); ?></span>
                        </div>

                        <!-- Empresa -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-building mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">Empresa:</span>
                            </div>
                            <span class="text-sm font-bold text-white truncate min-w-[120px] max-w-[180px]"><?php echo htmlspecialchars($nomeEmpresa); ?></span>
                        </div>

                        <!-- RG -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-id-card mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">RG:</span>
                            </div>
                            <span class="text-sm text-white truncate min-w-[120px] max-w-[180px]"><?php echo htmlspecialchars($rgFuncionario); ?></span>
                        </div>

                        <!-- CPF -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-address-card mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">CPF:</span>
                            </div>
                            <span class="text-sm text-white truncate min-w-[120px] max-w-[180px]"><?php echo htmlspecialchars($cpfFuncionario); ?></span>
                        </div>

                        <!-- Nascimento -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-calendar-alt mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">Nascimento:</span>
                            </div>
                            <span class="text-sm text-white truncate min-w-[120px] max-w-[180px]"><?php echo htmlspecialchars($dataNascimentoFuncionario); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Botão mobile -->
                <button class="md:hidden bg-cyan-500/90 text-white font-bold px-3 py-2 rounded-lg mt-4 shadow-[0_0_15px_#00ffff40] transition flex items-center gap-2" id="btn-mais-info">
                    <i class="fas fa-info-circle"></i> Mais informações
                </button>
            </div>
        </div>

        <!-- Conteúdo do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-center md:p-4 p-2">

            <!-- Título -->
            <div class="mb-6">
                <h2 class="text-transparent bg-clip-text text-center bg-gradient-to-r from-cyan-400 to-blue-500 text-3xl font-extrabold hidden md:block">
                    PAINEL REGISTRO PONTO
                </h2>
            </div>

            <!-- Últimos registros -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-white mb-3">Últimos registros</h3>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-in-alt text-green-600 text-lg sm:text-xl mb-1"></i>
                        <span class="font-bold text-green-600 text-sm sm:text-base">08:00</span>
                        <span class="text-xs font-bold text-white">Entrada</span>
                        <span class="text-xs text-green-500">Concluído</span>
                    </div>
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-out-alt text-red-600 text-lg sm:text-xl mb-1"></i>
                        <span class="font-bold text-red-600 text-sm sm:text-base">12:00</span>
                        <span class="text-xs font-bold text-white">Saída</span>
                        <span class="text-xs text-red-500">Concluído</span>
                    </div>
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-in-alt text-green-600 text-lg sm:text-xl mb-1"></i>
                        <span class="font-bold text-green-600 text-sm sm:text-base">13:30</span>
                        <span class="text-xs font-bold text-white">Entrada</span>
                        <span class="text-xs text-yellow-500">Pendente</span>
                    </div>
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-out-alt text-red-600 text-lg sm:text-xl mb-1"></i>
                        <span class="font-bold text-red-600 text-sm sm:text-base">18:00</span>
                        <span class="text-xs font-bold text-white">Saída</span>
                        <span class="text-xs text-yellow-500">Pendente</span>
                    </div>
                </div>
            </div>

            <!-- Localização -->
            <div class="mb-4">
                <label class="block text-xs font-bold mb-1 text-white" for="localizacao">Localização Atual</label>
                <div class="bg-slate-900 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] flex items-center bg-slate-900 rounded-lg p-1.5 glow-box">
                    <i class="fas fa-map-marker-alt text-cyan-400 text-lg mr-1.5"></i>
                    <input id="inputLocalizacao" type="text" value=""
                        class="w-full min-w-[250px] bg-slate-900 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] border-none outline-none font-bold text-white text-xs" readonly>
                </div>
            </div>

            <!-- Botão bater ponto -->
            <div class="flex justify-center mt-4 mb-3">
                <button id="btnBaterPonto" 
                    class="relative bg-cyan-500/90 text-slate-900 font-bold py-3 px-6 rounded-lg text-base
                        shadow-[0_0_15px_#00ffff40] transition-all 
                        md:hover:bg-cyan-400 md:hover:shadow-[0_0_25px_#00ffff80] md:hover:scale-105
                        flex items-center gap-3 text-white">
                    <img src="/../../assets/img/faceidIcon.png" class="w-7 h-7 icon-color" alt="Ícone FaceID" />
                    <span class="text-white">BATER PONTO</span>
                </button>
            </div>

            <!-- Rodapé -->
            <div class="text-xs text-gray-400 text-center select-none">
                Ninja Control - Direitos Reservados
            </div>

            <div id="welcome-message" class="mt-6 text-green-500 font-bold hidden">
                Bem-vindo,
            </div>

            <!-- Alerta flutuante -->
            <div id="alert-box" class="hidden fixed top-12 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-md text-white bg-green-500 shadow-lg z-50">
                <span id="alert-message"></span>
            </div>

        </div> <!-- Fim da coluna direita -->
    </div> <!-- Fim do container principal -->
</div> <!-- Fim do wrapper geral -->


<script src="./js/pontoFuncionario.js"></script>
<link rel="stylesheet" href="./css/pontoFuncionario.css">
<script>
    
    const faceIdFuncionario         = "<?php echo isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : ''; ?>";
    const nomeFuncionario           = "<?php echo isset($_SESSION['funcionario_nome']) ? $_SESSION['funcionario_nome'] : ''; ?>";
    const cpfFuncionario            = "<?php echo isset($_SESSION['funcionario_cpf']) ? $_SESSION['funcionario_cpf'] : ''; ?>";
    const rgFuncionario             = "<?php echo isset($_SESSION['funcionario_rg']) ? $_SESSION['funcionario_rg'] : ''; ?>";
    const dataNascimentoFuncionario = "<?php echo isset($_SESSION['funcionario_data_nascimento']) ? $_SESSION['funcionario_data_nascimento'] : ''; ?>";
    const localizacaoEmpresa        = "<?php echo $localizacaoEmpresa; ?>";

    const idFuncionario = "<?php echo isset($_SESSION['funcionario_id']) ? $_SESSION['funcionario_id'] : ''; ?>"
    const nomeEmpresa = "<?php echo isset($_SESSION['empresa_razao_fantasia']) ? $_SESSION['empresa_razao_fantasia'] : ''; ?>"
    sessionStorage.setItem("idFuncionario",idFuncionario);
    sessionStorage.setItem("fotoFuncionario",faceIdFuncionario);
    sessionStorage.setItem("nomeEmpresa",nomeEmpresa);
    
    let divBemVindo = $("#bemVindo");
    let conteudo = `
        <div id="welcome-message" class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="rounded-xl p-6 max-w-md w-full text-center animate-fade-in-up">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">
                    BEM-VINDO(A) DE VOLTA
                </h1>
                <h2 id="tituloEmpresa" class="text-xl sm:text-2xl font-medium text-white line-clamp-2">
                    ${nomeFuncionario}
                </h2>
            </div>
        </div>
    `;

    divBemVindo.append(conteudo);

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
