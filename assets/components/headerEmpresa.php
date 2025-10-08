    <style>
        nav {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            background: white;
            color: black;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 30;
        }

        .page-content {
            margin-top: 64px;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        .welcome-message {
            font-family: 'Noto Sans', sans-serif;
            font-size: 3rem;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: slideInRight 1s ease, fadeOut 1s ease 3s forwards;
            text-align: center;
        }

        body {
            overflow-x: hidden;
        }

        .menu-slide {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .menu-hidden {
            transform: translateX(100%);
            opacity: 0;
            pointer-events: none;
        }
        .menu-visible {
            transform: translateX(0);
            opacity: 1;
            pointer-events: auto;
        }

        #config-overlay {
            background-color: rgba(0,0,0,0.5);
            position: fixed;
            inset: 0;
            display: none;
            z-index: 40;
        }

        #config-panel {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        #config-panel > div {
            border-radius: 0.5rem;
            max-width: 28rem;
            width: 100%;
            padding: 1.5rem;
            position: relative;
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        #close-config {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #ffffffff;
        }
        #close-config:hover {
            color: #e00;
        }

        @media (max-width: 768px) {
            table thead th:nth-child(1),
            table thead th:nth-child(3),
            table tbody td:nth-child(1),
            table tbody td:nth-child(3) {
                display: none;
            }
            .logo-text { display: none; }
            .logo-emoji { display: block; }
        }
        @media (min-width: 769px) {
            .logo-emoji { display: none; }
        }
    </style>
</head>

<body class="bg-animated-gradient">

    <div id="bemVindo">
        <!-- carrego pelo js -->
    </div>

    <div id="controlador" class="hidden">

    <!-- Navbar -->
    <nav class="header-funcionario bg-slate-900">
        <div class="container mx-auto px-4 py-1.5 sm:py-3 flex items-center justify-between relative text-white">

                <!-- Logo -->
                <div class="flex items-center text-lg font-bold">
                    <img id="ninja-img" class="w-12 mr-2" src="../../assets/img/ninjaLogo.png" alt="Ninja Control" />
                    <span class="logo-text">NINJA CONTROL</span>
                </div>

                <!-- Nome Centralizado -->
                <div class="text-base sm:text-xl font-semibold absolute left-1/2 transform -translate-x-1/2">
                    PAINEL EMPRESA
                </div>

                <!-- Botão Menu -->
                <button id="menu-toggle" class="block hover:opacity-80">
                    <div class="space-y-1">
                        <span style="display: block; width: 1.5rem; height: 0.125rem; background-color: #ffffffff;"></span>
                        <span style="display: block; width: 1.5rem; height: 0.125rem; background-color: #ffffffff;"></span>
                        <span style="display: block; width: 1.5rem; height: 0.125rem; background-color: #ffffffff;"></span>
                    </div>
                </button>

            </div>

            <!-- Menu lateral -->
            <div id="menu" class="absolute top-0 right-0 bg-slate-900 text-white w-48 h-screen menu-slide menu-hidden shadow-lg">
                <!-- Cabeçalho do Menu -->
                <div class="bg-slate-800 text-center py-4 font-bold text-lg relative">
                    NINJA CONTROL
                    <button id="menu-close" class="absolute top-2 right-2 text-red-500 font-bold hover:text-gray-800 focus:outline-none">
                        X
                    </button>
                </div>

                <!-- Itens do Menu -->
                <ul class="flex flex-col justify-between h-[85%] px-4 py-4">

                    <!-- Itens principais -->
                    <div class="space-y-4">
                        <li>
                            <a href="../../views/pontoEmpresa/pontoEmpresa.php" class="flex items-center gap-2 hover:text-gray-500">
                                <i class="fas fa-clock"></i> REGISTRAR PONTO 
                            </a>
                        </li>
                        <li>
                            <a href="../../views/historicoPontoAdmin/historicoPontoAdmin.php" class="flex items-center gap-2 hover:text-gray-500">
                                <i class="fas fa-history"></i> HISTÓRICO PONTOS
                            </a>
                        </li>
                        <li>
                            <a href="../../views/painelPrincipal/painelPrincipal.php" class="flex items-center gap-2 hover:text-gray-500">
                                <i class="fas fa-users"></i> PAINEL DE FUNCIONÁRIOS
                            </a>
                        </li>
                        <li>
                            <a href="../../views/dadosEmpresaAdmin/dadosEmpresaAdmin.php" class="flex items-center gap-2 hover:text-gray-500">
                                <i class="fas fa-building"></i> DADOS EMPRESA
                            </a>
                        </li>
                        <li>
                            <a href="../../index.php" class="flex items-center gap-2 hover:text-gray-500">
                                <i class="fas fa-sign-out-alt"></i> LOGOUT
                            </a>
                        </li>
                    </div>

                    <!-- Configurações -->
                    <div>
                        <li>
                            <a href="#" id="config-link" class="flex items-center gap-2 hover:text-gray-500">
                                <i class="fas fa-cog"></i> CONFIGURAÇÕES
                            </a>
                        </li>
                    </div>

                </ul>
            </div>
        </nav>
    </div>

    <!-- Modal Overlay -->
    <div id="config-overlay" style="display: none;"></div>

    <!-- Modal de Opções de Configuração -->
    <div id="config-options-panel" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
        <div class="bg-slate-900 rounded-lg shadow-lg w-full max-w-sm p-6 relative">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-cogs text-cyan-500"></i> Configurações
            </h2>

            <ul class="space-y-4">
                <li>
                    <button id="btnAbrirAlterarSenha" class="w-full flex items-center gap-2 px-4 py-2 text-sm bg-cyan-500 text-white rounded hover:bg-cyan-700 transition">
                        <i class="fas fa-key"></i> Alterar Senha de Acesso
                    </button>
                </li>
                <li>
                    <button id="" class="w-full flex items-center gap-2 px-4 py-2 text-sm bg-cyan-500 text-white rounded hover:bg-cyan-700 transition">
                        <i class="fas fa-rocket"></i> Futuras Configurações (BETA)
                    </button>
                </li>
                <!-- Outras opções podem ser adicionadas aqui -->
            </ul>

            <button id="close-config-options" class="absolute top-3 right-3 text-white hover:text-red-600 text-xl">
                &times;
            </button>
        </div>
    </div>


    <!-- Modal Alteração de Senha -->
    <div id="config-panel" style="display: none;" class="">
        <div class="bg-slate-900">
            <h2 class="text-2xl font-bold mb-4 text-white">Alteração de Senha</h2>
            <form id="config-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white">Senha Atual</label>
                    <input id="inputSenhaAtual" type="password" name="senha_atual" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-cyan-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Nova Senha</label>
                    <input id="inputNovaSenha" type="password" name="nova_senha" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-cyan-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Confirmar Nova Senha</label>
                    <input id="inputConfirmarNovaSenha" type="password" name="confirmar_nova_senha" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-cyan-500" required />
                </div>
                <div class="text-right">
                    <button id="btnAlterarSenha" type="submit" class="bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-700">
                        Alterar Senha
                    </button>
                </div>
            </form>
            <button id="close-config">&times;</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Abrir modal de opções de configurações
            $('#config-link').click(function (e) {
                e.preventDefault();
                $('#menu').removeClass('menu-visible').addClass('menu-hidden');
                $('#config-options-panel').removeClass('hidden').css('display', 'flex').hide().fadeIn(200);
            });

            // Fechar modal de opções
            $('#close-config-options, #config-options-panel').click(function (e) {
                if (e.target === this || e.target.id === 'close-config-options') {
                    $('#config-options-panel').fadeOut(200, function () {
                        $(this).addClass('hidden'); // esconde novamente após fade
                    });
                }
            });

            // Ir para modal de alteração de senha a partir do menu
            $('#btnAbrirAlterarSenha').click(function () {
                $('#config-options-panel').fadeOut(200);
                $('#config-overlay, #config-panel').fadeIn(200);
            });

             $('#config-overlay, #config-panel').hide();
            // Abrir menu hamburguer
            $('#menu-toggle').click(function () {
                $('#menu').removeClass('menu-hidden').addClass('menu-visible');
            });

            // Fechar menu hamburguer
            $('#menu-close').click(function () {
                $('#menu').removeClass('menu-visible').addClass('menu-hidden');
            });

            // Fechar modal (botão X e clique no overlay)
            $('#close-config, #config-overlay').click(function () {
                $('#config-overlay, #config-panel').fadeOut(200);
            });

            // Submit form - só alert (pode ser substituído por AJAX)
            $('#config-form').submit(function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                console.log('Configurações enviadas:', data);
                alert('Configurações salvas!');
                $('#config-overlay, #config-panel').fadeOut(200);
            });

            $("#btnAlterarSenha").on('click', async function(e) {
                e.preventDefault();

                const senhaAtual = $("#inputSenhaAtual").val().trim();
                const novaSenha = $("#inputNovaSenha").val().trim();
                const confirmarNovaSenha = $("#inputConfirmarNovaSenha").val().trim();

                if (!senhaAtual || !novaSenha || !confirmarNovaSenha) {
                    alert("Por favor, preencha todos os campos.");
                    return;
                }

                if (novaSenha !== confirmarNovaSenha) {
                    alert("A nova senha e a confirmação não coincidem.");
                    return;
                }

                const res = await axios({
                    url: "../../../backend/backend.php",
                    method: "POST",
                    data: {
                        function: "getSenhaAtualEmpresa",
                        SENHA_EMPRESA: senhaAtual,
                        ID_EMPRESA: "<?php echo $_SESSION['empresa_id']; ?>",
                    },
                });

                if(res.data.success){
                    const resUpdate = await axios({
                        url: "../../../backend/backend.php",
                        method: "POST",
                        data: {
                            function: "updateSenhaEmpresa",
                            SENHA_EMPRESA: novaSenha,
                            ID_EMPRESA: "<?php echo $_SESSION['empresa_id']; ?>",
                        },
                    });
                    if(resUpdate.data.success){
                        showAlert("Senha Atualizada com sucesso!", "success");
                        console.log(resUpdate.data)
                        $('#inputSenhaAtual').val('');
                        $('#inputNovaSenha').val('');
                        $('#inputConfirmarNovaSenha').val('');
                        $('#config-overlay, #config-panel').fadeOut(200);
                        return;
                    }
                    else{
                        showAlert("Erro ao atualizar a senha. Tente novamente.", "error");
                        return;
                    }
                }
                else{
                    showAlert("Senha atual incorreta.", "error");
                    return;
                }
                $('#config-overlay, #config-panel').fadeOut(200);
            });
        });
    </script>
</body>
</html>
