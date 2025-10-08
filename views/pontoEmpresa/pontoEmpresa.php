<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    header('Location: ../loginEmpresa/loginEmpresa.php');
    exit;
}
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerEmpresa.php');
?>

<div class="min-h-screen flex flex-col items-center justify-center px-4">
    <div class="w-full max-w-6xl bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_20px_#00ffff20] p-0 mt-4 flex flex-col md:flex-row">

        <!-- Header do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-start md:p-4 p-2 border-b md:border-b-0 md:border-r border-cyan-400">

        <!-- Header mobile -->
        <div class="md:hidden flex items-center justify-center gap-2 mb-2 pl-1">
            <img id="imgFuncionario" 
                src="/../../assets/img/iconDefault.png" 
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

            <div class="flex flex-col items-center">
                <img id="imgFuncionarioDesk" src="/../../assets/img/iconDefault.png" alt="Foto do Funcionário"
                     class="w-24 h-24 rounded-full border-4 border-cyan-400 object-cover mb-4 hidden md:block">

                <!-- Select responsivo, bonito e moderno -->
                <div class="relative w-full max-w-xs mt-2  sm:m-10 ">
                    <button id="dropdownBtn" type="button" class="w-full bg-slate-800/80 border border-cyan-400/50 text-white rounded-lg px-4 py-2 flex items-center justify-between shadow-[0_0_10px_#00ffff20] hover:shadow-[0_0_15px_#00ffff50] transition">
                        <span id="selectedFuncionario" class="flex items-center gap-2">
                            <img id="imgFuncionario" src="/../../assets/img/iconDefault.png" alt="Foto" class="w-6 h-6 rounded-full">
                            <span id="nomeFuncDrop" class="text-white font-bold ">Selecione o funcionário</span>
                        </span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="dropdownList" class="absolute left-0 right-0 mt-2 bg-slate-900 border text-white font-bold border-cyan-400 rounded-lg shadow-lg z-10 hidden">
                        <!-- Funcionários serão inseridos aqui via JS -->
                    </div>
                </div>
                <!-- Dados do Funcionário (desktop) -->
                <div class="hidden md:block bg-[#0d1628]/90 backdrop-blur-md border border-cyan-500/20 rounded-2xl shadow-[0_0_20px_#00ffff20] rounded-3xl md:p-6 p-3 w-full mb-0 mt-5)" id="card-dados-funcionario">
                    <div class="flex flex-col gap-2">
                        <!-- Nome -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-user mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">Nome:</span>
                            </div>
                            <span id="nomeTbl" class="text-sm font-bold text-white truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                        <!-- RG -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-id-card mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">RG:</span>
                            </div>
                            <span id="rgTbl" class="text-sm text-white truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                        <!-- CPF -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-address-card mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">CPF:</span>
                            </div>
                            <span id="cpfTbl" class="text-sm text-white truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                        <!-- Nascimento -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-calendar-alt mr-2 text-cyan-400 text-base"></i>
                                <span class="text-xs text-white font-bold whitespace-nowrap">Nascimento:</span>
                            </div>
                            <span id="nascimentoTbl" class="text-sm text-white truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-center md:p-4 p-2">

            <!-- Título -->
            <div class="mb-6">
                <h2 class="text-transparent bg-clip-text text-center bg-gradient-to-r from-cyan-400 to-blue-500 text-3xl font-extrabold hidden md:block:">
                    PAINEL REGISTRO PONTO
                </h2>
                <br>

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
                <div class="bg-slate-800/80 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] flex items-center bg-gray-100 rounded-lg p-1.5 glow-box">
                    <i class="fas fa-map-marker-alt text-cyan-400 text-lg mr-1.5"></i>
                    <input id="inputLocalizacao" type="text" value="<?php echo isset($_SESSION['empresa_loc']) ? $_SESSION['empresa_loc'] : 'Endereço não disponível'; ?>"
                        class="w-full min-w-[250px] bg-slate-800/80 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] border-none outline-none font-bold text-white text-xs" readonly>
                </div>
            </div>

            <!-- Botão bater ponto -->
            <div class="flex justify-center mt-4 mb-3">
                <button id="btnBaterPonto" class="relative bg-cyan-500/90 hover:bg-cyan-400 text-slate-900 font-bold py-3 px-6 rounded-lg text-base shadow-[0_0_15px_#00ffff40] transition-all hover:shadow-[0_0_25px_#00ffff80] hover:scale-105 flex items-center gap-3 text-white">
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


<!-- Modal mobile ajustado -->
<div id="modal-dados-funcionario" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="w-[90%] max-w-md bg-[#101a2b]/95 md:border md:border-cyan-500/20 text-slate-100 rounded-2xl shadow-[0_0_25px_#00ffff20] px-6 py-4 backdrop-blur-xl">
    <h3 class="text-lg font-bold text-center mb-4">Dados do Funcionário</h3>

    <div class="flex flex-col gap-3 text-sm">
      <!-- Nome -->
      <div class="flex justify-between">
        <div class="flex items-center gap-2 text-gray-400 font-medium">
          <i class="fas fa-user"></i> Nome:
        </div>
        <span class="font-semibold text-right text-gray-200 break-words">Fulano</span>
      </div>

      <!-- Empresa -->
      <div class="flex justify-between">
        <div class="flex items-center gap-2 text-gray-400 font-medium">
          <i class="fas fa-building"></i> Empresa:
        </div>
        <span class="font-semibold text-right text-gray-200 break-words">Empresa X</span>
      </div>

      <!-- RG -->
      <div class="flex justify-between">
        <div class="flex items-center gap-2 text-gray-400 font-medium">
          <i class="fas fa-id-card"></i> RG:
        </div>
        <span class="font-semibold text-right text-gray-200 break-words">00.000.000-0</span>
      </div>

      <!-- CPF -->
      <div class="flex justify-between">
        <div class="flex items-center gap-2 text-gray-400 font-medium">
          <i class="fas fa-address-card"></i> CPF:
        </div>  
        <span class="font-semibold text-right text-gray-200 break-words">000.000.000-00</span>
      </div>

      <!-- Nascimento -->
      <div class="flex justify-between">
        <div class="flex items-center gap-2 text-gray-400 font-medium">
          <i class="fas fa-calendar-alt"></i> Nascimento:
        </div>
        <span class="font-semibold text-right text-gray-200 break-words">01/01/1990</span>
      </div>
    </div>

    <button id="btn-fechar-modal-mobile" class="mt-6 w-full bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg text-lg hover:bg-gray-300 transition">
      Fechar
    </button>
  </div>
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
            <button id="btnBaterPonto" 
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

<script src="./js/pontoEmpresa.js"></script>
<link rel="stylesheet" href="./css/pontoEmpresa.css">
<script>
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

        $("#btnCloseModal").on('click', function() {
            $("#modal-bater-ponto").addClass('hidden');
            $("#video-camera").get(0).srcObject.getTracks().forEach(track => track.stop());
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
