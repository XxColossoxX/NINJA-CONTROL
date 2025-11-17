<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    header('Location: ../loginEmpresa/loginEmpresa.php');
    exit;
}
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerEmpresa.php');
?>

<!-- Modal Empresa (como tela principal) -->
<div class="min-h-screen flex flex-col items-center justify-center w-full">
    <div class="modalDadosEmpresa w-full max-w-3xl sm:max-w-4xl glow-box bg-slate-900/90 backdrop-blur-md rounded-2xl shadow-[0_0_20px_#00ffff20] p-4 sm:p-6 md:p-8 flex flex-col md:flex-row gap-4 sm:gap-6">
        
        <!-- Coluna Esquerda -->
        <div class="flex-1">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-white mb-3 sm:mb-4 flex items-center gap-2 sm:gap-3">
                <i class="fas fa-building text-cyan-500 text-base sm:text-lg md:text-xl"></i>
                Informações da Empresa 
            </h2>

            <ul class="space-y-2 sm:space-y-3 text-white text-[11px] sm:text-xs md:text-sm">
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-id-badge text-cyan-500"></i>
                    <span id="spanNome"><strong>Nome:</strong></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-id-card text-cyan-500"></i>
                    <span id="spanCnpj"><strong>CNPJ:</strong></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-map-marker-alt text-cyan-500"></i>
                    <span id="spanEndereco"><strong>Endereço:</strong></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-phone-alt text-cyan-500"></i>
                    <span id="spanTelefone"><strong>Telefone:</strong></span>
                </li>
                <li class="flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-envelope text-cyan-500"></i>
                    <span id="spanEmail"><strong>Email:</strong></span>
                </li>
            </ul>
        </div>

        <!-- Coluna Direita -->
        <div class="flex-1 flex flex-col items-center justify-between">
            <div class="bg-slate-800/80 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] rounded-xl p-3 sm:p-4 md:p-6 w-full">
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-2 sm:mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-cyan-500"></i><span class="text-white font-bold"> Sobre a Empresa </span>
                </h3>
                <p class="text-[10px] sm:text-xs md:text-sm leading-relaxed">
                    <span id="spanDescricao" class="text-white"></span>
                </p>
            </div>

            <!-- Botão Editar -->
            <div class="w-full flex justify-center mt-3 sm:mt-4 md:mt-6">
                <button id="btn-editar-empresa" class="bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded shadow transition flex items-center gap-2 text-xs sm:text-sm md:text-base">
                    <i class="fas fa-edit"></i> Editar Informações
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição da Empresa -->
<div id="modal-editar-empresa" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden flex items-center justify-center px-2 sm:px-4">
    <div class="bg-slate-900 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] rounded-xl w-full max-w-md sm:max-w-xl md:max-w-2xl p-4 sm:p-6 md:p-8 relative text-white">
        <button id="fechar-modal-edicao" class="absolute top-3 right-4 text-gray-400 hover:text-cyan-500 text-xl">&times;</button>
        <h2 class="text-base sm:text-lg md:text-xl font-bold mb-5 text-cyan-500">Editar Informações da Empresa</h2>
        <form id="form-editar-empresa" method="POST" action="salvarEmpresa.php" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                <div>   
                    <label class="block text-sm font-semibold text-white">Nome Fantasia</label>
                    <input id="inputNomeFantasia" type="text" placeholder="Digite o Nome Fantasia" name="fantasia" value="" class="w-full border border-cyan-500/30 rounded px-3 py-2 bg-slate-800 text-white glow-box" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white">CNPJ</label>
                    <input id="inputCnpj" type="text" name="cnpj" placeholder="Digite o CNPJ" value="" class="w-full border border-cyan-500/30 rounded px-3 py-2 bg-slate-800 text-white glow-box" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1">Endereço</label>
                    <div class="relative">
                        <button type="button" id="abrir-modal-endereco"
                            class="w-full border border-cyan-500/30 rounded px-3 py-2 text-left text-white hover:bg-cyan-600 bg-slate-800 transition glow-box">
                            <span id="endereco-resumo">Clique para inserir o endereço</span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white">Telefone</label>
                    <input id="inputTelefone" type="text" placeholder="Digite o Telefone" name="telefone"  class="w-full border border-cyan-500/30 rounded px-3 py-2 bg-slate-800 text-white glow-box">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white">Email</label>
                    <input id="inputEmail" type="email" name="email" placeholder="Digite o Email" class="w-full border border-cyan-500/30 rounded px-3 py-2 bg-slate-800 text-white glow-box">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-white">Descrição</label>
                    <textarea id="inputDescricao" name="descricao" placeholder="Digite a Descrição" rows="4" class="w-full border border-cyan-500/30 rounded px-3 py-2 bg-slate-800 text-white glow-box"></textarea>
                </div>
            </div>
            <div class="mt-4 sm:mt-6 text-right">
                <a id="btnSalvar" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded cursor-pointer inline-block text-sm sm:text-base">
                    Salvar Alterações
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Endereço -->
<div id="modal-endereco" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden flex items-center justify-center px-2 sm:px-4">
    <div class="bg-slate-900 border border-cyan-500/10 shadow-[0_0_8px_#00ffff10] rounded-xl w-full max-w-sm sm:max-w-md md:max-w-xl p-4 sm:p-6 md:p-8 relative text-white">
        <button id="fechar-modal-endereco" class="absolute top-3 right-4 text-gray-400 hover:text-cyan-500 text-xl">&times;</button>
        <h2 class="text-lg sm:text-xl font-bold text-cyan-500 mb-4">Inserir Endereço</h2>
        <form id="form-endereco" class="space-y-4 sm:space-y-6">
            <div class="relative">
                <input type="text" id="inputCep" placeholder='Digite o CEP sem o "-" ' class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box"/>
                <button type="button" id="btnLoc"
                    class="absolute top-1/2 right-2 transform -translate-y-1/2 text-cyan-500 hover:text-cyan-600">
                    <i class="fas fa-map-marker-alt"></i>
                </button>
            </div>
            <input type="text" id="inputRua" placeholder="Rua / Logradouro" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box"/>
            <input type="text" id="inputNro" placeholder="Número" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box" />
            <input type="text" id="inputBairro" placeholder="Bairro" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box" />
            <input type="text" id="inputCidade" placeholder="Cidade" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box" />
            <input type="text" id="inputEstado" placeholder="Estado" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box" />
            <input type="text" id="inputLat" placeholder="Lat" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box hidden" />
            <input type="text" id="inputLong" placeholder="Long" class="w-full border px-3 py-2 rounded border-cyan-500/30 bg-slate-800 text-white glow-box hidden" />
            <div class="text-right">
                <button type="button" id="btnSalvarEndereco" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded">Salvar Endereço</button>
            </div>
        </form>
    </div>
</div>

<script src="./js/dadosEmpresaAdmin.js"></script>
<link rel="stylesheet" href="./css/dadosEmpresaAdmin.css">
