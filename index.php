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
</head>
    <!-- Mensagem de Boas-Vindas -->
    <div id="welcome-message" class="welcome-message text-center">
        <p class="text-lg sm:text-2xl md:text-3xl text-white mb-3">SEJA BEM-VINDO(A)</p>
        <h1 class="text-2xl sm:text-4xl md:text-5xl text-white font-sans font-bold"><strong>NINJA CONTROL</strong></h1>
    </div>

    <!-- Imagem com animação -->
    <div id="animacaoCena" class="animacaoCena hidden">
        <img id="ninja-img" class="mx-auto w-60" src="../assets/img/ninjaLogo.png" alt="Ninja Control">
    </div>

    <div id="login-system2" class="hidden">
        <img id="ninja-img" class="mx-auto w-20" src="../assets/img/ninjaLogo.png" alt="Ninja Control">
        <h1 class="text-center text-white font-bold text-3xl mb-2">NINJA CONTROL</h1>
        <div id="login-system" class="w-[90%] sm:w-96 bg-white bg-opacity-90 shadow-xl rounded-2xl p-4 sm:p-8 hidden mx-auto">
            <!-- Botões -->
            <div class="flex flex-col space-y-3">
                <a href="views/loginFuncionario/loginFuncionario.php" class="bgBtn w-[95%] sm:w-full bg-gray-700 text-white py-2 sm:py-3 rounded-md text-center transition sm:hover:scale-110 mx-auto block">
                <STRONG>FUNCIONÁRIO</STRONG> <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="views/loginEmpresa/loginEmpresa.php" class="bgBtn w-[95%] sm:w-full bg-gray-700 text-white py-2 sm:py-3 rounded-md text-center transition sm:hover:scale-110 mx-auto block">
                <STRONG>EMPRESA</STRONG> <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById("welcome-message").classList.add("hidden");

            document.getElementById("animacaoCena").classList.remove("hidden");
            document.getElementById("ninja-img").classList.add("ninja-image-animate");
        }, 2000);

        setTimeout(() => {
            document.getElementById("animacaoCena").classList.add("hidden");
            document.getElementById("login-system").classList.remove("hidden");
            document.getElementById("login-system2").classList.remove("hidden");
        }, 4000);    
    </script>

</body>
</html>
