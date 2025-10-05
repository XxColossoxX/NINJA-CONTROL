<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    header('Location: ../loginEmpresa/loginEmpresa.php');
    exit;
}
require_once('../../assets/components/background.php');
require_once('../../assets/components/headerEmpresa.php');
?>

<div class="min-h-screen flex flex-col items-center justify-center">
<!-- Modal Empresa (como tela principal) -->
<div class="min-h-screen flex flex-col items-center justify-center">
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
                    <span id="spanNome"><strong>Nome:</strong></span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fas fa-id-card text-teal-500"></i>
                    <span id="spanCnpj"><strong>CNPJ:</strong></span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-teal-500"></i>
                    <span id="spanEndereco"><strong>Endereço:</strong></span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fas fa-phone-alt text-teal-500"></i>
                    <span id="spanTelefone"><strong>Telefone:</strong></span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fas fa-envelope text-teal-500"></i>
                    <span id="spanEmail"><strong>Email:</strong></span>
                </li>
            </ul>
        </div>

        <!-- Coluna Direita -->
        <div class="flex-1 flex flex-col items-center justify-between">
            <div class="bg-teal-600 text-white p-6 md:p-8 rounded-xl shadow-md w-full">
                <h3 class="text-lg md:text-xl font-semibold mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Sobre a Empresa
                </h3>
                <p class="text-sm md:text-base leading-relaxed">
                    <span id="spanDescricao"></span>
                </p>
            </div>

            <!-- Botão Editar (DENTRO da coluna direita do modal) -->
            <div class="w-full flex justify-center mt-6">
                <button id="btn-editar-empresa" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded shadow transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Editar Informações
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Modal de Edição da Empresa -->
<div id="modal-editar-empresa" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-8 relative">
        <button id="fechar-modal-edicao" class="absolute top-3 right-4 text-gray-600 hover:text-red-500 text-xl">&times;</button>
        <h2 class="text-xl font-bold mb-6 text-teal-700">Editar Informações da Empresa</h2>
        <form id="form-editar-empresa" method="POST" action="salvarEmpresa.php">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Nome Fantasia</label>
                    <input id="inputNomeFantasia" type="text" placeholder="Digite o Nome Fantasia" name="fantasia" value="" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">CNPJ</label>
                    <input id="inputCnpj" type="text" name="cnpj" placeholder="Digite o CNPJ" value="" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço</label>
                    <div class="relative">
                        <button type="button" id="abrir-modal-endereco"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-left text-gray-700 hover:bg-gray-100">
                            <span id="endereco-resumo">Clique para inserir o endereço</span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Telefone</label>
                    <input id="inputTelefone" type="text" placeholder="Digite o Telefone" name="telefone"  class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="inputEmail" type="email" name="email" placeholder="Digite o Email" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700">Descrição</label>
                    <textarea id="inputDescricao" name="descricao" placeholder="Digite a Descrição" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
                </div>
            </div>
            <div class="mt-6 text-right">
                <a id="btnSalvar" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">
                    Salvar Alterações
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Endereço -->
<div id="modal-endereco" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6 relative">
        <button id="fechar-modal-endereco" class="absolute top-3 right-4 text-gray-600 hover:text-red-500 text-xl">&times;</button>
        <h2 class="text-lg font-bold text-teal-700 mb-4">Inserir Endereço</h2>
        <form id="form-endereco" class="space-y-4">
            <div class="relative">
                <input type="text" id="inputCep" placeholder='Digite o CEP sem o "-" ' class="w-full border px-3 py-2 rounded border-gray-300"/>
                <button type="button" id="btnLoc"
                    class="absolute top-1/2 right-2 transform -translate-y-1/2 text-teal-600 hover:text-teal-800">
                    <i class="fas fa-map-marker-alt"></i>
                </button>
            </div>
            <input type="text" id="inputRua" placeholder="Rua / Logradouro" class="w-full border px-3 py-2 rounded border-gray-300"/>
            <input type="text" id="inputNro" placeholder="Número" class="w-full border px-3 py-2 rounded border-gray-300" />
            <input type="text" id="inputBairro" placeholder="Bairro" class="w-full border px-3 py-2 rounded border-gray-300" />
            <input type="text" id="inputCidade" placeholder="Cidade" class="w-full border px-3 py-2 rounded border-gray-300" />
            <input type="text" id="inputEstado" placeholder="Estado" class="w-full border px-3 py-2 rounded border-gray-300" />
            <div class="text-right">
                <button type="button" id="btnSalvarEndereco" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">Salvar Endereço</button>
            </div>
        </form>
    </div>
</div>


<script src="./js/dadosEmpresaAdmin.js">
</script>
<link rel="stylesheet" href="./css/dadosEmpresaAdmin.css">
</body>
</html>