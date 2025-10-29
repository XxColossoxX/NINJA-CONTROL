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
// Garante APP_CONFIG mesmo se algum header não injetar (fallback)
@include_once __DIR__ . '/../../.env';

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
            <div id="modal-dados-funcionario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden px-0">
                <div class="w-full h-full max-w-none bg-[#0d1628]/95 backdrop-blur-md border border-cyan-500/30 rounded-none shadow-[0_0_25px_#00ffff30] p-0 relative overflow-hidden">
                    <button id="btn-fechar-modal-mobile" class="absolute top-3 right-3 z-10 text-white/80 hover:text-white" aria-label="Fechar">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <div class="h-full overflow-y-auto p-6">
                    <div class="flex flex-col items-center mb-4">
                        <img src="<?php echo $fotoFuncionario; ?>" alt="Foto do Funcionário" class="w-20 h-20 rounded-full object-cover border-2 border-cyan-500 shadow-[0_0_12px_#00ffff50]">
                        <h3 class="text-lg font-bold text-center text-white mt-2 line-clamp-2"><?php echo htmlspecialchars($nomeFuncionario); ?></h3>
                        <span class="text-xs text-cyan-300"><?php echo htmlspecialchars($nomeEmpresa); ?></span>
                    </div>

                    <!-- Tabs -->
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <button class="tab-func active px-3 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/50" data-tab="tab-dados">Dados</button>
                        <button class="tab-func px-3 py-1.5 rounded-full text-xs font-bold bg-transparent text-cyan-300 border border-cyan-500/30 hover:bg-cyan-500/10" data-tab="tab-horarios">Horários</button>
                    </div>

                    <!-- Conteúdos -->
                    <div id="tab-dados" class="tab-pane">
                        <div class="flex flex-col gap-3 text-sm">
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <span class="text-cyan-400 font-semibold flex items-center gap-2"><i class="fas fa-id-card"></i> RG:</span>
                                <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($rgFuncionario); ?></span>
                            </div>
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <span class="text-cyan-400 font-semibold flex items-center gap-2"><i class="fas fa-address-card"></i> CPF:</span>
                                <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($cpfFuncionario); ?></span>
                            </div>
                            <div class="flex justify-between items-center bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30 shadow-[0_0_12px_#00ffff20]">
                                <span class="text-cyan-400 font-semibold flex items-center gap-2"><i class="fas fa-calendar-alt"></i> Nascimento:</span>
                                <span class="text-white font-bold truncate max-w-[220px]"><?php echo htmlspecialchars($dataNascimentoFuncionario); ?></span>
                            </div>
                        </div>
                    </div>

                    <div id="tab-horarios" class="tab-pane hidden">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30">
                                <span class="text-cyan-400 font-semibold block mb-1"><i class="fas fa-sign-in-alt"></i> Entrada:</span>
                                <span class="text-white font-bold">08:00</span>
                            </div>
                            <div class="bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30">
                                <span class="text-cyan-400 font-semibold block mb-1"><i class="fas fa-sign-out-alt"></i> Saída:</span>
                                <span class="text-white font-bold">18:00</span>
                            </div>
                            <div class="bg-[#0d1628]/70 p-3 rounded-lg border border-cyan-400/30">
                                <span class="text-cyan-400 font-semibold block mb-1"><i class="fas fa-coffee"></i> Intervalo:</span>
                                <span class="text-white font-bold">12:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                    </div>
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

            <!-- Modal de Bater Ponto -->
            <div id="modal-bater-ponto" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center">
                <div class="w-full max-w-md sm:max-w-lg bg-slate-900 rounded-xl shadow-lg p-4 relative overflow-y-auto max-h-[90vh]">
                    
                    <!-- Botão Fechar -->
                    <button id="btnCloseModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-2xl font-bold transition" aria-label="Fechar Modal">
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
                    <div id="info-tab" class="tab-content mb-10">
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                            <div class="text-white font-bold"><strong><i class="fas fa-id-badge mr-2 text-cyan-400"></i>Nome:</strong> <span id="tab-nome"></span></div>
                            <div class="text-white font-bold"><strong><i class="fas fa-id-card mr-2 text-cyan-400"></i>RG:</strong> <span id="tab-rg"></span></div>
                            <div class="text-white font-bold"><strong><i class="fas fa-address-card mr-2 text-cyan-400"></i>CPF:</strong> <span id="tab-cpf"></span></div>
                            <div class="text-white font-bold"><strong><i class="fas fa-calendar-alt mr-2 text-cyan-400"></i>Nascimento:</strong> <span id="tab-nascimento"></span></div>
                        </div>
                    </div>

                    <div class="camera-container">
                        <div id="camera-container" class="mt-20">
                            <video id="video-camera" autoplay muted playsinline class="video-feed"></video>
                        </div>            
                        <div class="overlay">
                            <div class="circle-ring"></div>
                        </div>
                    </div>

                    <!-- Botão bater ponto -->
                    <div class="flex justify-center mt-6 mb-4">
                        <button id="btn-efetuar-ponto" 
                            class="relative bg-cyan-500/90 hover:bg-cyan-400 text-slate-900 font-bold 
                                py-2 px-3.5 sm:py-3 sm:px-6 rounded-lg text-sm sm:text-lg 
                                shadow-[0_0_10px_#00ffff40] sm:shadow-[0_0_15px_#00ffff40] 
                                transition-all hover:shadow-[0_0_25px_#00ffff80] hover:scale-105 
                                flex items-center gap-1 sm:gap-2 text-white">
                            <img src="/../../assets/img/faceidIcon.png" 
                                class="w-5 h-5 sm:w-8 sm:h-8 icon-color" alt="Ícone FaceID" />
                            <span class="text-white">BATER PONTO</span>
                        </button>
                    </div>
                </div>
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
