<?php
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();
require_once('assets/components/background.php')
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ninja Control</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/ninjaLogo.png">
    <link rel="icon" type="image/png" sizes="512x512" href="assets/img/ninjaLogo.png">
    <link rel="icon" href="/assets/img/icons/ninjaLogo16.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/assets/img/icons/ninjaLogo16.ico" type="image/x-icon">
    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
          navigator.serviceWorker.register('/service-worker.js');
        });
      }
    </script>

    <style>
        /* Animações de entrada e saída */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOutUp {
            0% {
                opacity: 1;
                transform: translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateY(-40px);
            }
        }

        /* Classes utilitárias */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .fade-out-up {
            animation: fadeOutUp 0.8s ease-in forwards;
        }

    @media (min-width: 640px) {
        #login-system2 {
            height: 520px !important; /* aumenta no desktop */
            max-width: 420px !important; /* mais largo em telas maiores */
        }
    }

    @media (max-width: 639px) {
        #login-system2 {
            height: 360px !important; /* reduz no mobile */
            max-width: 320px !important;
        }
    }
    </style>
</head>
    <!-- Mensagem de Boas-Vindas -->
    <div id="welcome-message" class="welcome-message text-center opacity-0 translate-y-10">
        <p class="text-lg sm:text-2xl md:text-3xl text-white mb-3">SEJA BEM-VINDO(A)</p>
        <h1 class="text-2xl sm:text-4xl md:text-5xl text-white font-sans font-bold">
            <strong>NINJA CONTROL</strong>
        </h1>
    </div>

    <div id="login-system2"
        class="hidden w-full max-w-sm sm:max-w-md rounded-3xl shadow-2xl relative mx-auto overflow-hidden"
        style="height: 400px; 
            background-image: url('/../../assets/img/saibaFoto.png'); 
            background-size: cover; 
            background-position: center top; 
            background-repeat: no-repeat;">

        <!-- Overlay escuro suave -->
        <div class="absolute inset-0 bg-black/30 rounded-3xl"></div>

        <!-- Botões dentro da imagem -->
        <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 px-5 flex flex-col gap-3 w-11/12 sm:w-9/12">

            <!-- Botão Funcionário -->
            <a href="views/loginFuncionario/loginFuncionario.php"
            class="w-full text-center font-semibold py-2.5 sm:py-3 rounded-xl text-base sm:text-lg
                    text-[#3BA3E6] border border-[#3BA3E6] hover:bg-[#3BA3E6] hover:text-white 
                    transition-all duration-300 ease-in-out shadow-md
                    flex justify-center items-center backdrop-blur-sm">
                SOU FUNCIONÁRIO
            </a>

            <!-- Botão Empresa -->
            <a href="views/loginEmpresa/loginEmpresa.php"
            class="w-full text-center font-semibold py-2.5 sm:py-3 rounded-xl text-base sm:text-lg
                    text-[#3BA3E6] border border-[#3BA3E6] hover:bg-[#3BA3E6] hover:text-white 
                    transition-all duration-300 ease-in-out shadow-md
                    flex justify-center items-center backdrop-blur-sm">
                SOU EMPRESA
            </a>
        </div>
    </div>

    <script>
        // Seleciona a div
        const welcome = document.getElementById('welcome-message');

        // Adiciona animação de entrada
        window.addEventListener('load', () => {
            welcome.classList.add('fade-in-up');
        });

        // Exemplo de animação de saída após 3 segundos
        setTimeout(() => {
            welcome.classList.remove('fade-in-up');
            welcome.classList.add('fade-out-up');
            setTimeout(() => {
                welcome.style.display = 'none';
                document.getElementById('login-system2').classList.remove('hidden');
                document.getElementById('login-system').classList.remove('hidden');
            }, 800); // Tempo da animação de saída
        }, 3000); 
    </script>

</body>
</html>
