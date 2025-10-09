<?php
session_start();
if (!isset($_SESSION['empresa_id'])) {
    header('Location: ../loginEmpresa/loginEmpresa.php');
    exit;
}
require_once('../../assets/components/headerEmpresa.php');
require_once('../../assets/components/background.php');

$empresaId = $_SESSION['empresa_id'];
?>

<!-- Mensagem de Boas-Vindas -->
<div id="welcome-message"
        class="text-center flex flex-col items-center justify-center animate-fadeInUp opacity-0 translate-y-4">
    
    <!-- Linha 1: menor e próxima -->
    <p class="text-lg sm:text-xl md:text-2xl text-white tracking-wider mb-1">
        SEJA BEM-VINDO(A)
    </p>

    <!-- Linha 2: maior e destaque -->
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white">
        <span id="welcome-nome"></span>!
    </h1>
</div>

<div class="flex justify-center items-start min-h-[calc(100vh-64px)] pt-10 px-4">
  <div class="w-full max-w-6xl controlador hidden">


    <div class="flex justify-end mb-4">
      <!-- Botão desktop -->
      <button id="btnAdd" class="hidden sm:inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-md transition">
        <i class="fas fa-user-plus"></i>
        ADICIONAR FUNCIONÁRIO
      </button>

      <!-- Botão mobile -->
      <button id="btnAddMobile" class="sm:hidden bg-cyan-500 text-white p-2 rounded-full transition">
        <i class="fas fa-user-plus text-lg"></i>
      </button>
    </div>

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
        </table>
      </div>
    </div>

    <!-- Modal - Formulário -->
    <div id="form-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden p-4">
        <div class="bg-slate-900 rounded-lg shadow-lg w-full max-w-md p-6 relative mt-24 mb-10">
            <button id="close-form-modal"
                class="absolute top-2 right-2 text-white hover:text-gray-700 text-2xl font-bold">&times;</button>
            <h2 class="text-xl text-white font-bold mb-4">Adicionar Funcionário</h2>
            <form id="employee-form">
                <!-- Campos responsivos -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="name">Nome</label>
                        <input id="inputNomeFuncionario" type="text" placeholder="Digite o nome"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="cpf">CPF</label>
                        <input id="inputCpfFuncionario" type="text" placeholder="Digite o CPF"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="rg">RG</label>
                        <input id="inputRgFuncionario" type="text" placeholder="Digite o RG"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="celular">Celular</label>
                        <input id="inputCelularFuncionario" type="text" placeholder="Digite o Celular"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="email">Email</label>
                        <input id="inputEmailFuncionario" type="text" placeholder="Digite o Email"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="password">Senha Acesso</label>
                        <input id="inputSenhaFuncionario" type="password" placeholder="Digite a senha"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-bold mb-1" for="nascimento">Data de Nascimento</label>
                        <input id="inputDataNascFuncionario" type="date"
                            class="w-full shadow border rounded py-2 px-3 text-black focus:outline-none focus:shadow-outline">
                    </div>
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

    <!-- Modal - Captura Facial -->
    <div id="camera-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden p-4">
        <div
            class="bg-slate-900 rounded-lg shadow-lg w-full max-w-3xl p-6 relative flex flex-col lg:flex-row gap-6 overflow-y-auto max-h-[90vh]">
            <button id="close-camera-modal"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

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

                <!-- Dados do Formulário -->
                <div class="w-full lg:w-1/2 space-y-2">
                    <h3 class="text-white font-bold text-lg font-bold mb-2">Dados do Funcionário</h3>
                    <p class="text-white font-bold"><strong>Nome:</strong> <span id="display-nome"></span></p>
                    <p class="text-white font-bold"><strong>CPF:</strong> <span id="display-cpf"></span></p>
                    <p class="text-white font-bold"><strong>RG:</strong> <span id="display-rg"></span></p>
                    <p class="text-white font-bold"><strong>Senha Acesso:</strong> <span id="display-senha"></span></p>
                    <p class="text-white font-bold"><strong>Data de Nascimento:</strong> <span id="display-data"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./js/painelPrincipal.js"></script>
<link rel="stylesheet" href="./css/painelPrincipal.css">
