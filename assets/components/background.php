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
  <link rel="icon" type="image/x-icon" href="../assets/img/icons/ninja_lock_icon.ico">

  <!-- VANTA.TOPOLOGY -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.0/p5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.topology.min.js"></script>

  <style>
    @keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 10px #00ffff20; }
    50% { box-shadow: 0 0 25px #00ffff50; }
    }

    .glow-box {
    animation: pulse-glow 3s infinite ease-in-out;
    }

    /* Alert animations */
    #alert-box {
        --animate-duration: 0.5s;
    }
    
    #alert-box.animate__fadeInDown {
        animation-duration: 0.3s !important;
    }
    
    #alert-box.animate__fadeOutUp {
        animation-duration: 0.2s !important;
    }

    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      font-family: 'Noto Sans', sans-serif;
    }

    /* Fundo animado Vanta.js */
    #fundo {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: -1;
      background-color: #10182d;
    }

    /* Loader */
    #loader-container { z-index: 9999 !important; }
    .loader { border-radius: 50%; }

    /* Botão com gradiente animado */
    .bgBtn {
      background: linear-gradient(45deg, rgb(0, 0, 0), rgb(115, 114, 115), rgb(0, 0, 0));
      background-size: 400% 400%;
      animation: gradientAnim 8s ease infinite;
    }
    @keyframes gradientAnim {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

  </style>
</head>

<body class="flex items-center justify-center">

  <!-- Fundo animado -->
  <div id="fundo"></div>

  <!-- Loader -->
  <div id="loader-container" class="fixed inset-0 bg-black bg-opacity-50 hidden flex flex-col items-center justify-center">
    <div class="flex items-center space-x-4 bg-slate-900 px-6 py-4 rounded-lg shadow-lg">
      <div class="loader border-4 border-cyan-500 border-t-transparent rounded-full w-8 h-8 animate-spin"></div>
      <span id="loader-message" class="text-white font-semibold">Carregando...</span>
    </div>
  </div>

  <!-- Alertas -->
<div id="alert-box" class="hidden fixed top-6 left-6 right-6 max-w-sm mx-auto px-4 py-3 rounded-lg shadow-lg text-white bg-teal-600 flex items-center gap-3 z-50">
  <i id="alert-icon" class="fas fa-info-circle text-xl md:text-2xl"></i>
  <span id="alert-message" class="font-semibold text-sm md:text-base flex-1"></span>
  <button id="alert-close" class="ml-2 text-white text-lg hover:text-gray-200 focus:outline-none">
    <i class="fas fa-times"></i>
  </button>
</div>


  <!-- Scripts -->
  <script>
    // Inicializa o fundo VANTA.TOPOLOGY
    document.addEventListener("DOMContentLoaded", function () {
      VANTA.TOPOLOGY({
        el: "#fundo",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0x327182,          // linhas em ciano
        backgroundColor: 0x121a25, // fundo escuro
        points: 11.0,
        maxDistance: 22.0,
        spacing: 16.0
      });
    });
  </script>

  <!-- Script do sistema (alertas e loader) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            // Reset animations and clear timeout
            clearTimeout(timeoutId);
            $alertBox
                .removeClass('animate__fadeInDown animate__fadeOutUp animate__animated')
                .addClass('animate__animated');
            
            void $alertBox[0].offsetWidth; // Force DOM reflow

            // Update content
            $alertMessage.text(message);
            $alertBox
                .removeClass('bg-green-600 bg-red-600 bg-yellow-500 bg-teal-600')
                .addClass(alertType.bg);

            $alertIcon.attr('class', `${alertType.icon} text-xl`);

            // Show with entrance animation
            $alertBox
                .removeClass('hidden')
                .addClass('animate__fadeInDown');

            // Set auto-hide timer
            timeoutId = setTimeout(() => {
                $alertBox
                    .removeClass('animate__fadeInDown')
                    .addClass('animate__fadeOutUp');

                // Hide after animation completes
                setTimeout(() => {
                    $alertBox
                        .addClass('hidden')
                        .removeClass('animate__fadeOutUp animate__animated');
                }, 300);
            }, 3000);
        }

        // Botão de fechar manualmente
        $alertClose.on('click', () => {
            clearTimeout(timeoutId);
            $alertBox.removeClass('animate__fadeInDown').addClass('animate__fadeOutUp');
            $alertBox.one('animationend', () => {
            $alertBox.addClass('hidden').removeClass('animate__fadeOutUp animate__animated');
            });
        });

        // Expor para uso global
        window.showAlert = showAlert;


        // Loader opcional
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
