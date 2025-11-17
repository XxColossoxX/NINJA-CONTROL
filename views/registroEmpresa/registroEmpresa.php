<?php
require_once('../../assets/components/background.php')
?>

    <!-- Formulário de Registro -->
    <div id="login-system" class="relative z-10 w-[90%] sm:w-full max-w-md mx-auto backdrop-blur-md bg-white/10 border border-white/20 shadow-2xl rounded-3xl p-5 sm:p-8">

        <div class="text-center">
            <img class="mx-auto w-20" src="../../assets/img/ninjaLogo.png" alt="Ninja Control">
            <h2 class="text-3xl font-bold text-[#3BA3E6] mt-2 mb-2"><i>REGISTRO EMPRESA</i></h2><h1 class="text-3x1 font-bold text-gray-200 mb-2">Cadastre sua empresa no melhor software do Brasil!</h1> 
        </div>

        <!-- Nome Empresa -->
        <div class="mb-4">
            <label class="block text-white font-bold">Razão Social:</label>
            <input id="inputNomeEmpresa" type="text" placeholder="Digite a razão social da empresa"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
        </div>

        <!-- Razão Fantasia -->
        <div class="mb-4">
            <label class="block text-white font-bold">Razão Fantasia:</label>
            <input id="inputUsuarioEmpresa" type="text" placeholder="Digite como te chamaremos"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
        </div>

          <!-- CNPJ -->
          <div class="mb-4">
            <label class="block text-white font-bold">CNPJ da Empresa:</label>
            <input id="inputCnpjEmpresa" type="text" placeholder="Digite o CNPJ da empresa"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
        </div>

        <!-- Senha -->
        <div class="mb-4 relative">
            <label class="block text-white font-bold">Senha:</label>
            <div class="relative">
                <input id="inputSenhaEmpresa" type="password" placeholder="Digite a senha"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
                <button type="button" id="toggleSenhaEmpresa"
                    class="absolute right-3 top-[calc(50%+2px)] transform -translate-y-1/2 text-gray-500">
                    <i class="fas fa-eye" id="iconSenhaEmpresa"></i>
                </button>
            </div>
        </div>

        <!-- Confirmar Senha -->
        <div class="mb-4 relative">
            <label class="block text-white font-bold">Confirmar Senha:</label>
            <div class="relative">
                <input id="inputConfirmaSenha" type="password" placeholder="Confirme a senha"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
                <button type="button" id="toggleConfirmaSenha"
                    class="absolute right-3 top-[calc(50%+2px)] transform -translate-y-1/2 text-gray-500">
                    <i class="fas fa-eye" id="iconConfirmaSenha"></i>
                </button>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex flex-col space-y-3">
            <button id="btnRegistrar" class="w-full bg-transparent border border-[#3BA3E6] text-[#3BA3E6] py-2.5 sm:py-3 rounded-lg text-center font-semibold hover:bg-[#3BA3E6] hover:text-white transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
                <STRONG> Registrar</STRONG> <i class="fas fa-arrow-right ml-2"></i>
            </button>
            <a href="../../../index.php" class="w-full bg-transparent border border-[#3BA3E6] text-[#3BA3E6] py-2.5 sm:py-3 rounded-lg text-center font-semibold hover:bg-[#3BA3E6] hover:text-white transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
            <STRONG> Voltar</STRONG> <i class="fas fa-arrow-left ml-2"></i>
            </a>
        </div>
    </div>

<script src="./js/registroEmpresa.js"></script>

</body>
</html>
