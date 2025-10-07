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
    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl p-0 mt-4 flex flex-col md:flex-row">

        <!-- Header do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-start md:p-4 p-2 border-b md:border-b-0 md:border-r border-gray-200">

            <!-- Header mobile -->
            <div class="md:hidden flex items-center gap-2 mb-4">
                <img id="imgFuncionario" src="/../../assets/img/iconDefault.png" alt="Foto do Funcionário"
                     class="w-12 h-12 rounded-full object-cover border-2 border-teal-500">
                <span class="font-bold text-base text-center text-gray-800 flex-1">
                    SEJA MUITO BEM VINDO!<br>
                </span>
            </div>

            <!-- Header desktop -->
            <h2 class="hidden md:block text-xl font-bold text-gray-800 text-center mt-4 mb-10">
                SEJA MUITO BEM VINDO!<br>
            </h2>

            <div class="flex flex-col items-center">
                <img id="imgFuncionarioDesk" src="/../../assets/img/iconDefault.png" alt="Foto do Funcionário"
                     class="w-24 h-24 rounded-full border-4 border-primary object-cover mb-4 hidden md:block">

                <!-- Select responsivo, bonito e moderno -->
                <div class="relative w-full max-w-xs">
                    <button id="dropdownBtn" type="button" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 flex items-center justify-between shadow focus:outline-none">
                        <span id="selectedFuncionario" class="flex items-center gap-2">
                            <img id="imgFuncionario" src="/../../assets/img/iconDefault.png" alt="Foto" class="w-6 h-6 rounded-full">
                            <span id="nomeFuncDrop">Selecione o funcionário</span>
                        </span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="dropdownList" class="absolute left-0 right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden">
                        <!-- Funcionários serão inseridos aqui via JS -->
                    </div>
                </div>
                <!-- Dados do Funcionário (desktop) -->
                <div class="hidden md:block bg-gray-100 rounded-3xl md:p-6 p-3 w-full mb-6 mt-4" id="card-dados-funcionario">
                    <div class="flex flex-col gap-2">
                        <!-- Nome -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-user mr-2 text-gray-500 text-base"></i>
                                <span class="text-xs text-gray-500 font-semibold whitespace-nowrap">Nome:</span>
                            </div>
                            <span id="nomeTbl" class="text-sm font-bold text-gray-700 truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                        <!-- RG -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-id-card mr-2 text-gray-500 text-base"></i>
                                <span class="text-xs text-gray-500 font-semibold whitespace-nowrap">RG:</span>
                            </div>
                            <span id="rgTbl" class="text-sm text-gray-700 truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                        <!-- CPF -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-address-card mr-2 text-gray-500 text-base"></i>
                                <span class="text-xs text-gray-500 font-semibold whitespace-nowrap">CPF:</span>
                            </div>
                            <span id="cpfTbl" class="text-sm text-gray-700 truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                        <!-- Nascimento -->
                        <div class="grid grid-cols-2 items-center gap-x-2 mb-1">
                            <div class="flex items-center min-w-[110px]">
                                <i class="fas fa-calendar-alt mr-2 text-gray-500 text-base"></i>
                                <span class="text-xs text-gray-500 font-semibold whitespace-nowrap">Nascimento:</span>
                            </div>
                            <span id="nascimentoTbl" class="text-sm text-gray-700 truncate min-w-[120px] max-w-[180px]"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo do modal -->
        <div class="w-full md:w-1/2 flex flex-col justify-center md:p-4 p-2">

            <!-- Título -->
            <div class="mb-6">
                <h2 class="hidden md:block text-2xl font-extrabold text-teal-700 text-center mb-4 uppercase tracking-wide">
                    PAINEL REGISTRO PONTO
                </h2>
            </div>

            <!-- Últimos registros -->
            <div class="mb-6">
                <h3 class="text-lg font-bold mb-3">Últimos registros</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-100 rounded-lg p-2 flex flex-col items-center">
                        <i class="fas fa-sign-in-alt text-green-600 text-xl mb-1"></i>
                        <span class="font-bold text-green-600">08:00</span>
                        <span class="text-xs">Entrada</span>
                        <span class="text-xs text-green-500">Concluído</span>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-2 flex flex-col items-center">
                        <i class="fas fa-sign-out-alt text-red-600 text-xl mb-1"></i>
                        <span class="font-bold text-red-600">12:00</span>
                        <span class="text-xs">Saída</span>
                        <span class="text-xs text-red-500">Concluído</span>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-2 flex flex-col items-center">
                        <i class="fas fa-sign-in-alt text-green-600 text-xl mb-1"></i>
                        <span class="font-bold text-green-600">13:30</span>
                        <span class="text-xs">Entrada</span>
                        <span class="text-xs text-yellow-500">Pendente</span>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-2 flex flex-col items-center">
                        <i class="fas fa-sign-out-alt text-red-600 text-xl mb-1"></i>
                        <span class="font-bold text-red-600">18:00</span>
                        <span class="text-xs">Saída</span>
                        <span class="text-xs text-yellow-500">Pendente</span>
                    </div>
                </div>
            </div>

            <!-- Localização -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="localizacao">Localização Atual</label>
                <div class="flex items-center bg-gray-100 rounded-lg p-2">
                    <i class="fas fa-map-marker-alt text-teal-600 text-xl mr-2"></i>
                    <input id="inputLocalizacao" type="text" value="<?php echo isset($_SESSION['empresa_loc']) ? $_SESSION['empresa_loc'] : 'Endereço não disponível'; ?>"
                           class="w-full bg-gray-100 border-none outline-none text-gray-700 text-sm" readonly>
                </div>
            </div>

            <!-- Botão bater ponto -->
            <div class="flex justify-center mt-6 mb-4">
                <button id="btnBaterPonto"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-lg text-lg shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-fingerprint mr-2"></i> BATER PONTO
                </button>
            </div>

            <!-- Rodapé -->
            <div class="text-xs text-gray-400 text-center">
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


<!-- Modal mobile -->
<div id="modal-dados-funcionario" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg p-4 w-full max-w-xs sm:max-w-sm md:max-w-md relative mx-2 overflow-y-auto h-auto">
        <h3 class="text-lg font-bold text-center mb-4">Dados do Funcionário</h3>
        <div class="flex flex-col gap-2">
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="flex items-center">
                    <i class="fas fa-user mr-2 text-gray-500"></i>
                    <span class="text-base text-gray-500 font-semibold">Nome:</span>
                </div>
                <span class="text-base font-bold text-gray-700 text-left break-words"></span>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="flex items-center">
                    <i class="fas fa-building mr-2 text-gray-500"></i>
                    <span class="text-base text-gray-500 font-semibold">Empresa:</span>
                </div>
                <span class="text-base font-bold text-gray-700 text-left break-words"></span>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="flex items-center">
                    <i class="fas fa-id-card mr-2 text-gray-500"></i>
                    <span class="text-base text-gray-500 font-semibold">RG:</span>
                </div>
                <span class="text-base font-bold text-gray-700 text-left break-words"></span>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="flex items-center">
                    <i class="fas fa-address-card mr-2 text-gray-500"></i>
                    <span class="text-base text-gray-500 font-semibold">CPF:</span>
                </div>
                <span class="text-base font-bold text-gray-700 text-left break-words"></span>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>
                    <span class="text-base text-gray-500 font-semibold">Nascimento:</span>
                </div>
                <span class="text-base font-bold text-gray-700 text-left break-words"></span>
            </div>
        </div>
        <button id="btn-fechar-modal-mobile" class="mt-6 w-full bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg text-lg hover:bg-gray-300 transition">Fechar</button>
    </div>
</div>

<!-- Modal de Bater Ponto -->
<div id="modal-bater-ponto" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg md:max-w-2xl lg:max-w-3xl relative p-2 sm:p-4 overflow-y-auto h-auto">
        <h2 class="text-xl font-bold text-center mb-4"><i class="fas fa-fingerprint mr-2 text-teal-600"></i>Registro de Ponto</h2>

        <!-- Abas -->
        <div class="flex border-b border-gray-200 mb-4">
            <button class="tab-button text-gray-600 font-semibold px-4 py-2 border-b-2 border-transparent hover:border-teal-500 hover:text-teal-600" data-tab="info-tab">
                <i class="fas fa-user mr-2"></i>Informações
            </button>
        </div>

        <!-- Conteúdo das Abas -->
        <div id="info-tab" class="tab-content">
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <div><strong><i class="fas fa-id-badge mr-2"></i>Nome:</strong> <span id="tab-nome"></span></div>
                <div><strong><i class="fas fa-building mr-2"></i>Empresa:</strong> <span id="tab-empresa"></span></div>
                <div><strong><i class="fas fa-id-card mr-2"></i>RG:</strong> <span id="tab-rg"></span></div>
                <div><strong><i class="fas fa-address-card mr-2"></i>CPF:</strong> <span id="tab-cpf"></span></div>
                <div><strong><i class="fas fa-calendar-alt mr-2"></i>Nascimento:</strong> <span id="tab-nascimento"></span></div>
            </div>
        </div>

        <div class="camera-container">
            <div id="camera-container">
                <video id="video-camera" autoplay muted playsinline class="video-feed"></video>
            </div>            
            <div class="overlay">
                <div class="circle-ring"></div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-between items-center mt-4">
            <button id="btn-fechar-ponto" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                <i class="fas fa-times mr-2"></i>Fechar
            </button>
            <button id="btn-efetuar-ponto" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition font-bold">
                <i class="fas fa-check-circle mr-2"></i>Efetuar Ponto
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
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#menu-mobile, #btn-menu-mobile').length) {
                $('#menu-mobile').addClass('hidden');
            }
        });
    });
</script>

</body>
</html>
