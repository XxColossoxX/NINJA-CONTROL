<?php
    require_once('../../assets/components/background.php')
?>


<div id="login-empresa-container">

    <!-- Formulário Glass -->
    <div id="login-system"
        class="relative z-10 w-[90%] sm:w-full max-w-md mx-auto backdrop-blur-md bg-white/10 border border-white/20 shadow-2xl rounded-3xl p-5 sm:p-8">

        <div class="text-center mb-5">
            <img class="mx-auto w-16 sm:w-20" src="../../assets/img/ninjaLogo.png" alt="Ninja Control">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#3BA3E6] mt-3 mb-1 drop-shadow-md">LOGIN EMPRESA</h2>
            <p class="text-sm sm:text-base font-medium text-gray-200">Solicite seu acesso ao suporte da sua empresa!</p>
        </div>

        <!-- Campo CNPJ -->
        <div class="mb-4">
            <label class="block text-[#3BA3E6] font-semibold mb-1">CNPJ:</label>
            <input id="inputCnpjLogin" type="text" placeholder="Digite o CNPJ da empresa"
                class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
        </div>

        <!-- Campo Senha -->
        <div class="mb-6 relative">
            <label class="block text-[#3BA3E6] font-semibold mb-1">Senha:</label>
            <div class="relative">
                <input id="inputSenhaLogin" type="password" placeholder="Digite a senha"
                    class="w-full border border-white/30 bg-white/10 text-white placeholder-gray-300 rounded-md px-3 py-2 pr-10 text-sm sm:text-base focus:ring-2 focus:ring-[#3BA3E6] focus:border-[#3BA3E6] outline-none transition" />
                <button type="button" id="toggleSenhaLogin"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#3BA3E6] transition">
                    <i class="fas fa-eye" id="iconSenhaLogin"></i>
                </button>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex flex-col space-y-3">
            <button id="btnEntrar"
                class="w-full bg-[#3BA3E6]/90 hover:bg-[#3BA3E6] text-white py-2.5 sm:py-3 rounded-lg text-center font-semibold transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
                <strong>Entrar</strong>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>

            <a href="../../views/registroEmpresa/registroEmpresa.php"
                class="w-full bg-transparent border border-[#3BA3E6] text-[#3BA3E6] py-2.5 sm:py-3 rounded-lg text-center font-semibold hover:bg-[#3BA3E6] hover:text-white transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
                <strong>Registrar</strong>
                <i class="fas fa-user-plus ml-2"></i>
            </a>

            <a href="../../../indexLogin.php"
                class="w-full bg-transparent border border-gray-400 text-gray-300 py-2.5 sm:py-3 rounded-lg text-center font-semibold hover:bg-gray-300/20 transition-all duration-300 hover:scale-[1.03] flex items-center justify-center">
                <strong>Voltar</strong>
                <i class="fas fa-arrow-left ml-2"></i>
            </a>
        </div>
    </div>
</div>

<script src="/views/loginEmpresa/js/loginEmpresa.js"></script>
<link rel="stylesheet" href="/views/loginEmpresa/css/loginEmpresa.css">

</body>
</html>
