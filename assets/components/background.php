<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ninja Control</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Inputmask -->
  <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100..900&display=swap" rel="stylesheet">

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <!-- Ícone -->
  <link rel="icon" type="image/png" href="/assets/img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/assets/img/favicon/favicon.svg" />
  <link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="Ninja Control" />
  <link rel="manifest" href="/assets/manifest.json" />
  <meta name="theme-color" content="#125f7a">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="mobile-web-app-capable" content="yes">
  <style>
    /* Gradiente principal de fundo com ofuscamento preto */
    body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      font-family: 'Noto Sans', sans-serif;
      background: 
        linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), /* camada preta semitransparente */
        linear-gradient(180deg, #125f7a, #94e4e6, #125f7a); /* gradiente original */
    }

    @keyframes pulse-glow {
      0%, 100% { box-shadow: 0 0 10px #00ffff20; }
      50% { box-shadow: 0 0 25px #00ffff50; }
    }

    .glow-box {
      animation: pulse-glow 3s infinite ease-in-out;
    }

    /* Alert animations */
    #alert-box { --animate-duration: 0.5s; }
    #alert-box.animate__fadeInDown { animation-duration: 0.3s !important; }
    #alert-box.animate__fadeOutUp { animation-duration: 0.2s !important; }

    html, body { height: 100%; }

    /* Loader */
    #loader-container { z-index: 9999 !important; }
    .loader { border-radius: 50%; }

    /* Botão com gradiente animado */
    .bgBtn {
      background: linear-gradient(45deg, rgb(0,0,0), rgb(115,114,115), rgb(0,0,0));
      background-size: 400% 400%;
      animation: gradientAnim 8s ease infinite;
    }
    @keyframes gradientAnim {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    /* Overlay semi-transparente para alertas e loader */
    .overlay-dark { background: rgba(15, 23, 42, 0.6); }

  </style>
</head>

<body class="relative flex items-center justify-center">
  
  <!-- Background com gradiente e opacidade via CSS -->
  <div class=""></div>

  <!-- Loader -->
  <div id="loader-container" class="fixed inset-0 overlay-dark hidden flex flex-col items-center justify-center">
    <div class="flex items-center space-x-4 bg-slate-900/80 px-6 py-4 rounded-lg shadow-lg">
      <div class="loader border-4 border-cyan-500 border-t-transparent rounded-full w-8 h-8 animate-spin"></div>
      <span id="loader-message" class="text-white font-semibold">Carregando...</span>
    </div>
  </div>

  <!-- Alertas -->
  <div id="alert-box" 
      class="hidden fixed top-6 right-6 w-[90%] sm:w-auto max-w-sm px-4 py-3 rounded-lg shadow-lg 
            text-white bg-teal-600 flex items-center gap-3 z-[9999] animate-slide-in-right">
    <i id="alert-icon" class="fas fa-info-circle text-xl md:text-2xl"></i>
    <span id="alert-message" class="font-semibold text-sm md:text-base flex-1"></span>
    <button id="alert-close" class="ml-2 text-white text-lg hover:text-gray-200 focus:outline-none">
      <i class="fas fa-times"></i>
    </button>
  </div>


  <!-- Script do sistema (alertas e loader) -->
  <script>
    $(function () {
        const $alertBox = $('#alert-box');
        const $alertIcon = $('#alert-icon');
        const $alertMessage = $('#alert-message');
        const $alertClose = $('#alert-close');

        const types = {
            success: { bg: 'bg-green-600', icon: 'fas fa-check-circle' },
            error: { bg: 'bg-red-600', icon: 'fas fa-exclamation-circle' },
            warning: { bg: 'bg-yellow-500', icon: 'fas fa-exclamation-triangle' },
            info: { bg: 'bg-teal-600', icon: 'fas fa-info-circle' }
        };

        let timeoutId;

        function showAlert(message, type = 'info') {
            const alertType = types[type] || types.info;

            clearTimeout(timeoutId);
            $alertBox.removeClass('animate__fadeInDown animate__fadeOutUp animate__animated').addClass('animate__animated');
            void $alertBox[0].offsetWidth;

            $alertMessage.text(message);
            $alertBox.removeClass('bg-green-600 bg-red-600 bg-yellow-500 bg-teal-600').addClass(alertType.bg);
            $alertIcon.attr('class', `${alertType.icon} text-xl`);

            $alertBox.removeClass('hidden').addClass('animate__fadeInDown');

            timeoutId = setTimeout(() => {
                $alertBox.removeClass('animate__fadeInDown').addClass('animate__fadeOutUp');
                setTimeout(() => {
                    $alertBox.addClass('hidden').removeClass('animate__fadeOutUp animate__animated');
                }, 300);
            }, 3000);
        }

        $alertClose.on('click', () => {
            clearTimeout(timeoutId);
            $alertBox.removeClass('animate__fadeInDown').addClass('animate__fadeOutUp');
            $alertBox.one('animationend', () => {
                $alertBox.addClass('hidden').removeClass('animate__fadeOutUp animate__animated');
            });
        });

        window.showAlert = showAlert;

        window.loaderM = function (mensagem, estado) {
            const $loaderContainer = $('#loader-container');
            const $loaderMessage = $('#loader-message');

            if (estado) {
                $loaderMessage.text(mensagem || 'Carregando...');
                $loaderContainer.removeClass('hidden');
            } else {
                $loaderContainer.addClass('hidden');
            }
        };
    });
  </script>


</body>
</html>







