<?php
require_once('../../assets/components/background.php')
?>

<div id="registro-empresa-container"
    class="w-full min-h-screen flex items-center justify-center relative overflow-hidden"
    style="height: 100%;
        margin: 0;
        overflow: hidden;
        font-family: 'Noto Sans', sans-serif;
        background: linear-gradient(180deg, #125f7a, #94e4e6, #125f7a) !important;">

    <!-- Overlay escuro -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Formulário Glass -->
    <div id="login-system"
        class="relative z-10 w-[90%] sm:w-full max-w-md mx-auto backdrop-blur-md bg-white/10 border border-white/20 shadow-2xl rounded-3xl p-5 sm:p-8">

        <div class="text-center mb-5">
            <img class="mx-auto w-14 sm:w-20" src="../../assets/img/ninjaLogo.png" alt="Ninja Control">
            <h2 class="text-xl sm:text-3xl font-bold text-[#3BA3E6] mt-3 mb-1 drop-shadow-md">REGISTRO EMPRESA</h2>
            <p class="text-sm sm:text-base font-medium text-gray-200">Cadastre sua empresa no melhor sistema do Brasil!</p>
        </div>

        <!-- Razão Social -->
        <div class="mb-3 sm:mb-4">
            <label class="block text-[#3BA3E6] font-semibold mb-1 text-sm sm:text-base">Razão Social:</label>
            <input id="inputNomeEmpresa" type="text" placeholder="Digite a razão social da empresa"
                class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
        </div>

        <!-- Razão Fantasia -->
        <div class="mb-3 sm:mb-4">
            <label class="block text-[#3BA3E6] font-semibold mb-1 text-sm sm:text-base">Nome Fantasia:</label>
            <input id="inputUsuarioEmpresa" type="text" placeholder="Digite como te chamaremos"
                class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
        </div>

        <!-- CNPJ -->
        <div class="mb-3 sm:mb-4">
            <label class="block text-[#3BA3E6] font-semibold mb-1 text-sm sm:text-base">CNPJ da Empresa:</label>
            <input id="inputCnpjEmpresa" type="text" placeholder="Digite o CNPJ da empresa"
                class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
        </div>

        <!-- Senha -->
        <div class="mb-3 sm:mb-4 relative">
            <label class="block text-[#3BA3E6] font-semibold mb-1 text-sm sm:text-base">Senha:</label>
            <div class="relative">
                <input id="inputSenhaEmpresa" type="password" placeholder="Digite a senha"
                    class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 pr-10 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
                <button type="button" id="toggleSenhaEmpresa"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#3BA3E6] transition">
                    <i class="fas fa-eye" id="iconSenhaEmpresa"></i>
                </button>
            </div>
        </div>

        <!-- Confirmar Senha -->
        <div class="mb-6 relative">
            <label class="block text-[#3BA3E6] font-semibold mb-1 text-sm sm:text-base">Confirmar Senha:</label>
            <div class="relative">
                <input id="inputConfirmaSenha" type="password" placeholder="Confirme a senha"
                    class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 pr-10 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
                <button type="button" id="toggleConfirmaSenha"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#3BA3E6] transition">
                    <i class="fas fa-eye" id="iconConfirmaSenha"></i>
                </button>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex flex-col space-y-3">
            <button id="btnRegistrar"
                class="w-full bg-[#3BA3E6]/90 hover:bg-[#3BA3E6] text-white py-2.5 sm:py-3 rounded-lg text-center font-semibold transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
                <strong>Registrar</strong>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>

            <a href="../../../indexLogin.php"
                class="w-full bg-transparent border border-gray-400 text-gray-300 py-2.5 sm:py-3 rounded-lg text-center font-semibold hover:bg-gray-300/20 transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
                <strong>Voltar</strong>
                <i class="fas fa-arrow-left ml-2"></i>
            </a>
        </div>
    </div>
</div>

<script src="./js/registroEmpresa.js"></script>
<link rel="stylesheet" href="/views/loginEmpresa/css/loginEmpresa.css">

</body>
</html>
