<?php
require_once('../../assets/components/background.php')
?>

  <!-- Formulário de Registro -->
  <div id="login-system" class="w-[90%] sm:w-full max-w-md mx-auto bg-white bg-opacity-90 shadow-xl rounded-2xl p-4 sm:p-8">
        <div class="text-center">
            <img class="mx-auto w-20" src="../../assets/img/ninjaLogo.png" alt="Ninja Control">
            <h2 class="text-3xl font-bold text-gray-800 mt-2 mb-2">LOGIN FUNCIONÁRIO</h2><h1 class="text-3x1 font-bold text-gray-700 mb-2">Solicite seu acesso ao suporte da sua empresa!</h1> 
        </div>

        <!-- Campo Usuário -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold">Digite o CPF:</label>
            <input id="inputCpfFuncionario" type="text" placeholder="Digite seu usuário" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" /> 
        </div>
        
        <!-- Senha -->
        <div class="mb-4 relative">
            <label class="block text-gray-700 font-bold">Senha:</label>
            <div class="relative">
                <input id="inputSenhaFuncionario" type="password" placeholder="Digite a senha"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
                <button type="button" id="toggleSenhaFuncionario"
                    class="absolute right-3 top-[calc(50%+2px)] transform -translate-y-1/2 text-gray-500">
                    <i class="fas fa-eye" id="iconSenhaFuncionario"></i>
                </button>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex flex-col space-y-3">
            <button id="btnEntrar" class="bgBtn w-[95%] sm:w-full bg-gray-700 text-white py-2 sm:py-3 rounded-md text-center transition sm:hover:scale-110 mx-auto block">
                <strong>Entrar</strong>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>

            <a href="../../../index.php" class="bgBtn w-[95%] sm:w-full bg-gray-700 text-white py-2 sm:py-3 rounded-md text-center transition sm:hover:scale-110 mx-auto block mt-3">
                <strong>Voltar</strong>
                <i class="fas fa-arrow-left ml-2"></i>
            </a>
        </div>
    </div>

<script src="/views/loginFuncionario/js/loginFuncionario.js"></script>
<link rel="stylesheet" href="/views/loginEmpresa/css/loginEmpresa.css">

</body>
</html>
