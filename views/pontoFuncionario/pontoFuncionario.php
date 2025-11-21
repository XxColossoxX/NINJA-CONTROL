<?php
session_start();

$idFuncionario = $_SESSION['funcionario_id'];
$nomeFuncionario = isset($_SESSION['funcionario_nome']) ? $_SESSION['funcionario_nome'] : '';
$faceIdFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : '';
$fotoFuncionario = isset($_SESSION['funcionario_faceid']) ? $_SESSION['funcionario_faceid'] : ''; //Exemplo: usar faceId como url da foto
$rgFuncionario = isset($_SESSION['funcionario_rg']) ? $_SESSION['funcionario_rg'] : '';
$cpfFuncionario = isset($_SESSION['funcionario_cpf']) ? $_SESSION['funcionario_cpf'] : '';
$nomeEmpresa = isset($_SESSION['empresa_razao_fantasia']) ? $_SESSION['empresa_razao_fantasia'] : 'Nome da Empresa'; // Trocar para valor real depois
$dataNascimentoFuncionario = isset($_SESSION['funcionario_data_nascimento']) ? $_SESSION['funcionario_data_nascimento'] : 'Data de Nascimento'; // Trocar para valor real depois
$localizacaoEmpresa = isset($_SESSION['empresa_loc']) ? $_SESSION['empresa_loc'] : '';
$horarioEntrada1 = isset($_SESSION['entrada1']) ? $_SESSION['entrada1'] : '-'; 
$horarioSaida1 = isset($_SESSION['saida1']) ? $_SESSION['saida1'] : '-';
$horarioSaida2 = isset($_SESSION['saida2']) ? $_SESSION['saida2'] : '-';
$horarioIntEntrada2 = isset($_SESSION['entrada2']) ? $_SESSION['entrada2'] : '-';

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

<div id="controlador2" class="min-h-screen flex flex-col items-center justify-center px-3 sm:px-4 py-4 sm:py-6 hidden">
    <div class="w-full max-w-6xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_20px_#00ffff20] p-0 mt-2 sm:mt-4 flex flex-col md:flex-row">

        <!-- Header do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-start md:p-4 p-2 border-b md:border-b-0 md:border-r border-cyan-400">

        <!-- Header mobile -->
        <div class="md:hidden flex items-center justify-center gap-2 mb-2 pl-1">
            <img id="imgFuncionario" 
                src="<?php echo $fotoFuncionario; ?>" 
                alt="Foto do Funcionário"
                class="w-8 h-8 rounded-full object-cover border-2 border-cyan-500 ml-1">
            
            <span class="font-bold text-sm text-white flex items-center gap-2">
                SEJA MUITO BEM VINDO!
                <img id="ninja-img" 
                    class="w-8" 
                    src="../../assets/img/ninjaLogo.png" 
                    alt="Ninja Control" />
            </span>
        </div>


            <!-- Header desktop -->
            <div class="hidden md:block relative text-center mt-4 mb-10">
                <h2 class="text-xl font-bold text-white">
                    SEJA MUITO BEM VINDO!
                </h2>
            </div>

            <!-- Modal mobile estilizado -->
            <div id="modal-dados-funcionario" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                <div class="w-[85%] max-w-md bg-[#101a2b]/95 md:border md:border-cyan-500/20 text-slate-100 rounded-2xl shadow-[0_0_25px_#00ffff20] px-4 py-4 sm:px-6 sm:py-4 backdrop-blur-xl relative max-h-[85vh] overflow-y-auto mt-16 sm:mt-20">
                    
                    <!-- Botão Fechar -->
                    <button id="btn-fechar-modal-mobile" class="absolute top-3 right-3 z-10 text-white/80 hover:text-white" aria-label="Fechar">
                        <i class="fas fa-times text-xl"></i>
                    </button>

                    <!-- FOTO DO FUNCIONÁRIO -->
                    <div class="flex justify-center mb-4">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-cyan-400/60 shadow-[0_0_12px_#00ffff80]">
                            <img src="<?php echo htmlspecialchars($fotoFuncionario ?: 'perfil-default.png'); ?>" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <h3 class="text-base sm:text-lg font-bold text-center mb-3 sm:mb-4">Dados do Funcionário</h3>

                    <!-- Tabs -->
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <button class="tab-func active px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/50" data-tab="tab-dados">Dados</button>
                        <button class="tab-func px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs font-bold bg-transparent text-cyan-300 border border-cyan-500/30 hover:bg-cyan-500/10" data-tab="tab-horarios">Horários</button>
                    </div>

                    <!-- Conteúdos -->
                    <div id="tab-dados" class="tab-pane">
                        <div class="flex flex-col gap-2 sm:gap-3 text-xs sm:text-sm">
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <div class="flex items-center gap-1.5 sm:gap-2 text-cyan-400 font-semibold text-xs">
                                    <i class="fas fa-user"></i> Nome:
                                </div>
                                <span class="font-bold text-right text-white break-words text-xs sm:text-sm"><?php echo htmlspecialchars($nomeFuncionario); ?></span>
                            </div>
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <div class="flex items-center gap-1.5 sm:gap-2 text-cyan-400 font-semibold text-xs">
                                    <i class="fas fa-building"></i> Empresa:
                                </div>
                                <span class="font-bold text-right text-white break-words text-xs sm:text-sm"><?php echo htmlspecialchars($nomeEmpresa); ?></span>
                            </div>
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <div class="flex items-center gap-1.5 sm:gap-2 text-cyan-400 font-semibold text-xs">
                                    <i class="fas fa-id-card"></i> RG:
                                </div>
                                <span class="font-bold text-right text-white break-words text-xs sm:text-sm"><?php echo htmlspecialchars($rgFuncionario); ?></span>
                            </div>
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <div class="flex items-center gap-1.5 sm:gap-2 text-cyan-400 font-semibold text-xs">
                                    <i class="fas fa-address-card"></i> CPF:
                                </div>
                                <span class="font-bold text-right text-white break-words text-xs sm:text-sm"><?php echo htmlspecialchars($cpfFuncionario); ?></span>
                            </div>
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <div class="flex items-center gap-1.5 sm:gap-2 text-cyan-400 font-semibold text-xs">
                                    <i class="fas fa-calendar-alt"></i> Nascimento:
                                </div>
                                <span class="font-bold text-right text-white break-words text-xs sm:text-sm"><?php echo htmlspecialchars($dataNascimentoFuncionario); ?></span>
                            </div>
                        </div>
                    </div>

                    <div id="tab-horarios" class="tab-pane hidden">
                        <div class="grid grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm">
                            <div class="bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30">
                                <span class="text-cyan-400 font-semibold block mb-1 text-xs"><i class="fas fa-sign-in-alt"></i> Entrada:</span>
                                <span class="text-white font-bold text-xs sm:text-sm"><?php echo htmlspecialchars($horarioEntrada1); ?></span>
                            </div>
                            <div class="bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30">
                                <span class="text-cyan-400 font-semibold block mb-1 text-xs"><i class="fas fa-sign-out-alt"></i> Saída:</span>
                                <span class="text-white font-bold text-xs sm:text-sm"><?php echo htmlspecialchars($horarioSaida1); ?></span>
                            </div>
                            <div class="bg-[#0d1628]/70 p-2 sm:p-3 rounded-lg border border-cyan-400/30 col-span-2">
                                <span class="text-cyan-400 font-semibold block mb-1 text-xs"><i class="fas fa-coffee"></i> Intervalo:</span>
                                <span class="text-white font-bold text-xs sm:text-sm"><?php echo htmlspecialchars($horarioIntEntrada2); ?> -  <?php echo htmlspecialchars($horarioSaida2);?></span>
                            </div>
                        </div>
                    </div>
                </div>       
            </div>

            <div class="flex flex-col items-center">
                <img id="imgFuncionarioDesk" src="<?php echo $fotoFuncionario; ?>" alt="Foto do Funcionário"
                     class="w-24 h-24 rounded-full border-4 border-cyan-400 object-cover mb-4 hidden md:block">

                <!-- Botão de Informações (no lugar do dropdown) -->
                <div class="relative w-full max-w-xs mt-2 sm:m-10">
                    <button id="btn-mais-info" type="button" class="w-full bg-slate-800/80 border border-cyan-400/50 text-white rounded-lg px-4 py-2 flex items-center justify-between shadow-[0_0_10px_#00ffff20] hover:shadow-[0_0_15px_#00ffff50] transition">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-cyan-400"></i>
                            <span class="text-white font-bold">Ver Informações</span>
                    </button>
                </div>
                <!-- Dados do Funcionário (desktop) -->
                <div class="hidden md:block bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_20px_#00ffff20] rounded-3xl md:p-6 p-3 w-full mb-0 mt-5" id="card-dados-funcionario">
                    <div class="flex flex-col gap-2">
                        <!-- Nome -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-user mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">Nome:</span>
                            </div>
                            <span class="text-sm font-bold text-white truncate min-w-[120px] max-w-[180px]"><?php echo htmlspecialchars($nomeFuncionario); ?></span>
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
            </div>
        </div>

        <!-- Conteúdo do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-center md:p-4 p-2">

            <!-- Título -->
            <div class="mb-4 sm:mb-6">
                <h2 class="text-transparent bg-clip-text text-center bg-gradient-to-r from-cyan-400 to-blue-500 text-xl sm:text-2xl md:text-3xl font-extrabold">
                    PAINEL REGISTRO PONTO
                </h2>
            </div>

            <!-- Últimos registros -->
            <div class="mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-bold text-white mb-2 sm:mb-3">Últimos registros</h3>
                <div class="grid grid-cols-2 gap-2 sm:gap-3 md:gap-4">
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-in-alt text-green-600 text-lg sm:text-xl mb-1"></i>
                        <span id="ENTRADA1" class="font-bold text-green-600 text-sm sm:text-base">---</span>
                        <span class="text-xs font-bold text-white">Entrada</span>
                        <span id="statusENTRADA1" class="text-xs ">---</span>
                        <!-- text-green-500 -->
                    </div>
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-out-alt text-red-600 text-lg sm:text-xl mb-1"></i>
                        <span id="SAIDA1" class="font-bold text-red-600 text-sm sm:text-base">---</span>
                        <span class="text-xs font-bold text-white">Saída</span>
                        <span id="statusSAIDA1" class="text-xs ">---</span>
                        <!-- text-red-500 -->
                    </div>
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-in-alt text-green-600 text-lg sm:text-xl mb-1"></i>
                        <span id="ENTRADA2" class="font-bold text-green-600 text-sm sm:text-base">---</span>
                        <span class="text-xs font-bold text-white">Entrada</span>
                        <span id="statusENTRADA2" class="text-xs ">---</span>
                        <!-- text-yellow-500 -->
                    </div>
                    <div class="bg-slate-800/80 border border-cyan-500/10 rounded-xl p-2 sm:p-3 flex flex-col items-center shadow-[0_0_10px_#00ffff10]">
                        <i class="fas fa-sign-out-alt text-red-600 text-lg sm:text-xl mb-1"></i>
                        <span id="SAIDA2" class="font-bold text-red-600 text-sm sm:text-base">---</span>
                        <span class="text-xs font-bold text-white">Saída</span>
                        <span id="statusSAIDA2" class="text-xs ">---</span>
                        <!-- text-yellow-500 -->
                    </div>
                </div>
            </div>

            <!-- Localização -->
            <div class="mb-3 sm:mb-4">
                <label class="block text-xs font-bold mb-1 text-white" for="localizacao">Localização Atual</label>
                <div class="bg-slate-900 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] flex items-center rounded-lg p-2 sm:p-1.5 glow-box">
                    <i class="fas fa-map-marker-alt text-cyan-400 text-base sm:text-lg mr-1.5 flex-shrink-0"></i>
                    <input id="inputLocalizacao" type="text" value=""
                        class="w-full bg-transparent border-none outline-none font-bold text-white text-xs sm:text-sm truncate" readonly>
                </div>
            </div>

            <!-- Botão bater ponto -->
            <div class="flex justify-center mt-3 sm:mt-4 mb-2 sm:mb-3">
                <button id="btnBaterPonto" 
                    class="relative bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white font-bold py-3 px-6 sm:py-3.5 sm:px-8 rounded-xl text-sm sm:text-base
                        shadow-[0_0_15px_#00ffff50] transition-all 
                        hover:shadow-[0_0_25px_#00ffff80] hover:scale-105 active:scale-95
                        flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-center">
                    <img src="/../../assets/img/faceidIcon.png" class="w-6 h-6 sm:w-7 sm:h-7 icon-color" alt="Ícone FaceID" />
                    <span>BATER PONTO</span>
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

<!-- Modal de Bater Ponto -->
<div id="modal-bater-ponto" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
<div class="w-[90%] sm:w-full max-w-md sm:max-w-lg bg-slate-900/95 backdrop-blur-md 
    border border-cyan-500/30 rounded-2xl shadow-2xl 
    p-2 sm:p-3 md:p-4 relative 
    overflow-hidden max-h-[80vh]">
        
        <!-- Botão Fechar -->
        <button id="btnCloseModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-2xl font-bold transition z-10 bg-slate-800/80 rounded-full w-8 h-8 flex items-center justify-center hover:bg-slate-700" aria-label="Fechar Modal">
            &times;
        </button>
        
        <h2 class="text-xl font-bold text-white text-center mb-4 flex items-center justify-center gap-2">
            <img src="/../../assets/img/faceidIcon.png" class="w-8 h-8 icon-color" alt="Ícone FaceID" />
            Registro de Ponto
        </h2>

        <!-- Abas -->
        <div class="flex border-b mb-4">
            <button class="tab-button text-cyan-500 font-bold px-4 py-2 border-transparent hover:border-cyan-700 hover:text-white" data-tab="info-tab">
                <i class="fas fa-user mr-2 text-cyan-500"></i><span class="text-cyan-500 font-bold">Informações</span>
            </button>
        </div>

        <!-- Conteúdo das Abas -->
        <div id="info-tab" class="tab-content mb-6">
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="bg-slate-800/50 p-3 rounded-lg border border-cyan-500/20">
                    <div class="text-cyan-400 text-xs mb-1"><i class="fas fa-id-badge mr-2"></i>Nome</div>
                    <div class="text-white font-semibold text-sm" id="tab-nome"></div>
                </div>
                <div class="bg-slate-800/50 p-3 rounded-lg border border-cyan-500/20">
                    <div class="text-cyan-400 text-xs mb-1"><i class="fas fa-id-card mr-2"></i>RG</div>
                    <div class="text-white font-semibold text-sm" id="tab-rg"></div>
                </div>
                <div class="bg-slate-800/50 p-3 rounded-lg border border-cyan-500/20">
                    <div class="text-cyan-400 text-xs mb-1"><i class="fas fa-address-card mr-2"></i>CPF</div>
                    <div class="text-white font-semibold text-sm" id="tab-cpf"></div>
                </div>
                <div class="bg-slate-800/50 p-3 rounded-lg border border-cyan-500/20">
                    <div class="text-cyan-400 text-xs mb-1"><i class="fas fa-calendar-alt mr-2"></i>Nascimento</div>
                    <div class="text-white font-semibold text-sm" id="tab-nascimento"></div>
                </div>
            </div>
        </div>

        <div class="camera-container my-6">
            <div id="camera-container">
                <video id="video-camera" autoplay muted playsinline class="video-feed"></video>
            </div>            
            <div class="overlay">
                <div class="circle-ring"></div>
            </div>
        </div>

        <!-- Botão bater ponto -->
        <div class="flex justify-center mt-4 mb-2">
            <button id="btn-efetuar-ponto" 
                class="relative bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white font-bold 
                    py-3 px-6 sm:py-3.5 sm:px-8 rounded-xl text-sm sm:text-base 
                    shadow-[0_0_15px_#00ffff50] 
                    transition-all hover:shadow-[0_0_25px_#00ffff80] hover:scale-105 active:scale-95
                    flex items-center gap-2 w-full sm:w-auto justify-center">
                <img src="/../../assets/img/faceidIcon.png" 
                    class="w-6 h-6 sm:w-7 sm:h-7 icon-color" alt="Ícone FaceID" />
                <span>BATER PONTO</span>
            </button>
        </div>
    </div>
</div>

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
    sessionStorage.setItem("fotoFuncionario",faceIdFuncionario);
    sessionStorage.setItem("nomeEmpresa",nomeEmpresa);
    
    let divBemVindo = $("#bemVindo");
    let conteudo = `
        <div id="welcome-message" class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="rounded-xl p-6 max-w-md w-full text-center animate-fade-in-up">
                <p class="text-sm sm:text-base md:text-lg text-white mb-1 leading-tight">
                    BEM-VINDO(A)
                </p>
                <h2 id="tituloEmpresa" class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white leading-tight">
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
            document.getElementById("controlador2")?.classList.remove("hidden");
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

        // Funcionalidade das tabs no modal de dados do funcionário
        $('.tab-func').on('click', function() {
            const tabId = $(this).data('tab');
            // Remove active de todas as tabs
            $('.tab-func').removeClass('active').removeClass('bg-cyan-500/20').addClass('bg-transparent');
            // Adiciona active na tab clicada
            $(this).addClass('active').removeClass('bg-transparent').addClass('bg-cyan-500/20');
            // Esconde todos os panes
            $('.tab-pane').addClass('hidden');
            // Mostra o pane correspondente
            $('#' + tabId).removeClass('hidden');
        });
    });
</script>


</body>
</html>
