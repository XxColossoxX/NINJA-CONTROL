<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Funcionários</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- criar mascaras -->
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
    
    <!-- Ícone -->
    <link rel="icon" type="image/png" href="/assets/img/ninjaLogo.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/ninjaLogo.png">
    <link rel="icon" type="image/png" sizes="512x512" href="assets/img/ninjaLogo.png">

    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

    <style>
        
        /* Animações */
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

        /* Fundo com gradiente animado */
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .ninja-image-animate {
            animation: scaleIn 0.5s ease forwards, scaleOut 0.5s ease 1.5s forwards;
        }

        .animacaoCena {
            font-family: 'Noto Sans', sans-serif;
            font-size: 3rem;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        /* Animação para o menu */
        .menu-slide {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        .menu-hidden {
            transform: translateX(100%);
            opacity: 0;
        }

        .menu-visible {
            transform: translateX(0);
            opacity: 1;
        }

        /* Remover scroll horizontal */
        body {
            overflow-x: hidden;
        }

        /* Ajustar o espaço do conteúdo abaixo da navbar */
        .content {
            padding-top: 64px; /* Altura da navbar */
        }

        /* Responsividade para o Dashboard */
        @media (max-width: 768px) {
            table thead th:nth-child(1),
            table thead th:nth-child(3),
            table tbody td:nth-child(1),
            table tbody td:nth-child(3) {
                display: none;
            }
        }

        /* Responsividade para a Navbar */
        @media (max-width: 768px) {
            .logo-text {
                display: none; /* Esconde o texto "NINJA CONTROL" */
            }
            .logo-emoji {
                display: block; /* Mostra apenas o emoji */
            }
        }

        @media (min-width: 769px) {
            .logo-emoji {
                display: none; /* Esconde o emoji em telas maiores */
            }
        }

        /* Animação de entrada (fade-in + slide-down) */
        @keyframes fadeInSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animação de saída (fade-out + slide-up) */
        @keyframes fadeOutSlideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        /* Classes para animação */
        .alert-show {
            animation: fadeInSlideDown 0.5s ease forwards;
        }

        .alert-hide {
            animation: fadeOutSlideUp 0.5s ease forwards;
        }
        
        /* Ajustar o vídeo dentro do círculo */
        #register-camera {
            border-radius: 50%; /* Faz o vídeo ficar circular */
            object-fit: cover; /* Garante que o vídeo preencha o círculo */
            width: 100%; /* Preenche a largura do contêiner */
            height: 100%; /* Preenche a altura do contêiner */
        }

        nav.header-funcionario {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 64px; /* altura fixa */
            z-index: 30;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
    </style>
</head>

<body class="bg-animated-gradient">

    <div id="bemVindo">
        <!-- carrego pelo js -->
    </div>       

<div id="controlador" class="hidden">
    
<!-- Navbar -->
<nav class="header-funcionario bg-slate-900 text-white">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between relative bg-slate-900 text-white">
        <!-- Logo -->
        <div class="flex items-center text-lg font-bold">
            <img id="ninja-img" class="w-10 mr-2" src="../../assets/img/ninjaLogo.png" alt="Ninja Control" />
            <span class="logo-text">NINJA CONTROL</span>
        </div>

        <!-- Nome Centralizado -->
        <div class="text-xl font-semibold absolute left-1/2 transform -translate-x-1/2">
            FUNCIONÁRIOS
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
                    <a href="../../views/pontoFuncionario/pontoFuncionario.php" class="flex items-center gap-2 hover:text-gray-500">
                        <i class="fas fa-clock"></i> REGISTRAR PONTO
                    </a>
                </li>
                <li>
                    <a href="../../views/historicoPonto/historicoPonto.php" class="flex items-center gap-2 hover:text-gray-500">
                        <i class="fas fa-history"></i> HISTÓRICO PONTOS
                    </a>
                </li>
                <li>
                    <a href="../../views/dadosEmpresa/dadosEmpresa.php" class="flex items-center gap-2 hover:text-gray-500">
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

    <!-- Modal Overlay -->
    <div id="config-overlay" style="display: none;"></div>

        <!-- Modal Configurações -->
        <div id="config-panel" style="display: none;">
            <div>
                <h2 class="text-2xl font-bold mb-4 text-gray-700">Configurações</h2>
                <form id="config-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nome Completo</label>
                        <input id="inputLocEmpresa" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">CPF</label>
                        <input id="inputRazaoEmpresa" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">RG</label>
                        <input id="inputUsuarioEmpresa" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Data Nascimento</label>
                        <input id="inputCnpj" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Senha Funcionario</label>
                        <input id="inputSenhaEmpresa" type="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
                    </div>
                    <div class="text-right">
                        <button id="btnEnviar" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Salvar</button>
                    </div>
                </form>
                <button id="close-config">&times;</button>
            </div>
        </div>


        <script>
            $(document).ready(function() {
                // Abrir menu hamburguer
                $('#menu-toggle').on('click', function(e) {
                    e.stopPropagation();
                    $('#menu').removeClass('menu-hidden').addClass('menu-visible');
                });
                // Fechar menu hamburguer
                $('#menu-close').on('click', function(e) {
                    e.stopPropagation();
                    $('#menu').removeClass('menu-visible').addClass('menu-hidden');
                });
                // Fechar ao clicar fora do menu
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#menu, #menu-toggle').length) {
                        $('#menu').removeClass('menu-visible').addClass('menu-hidden');
                    }
                });
            });

            $(document).ready(function () {
                $('#config-overlay, #config-panel').hide();

                // Abrir menu hamburguer
                $('#menu-toggle').click(function () {
                    $('#menu').removeClass('menu-hidden').addClass('menu-visible');
                });

                // Fechar menu hamburguer
                $('#menu-close').click(function () {
                    $('#menu').removeClass('menu-visible').addClass('menu-hidden');
                });

                // Abrir modal configurações
                $('#config-link').click(function (e) {
                    e.preventDefault();
                    $('#config-overlay, #config-panel').fadeIn(200);
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
            });

        </script>