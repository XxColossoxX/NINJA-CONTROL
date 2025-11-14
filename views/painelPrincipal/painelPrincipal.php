<?php
session_start();
if (!isset($_SESSION['empresa_id'])) {
    header('Location: ../loginEmpresa/loginEmpresa.php');
    exit;
}
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerEmpresa.php');

$empresaId = $_SESSION['empresa_id'];
?>

<span id="empresa-id" class="hidden"><?= htmlspecialchars($empresaId) ?></span>
<body class="min-h-screen relative font-sans bg-gray-900 text-white">

    <!-- CONTAINER PRINCIPAL DO PAINEL -->
    <div class="container mx-auto -mt-40 px-4">

        <!-- Boas-vindas -->
        <div id="welcome-message" class="mt-6 text-green-500 font-bold hidden">
            Bem-vindo, <span id="welcome-nome"></span>!
        </div>

        <!-- Painel/controlador -->
        <div class="w-full max-w-6xl mx-auto controlador hidden">

            <!-- Botões -->
            <div class="flex justify-end mb-4">
                <!-- Botão desktop -->
                <button id="btnAdd" class="hidden sm:inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-md transition">
                    <i class="fas fa-user-plus"></i>
                    ADICIONAR FUNCIONÁRIO
                </button>

                <!-- Botão mobile -->
                <button id="btnAddMobile" class="sm:hidden bg-cyan-500 hover:bg-cyan-700 text-white p-2 rounded-full transition">
                    <i class="fas fa-user-plus text-lg"></i>
                </button>
            </div>

            <!-- Tabela de Funcionários -->
            <div class="bg-slate-900 rounded-lg shadow-md">
                <div class="overflow-y-auto max-h-[500px]">
                    <table id="tblFuncionario" class="min-w-full table-auto">
                        <thead class="bg-slate-900 text-white font-bold text-sm uppercase">
                            <tr>
                                <th class="px-2 py-2 text-center w-12">Info</th>
                                <th class="px-2 py-2 text-left">Funcionário</th>
                                <th class="hidden md:table-cell px-2 py-2 text-center">CPF</th>
                                <th class="px-2 py-2 text-center">Editar</th>
                                <th class="px-2 py-2 text-center">Excluir</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot class="bg-slate-900 text-white font-bold text-sm">
                            <tr>
                                <td colspan="5" class="text-center py-2">
                                    Total de Funcionários: <span id="totalFunc">0</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
        <!-- MODAL FORMULÁRIO FUNCIONÁRIO -->
    <div id="form-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden p-4 z-50">
        <div class="bg-slate-900 rounded-lg shadow-lg w-full max-w-md p-6 relative overflow-y-auto max-h-[90vh]">
            <button id="close-form-modal" class="absolute top-2 right-2 text-white hover:text-gray-700 text-2xl font-bold">&times;</button>
            <h2 class="text-xl text-white font-bold mb-4">Adicionar Funcionário</h2>
            <form id="employee-form" class="space-y-4">
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="name">Nome</label>
                    <input id="inputNomeFuncionario" type="text" placeholder="Digite o nome"
                        class="w-full shadow border rounded py-2 px-3  focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="cpf">CPF</label>
                    <input id="inputCpfFuncionario" type="text" placeholder="Digite o CPF"
                        class="w-full shadow border rounded py-2 px-3  focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="rg">RG</label>
                    <input id="inputRgFuncionario" type="text" placeholder="Digite o RG"
                        class="w-full shadow border rounded py-2 px-3  focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="celular">Celular</label>
                    <input id="inputCelularFuncionario" type="text" placeholder="Digite o Celular"
                        class="w-full shadow border rounded py-2 px-3  focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="email">Email</label>
                    <input id="inputEmailFuncionario" type="text" placeholder="Digite o Email"
                        class="w-full shadow border rounded py-2 px-3  focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="password">Senha Acesso</label>
                    <input id="inputSenhaFuncionario" type="password" placeholder="Digite a senha"
                        class="w-full shadow border rounded py-2 px-3  focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-1" for="nascimento">Data de Nascimento</label>
                    <input id="inputDataNascFuncionario" type="date"
                        class="w-full shadow border rounded py-2 px-3 focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" id="btnProximo"
                        class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        Próximo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CAMERA -->
    <div id="camera-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden p-4 z-50">
        <div class="bg-slate-900 rounded-lg shadow-lg w-full max-w-3xl p-6 relative flex flex-col lg:flex-row gap-6 overflow-y-auto max-h-[90vh]">
            <button id="close-camera-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

            <!-- Webcam Section -->
            <div class="flex flex-col items-center w-full lg:w-1/2">
                <h2 class="text-xl text-white font-bold mb-4">Cadastro Facial</h2>
                <p id="camera-error" class="text-red-500 text-center mb-4 hidden">Erro ao acessar a câmera.</p>
                <div class="w-64 h-64 rounded-full border-4 border-primary flex items-center justify-center mb-4 overflow-hidden">
                    <video id="register-camera" autoplay playsinline class="w-full h-full object-cover"></video>
                </div>
                <button id="btnCapturar"
                    class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-transform duration-300 transform hover:scale-105">
                    Capturar
                </button>
            </div>

            <!-- Painel com Abas -->
            <div class="w-full lg:w-1/2">
                <div class="flex gap-2 mb-3">
                    <button class="tab-admin px-3 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/50 active" data-tab="admin-dados">Dados</button>
                    <button class="tab-admin px-3 py-1.5 rounded-full text-xs font-bold bg-transparent text-cyan-300 border border-cyan-500/30 hover:bg-cyan-500/10" data-tab="admin-horarios">Horários</button>
                </div>
                <div id="admin-dados" class="admin-pane space-y-2">
                    <h3 class="text-white font-bold text-lg mb-2">Dados do Funcionário</h3>
                    <p class="text-white font-bold"><strong>Nome:</strong> <span id="display-nome"></span></p>
                    <p class="text-white font-bold"><strong>CPF:</strong> <span id="display-cpf"></span></p>
                    <p class="text-white font-bold"><strong>RG:</strong> <span id="display-rg"></span></p>
                    <p class="text-white font-bold"><strong>Senha Acesso:</strong> <span id="display-senha"></span></p>
                    <p class="text-white font-bold"><strong>Data de Nascimento:</strong> <span id="display-data"></span></p>
                </div>
                <div id="admin-horarios" class="admin-pane hidden">
                    <h3 class="text-white font-bold text-lg mb-3">Horários do Funcionário</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-white text-sm mb-1">Entrada</label>
                            <input id="inputHoraEntrada" type="time" class="w-full bg-slate-800 text-white rounded px-3 py-2 border border-slate-700">
                        </div>
                        <div>
                            <label class="block text-white text-sm mb-1">Saída</label>
                            <input id="inputHoraSaida" type="time" class="w-full bg-slate-800 text-white rounded px-3 py-2 border border-slate-700">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-white text-sm mb-1">Intervalo (início e fim)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input id="inputHoraIntInicio" type="time" class="w-full bg-slate-800 text-white rounded px-3 py-2 border border-slate-700">
                                <input id="inputHoraIntFim" type="time" class="w-full bg-slate-800 text-white rounded px-3 py-2 border border-slate-700">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<link rel="stylesheet" href="./css/painelPrincipal.css">
<script src="./js/painelPrincipal.js"></script>

<script>
    
    // Funções para abrir/fechar modais
    const btnAdd = document.getElementById('btnAdd');
    const btnAddMobile = document.getElementById('btnAddMobile');
    const formModal = document.getElementById('form-modal');
    const cameraModal = document.getElementById('camera-modal');
    const closeFormModal = document.getElementById('close-form-modal');
    const closeCameraModal = document.getElementById('close-camera-modal');

    // Abrir modal formulário
    [btnAdd, btnAddMobile].forEach(btn => {
        if(btn) btn.addEventListener('click', () => {
            formModal.classList.remove('hidden');
        });
    });

    // Fechar modal formulário
    if(closeFormModal) closeFormModal.addEventListener('click', () => {
        formModal.classList.add('hidden');
    });

    // Abrir modal câmera
    function openCameraModal() {
        cameraModal.classList.remove('hidden');
    }

    // Fechar modal câmera
    if(closeCameraModal) closeCameraModal.addEventListener('click', () => {
        cameraModal.classList.add('hidden');
    });

    // Fechar modais ao clicar fora do conteúdo
    [formModal, cameraModal].forEach(modal => {
        if(modal){
            modal.addEventListener('click', (e) => {
                if(e.target === modal){
                    modal.classList.add('hidden');
                }
            });
        }
    });

    // Alternar abas do painel
    const tabs = document.querySelectorAll('.tab-admin');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;
            document.querySelectorAll('.admin-pane').forEach(pane => {
                pane.classList.add('hidden');
            });
            document.getElementById(target).classList.remove('hidden');
            tabs.forEach(t => t.classList.remove('active', 'bg-cyan-500/20'));
            tab.classList.add('active', 'bg-cyan-500/20');
        });
    });
    
    $(document).ready(function() {
    const $formModal = $('#form-modal');
    const $closeFormModal = $('#close-form-modal');
    const $btnProximo = $('#btnProximo'); // exemplo

    // Abrir modal
    $('#btnAdd, #btnAddMobile').on('click', function() {
        $formModal.removeClass('hidden').addClass('modal-show');
    });

    // Fechar modal
    $closeFormModal.on('click', function() {
        $formModal.addClass('hidden').removeClass('modal-show');
    });

    // Fechar clicando fora da janela
    $formModal.on('click', function(e) {
        if (e.target === this) {
            $formModal.addClass('hidden').removeClass('modal-show');
        }
    });
});

</script>
</body>


