<?php @include_once __DIR__ . '/../../config/env.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

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
  <link rel="icon" type="image/png" href="/../../assets/img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/../../assets/img/favicon/favicon.svg" />
  <link rel="shortcut icon" href="/../../assets/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/../../assets/img/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="Ninja Control" />
  <link rel="manifest" href="/../../assets/img/favicon/site.webmanifest" />
  <script>
    window.APP_CONFIG = {
      googleMapsApiKey: <?php echo json_encode(getenv('GOOGLE_MAPS_API_KEY') ?: ''); ?>
    };
  </script>
  <style>
    /* Opcional: deixa a modal centralizada e com animação suave */
.modal-window {
    max-height: 90vh;   /* limita altura em telas pequenas */
    overflow-y: auto;
    transform: translateY(0);
    transition: transform 0.3s ease, opacity 0.3s ease;
}

/* Para animação de entrada */
.modal-show {
    opacity: 1;
    transform: translateY(0);
}

.modal-hide {
    opacity: 0;
    transform: translateY(-20px);
}

    /* Fundo azul-escuro quase preto */
    body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      font-family: 'Noto Sans', sans-serif;
      background: radial-gradient(1200px 800px at 70% 10%, #0b2a36 0%, #071823 40%, #051119 70%, #030a10 100%);
      background-color: #030a10;
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

    /* Canvas de partículas */
    #bg-particles {
      position: fixed;
      inset: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0; /* atrás de tudo */
      pointer-events: none;
    }

    /* Overlay semi-transparente para alertas e loader */
    .overlay-dark { background: rgba(15, 23, 42, 0.6); }

  </style>
</head>

<div class="fixed inset-0 flex items-center justify-center">
  
  <!-- Canvas de partículas de fundo -->
  <canvas id="bg-particles"></canvas>

  <!-- Loader -->
  <div id="loader-container" class="fixed inset-0 overlay-dark hidden flex flex-col items-center justify-center">
    <div class="flex items-center space-x-4 bg-slate-900/80 px-6 py-4 rounded-lg shadow-lg">
      <div class="loader border-4 border-cyan-500 border-t-transparent rounded-full w-8 h-8 animate-spin"></div>
      <span id="loader-message" class="text-white font-semibold">Carregando...</span>
    </div>
  </div>

  <!-- Alertas -->
  <div id="alert-box" style="z-index: 999999 !important;" 
     class="hidden fixed top-4 right-3 sm:right-6 w-11/12 sm:w-auto max-w-sm sm:max-w-md px-3 py-2 sm:px-4 sm:py-3 rounded-lg shadow-lg text-white bg-teal-600 flex items-center gap-3">
    <i id="alert-icon" class="fas fa-info-circle text-sm sm:text-base md:text-2xl"></i>
    <span id="alert-message" class="font-semibold text-xs sm:text-sm md:text-base flex-1 leading-snug"></span>
    <button id="alert-close" class="ml-2 text-white text-lg hover:text-gray-200 focus:outline-none">
      <i class="fas fa-times"></i>
    </button>
  </div>

  <!-- Script do sistema (alertas e loader) -->
  <script>
    // Partículas ciano leves e animadas
    (function(){
      const canvas = document.getElementById('bg-particles');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      let width, height, dpr, particles, lastTs;

      function rand(min, max){ return Math.random() * (max - min) + min; }

      function resize(){
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        width = canvas.clientWidth;
        height = canvas.clientHeight;
        canvas.width = Math.floor(width * dpr);
        canvas.height = Math.floor(height * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        initParticles();
      }

      function initParticles(){
        const area = width * height;
        const density = Math.min(120, Math.max(35, Math.floor(area / 22000)));
        particles = new Array(density).fill(0).map(() => ({
          x: rand(0, width),
          y: rand(0, height),
          r: rand(0.8, 2.0),
          vx: rand(-0.08, 0.08),
          vy: rand(-0.08, 0.08),
          alpha: rand(0.35, 0.85),
          alphaSpeed: rand(0.002, 0.006),
        }));
      }

      function step(ts){
        const dt = lastTs ? Math.min((ts - lastTs) / 16.666, 2) : 1;
        lastTs = ts;
        ctx.clearRect(0, 0, width, height);

        // fundo muito sutil com gradiente radial (leve vinheta)
        const g = ctx.createRadialGradient(width*0.7, height*0.1, 100, width*0.5, height*0.5, Math.max(width, height));
        g.addColorStop(0, 'rgba(0, 255, 255, 0.04)');
        g.addColorStop(1, 'rgba(0, 0, 0, 0)');
        ctx.fillStyle = g;
        ctx.fillRect(0,0,width,height);

        ctx.save();
        ctx.shadowColor = 'rgba(0,255,255,0.5)';
        ctx.shadowBlur = 6;
        for (let p of particles){
          // atualizar
          p.x += p.vx * dt * 1.2;
          p.y += p.vy * dt * 1.2;
          p.alpha += p.alphaSpeed * dt;
          if (p.alpha > 0.9 || p.alpha < 0.3) p.alphaSpeed *= -1;

          // wrap nas bordas
          if (p.x < -5) p.x = width + 5;
          if (p.x > width + 5) p.x = -5;
          if (p.y < -5) p.y = height + 5;
          if (p.y > height + 5) p.y = -5;

          // desenhar
          ctx.beginPath();
          ctx.fillStyle = `rgba(0, 255, 255, ${p.alpha})`;
          ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
          ctx.fill();

          // "emissão" sutil: pequeno traço atrás da partícula
          ctx.beginPath();
          ctx.strokeStyle = `rgba(0, 255, 255, ${p.alpha * 0.35})`;
          ctx.lineWidth = Math.max(0.5, p.r * 0.6);
          ctx.moveTo(p.x, p.y);
          ctx.lineTo(p.x - p.vx * 10, p.y - p.vy * 10);
          ctx.stroke();
        }
        ctx.restore();

        requestAnimationFrame(step);
      }

      window.addEventListener('resize', resize);
      resize();
      requestAnimationFrame(step);
    })();

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







