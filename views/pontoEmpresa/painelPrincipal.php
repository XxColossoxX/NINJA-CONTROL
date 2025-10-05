<?php

session_start();

if (!isset($_SESSION['empresa_id'])) {
    header('../loginEmpresa/loginEmpresa.php');
    exit;
}
require_once('../../assets/components/header.php');
require_once('../../assets/components/background.php');


$empresaId = $_SESSION['empresa_id']; // Recupera o ID da empresa da sessão
?>
        <!-- Dashboard -->
        <div class="content container mx-auto mt-8 px-4 ">
            <div class="flex justify-end mb-4">
                <button id="add-employee-btn" class="bg-green-400 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    ADICIONAR FUNCIONÁRIO
                </button>
            </div>
            <div class="overflow-x-auto">
                <table id="tblFuncionario" class="table-auto w-full bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-600 text-white">
                        <tr>
                            <th class="px-4 py-2 border-r-4 border-gray-600 text-center rounded-tl-lg"><strong>ID</strong></th>
                            <th class="px-4 py-2 border-r-4 border-gray-600 text-center "><strong>NOME</strong></th>
                            <th class="px-4 py-2 border-r-4 border-gray-600 text-center hidden md:table-cell"><strong>CPF</strong></th>
                            <th class="px-4 py-2 border-r-4 border-gray-600 text-center hidden md:table-cell"><strong>RG</strong></th>
                            <th class="px-4 py-2 border-r-4 border-gray-600 text-center "><strong>IMAGEM</strong></th>
                            <th class="px-2 py-1 border-r-4 border-gray-600 text-center"><strong>EDITAR</strong></th>
                            <th class="px-2 py-1 text-center rounded-tr-lg"><strong>EXCLUIR</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- As linhas da tabela serão preenchidas dinamicamente -->
                    </tbody>
                    <tfoot class="bg-gray-600 text-white">
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center rounded-bl-lg rounded-br-lg">
                                <strong>Total de Funcionários:</strong> <span id="totalFunc">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    <!-- Modal do Formulário -->
    <div id="form-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6 relative">
            <button id="close-form-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
                &times;
            </button>
            <h2 class="text-xl font-bold mb-4">Adicionar Funcionário</h2>
            <form id="employee-form">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome</label>
                    <input id="inputNomeFuncionario" type="text" placeholder="Digite o nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cpf">CPF</label>
                    <input id="inputCpfFuncionario" type="text" placeholder="Digite o CPF" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cpf">RG</label>
                    <input id="inputRgFuncionario" type="text" placeholder="Digite o RG" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cpf">DATA_NASCIMENTO</label>
                    <input id="inputDataNascFuncionario" type="date" placeholder="dd/mm/aaaa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="btnProximo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Próximo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Captura de Rosto -->
    <div id="camera-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-3/4 p-6 relative flex">
            <!-- Seção da câmera -->
            <div class="w-1/2 flex flex-col items-center">
                <button id="close-camera-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
                    &times;
                </button>
                <h2 class="text-xl font-bold mb-4">Cadastro Facial</h2>
                <p id="camera-error" class="text-red-500 text-center mb-4 hidden">Verifique se seu navegador não bloqueou o acesso à câmera.</p>
                <div class="relative mb-6">
                    <div class="w-64 h-64 rounded-full border-4 border-dashed border-primary flex items-center justify-center overflow-hidden">
                        <video id="register-camera" autoplay playsinline class="w-full h-full object-cover"></video>
                    </div>
                </div>
                <button id="btnCapturar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Capturar
                </button>
            </div>

            <!-- Seção de dados do formulário -->
            <div class="w-1/2 pl-6">
                <h3 class="text-lg font-bold mb-4">Dados do Funcionário</h3>
                <p><strong>Nome:</strong> <span id="display-nome"></span></p>
                <p><strong>CPF:</strong> <span id="display-cpf"></span></p>
                <p><strong>RG:</strong> <span id="display-rg"></span></p>
                <p><strong>Data de Nascimento:</strong> <span id="display-data"></span></p>
                <div id="welcome-message" class="mt-6 text-green-500 font-bold hidden">
                    Bem-vindo, <span id="welcome-nome"></span>!
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="alert-box" class="hidden fixed top-12 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-md text-white">
        <span id="alert-message"></span>
    </div>

    <script src="./js/painelPrincipal.js"></script>
    <link rel="stylesheet" href="./css/painelPrincipal.css">

</body>
</html>
