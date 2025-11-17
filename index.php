<?php @include_once __DIR__ . '/config/env.php'; ?>
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

  <!-- SimpleLightbox CSS e JS -->
  <link href="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.1/dist/simple-lightbox.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.1/dist/simple-lightbox.min.js"></script>

  <!-- Ícones -->
  <link rel="icon" type="image/png" href="/assets/img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/assets/img/favicon/favicon.svg" />
  <link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="Ninja Control" />

  <script>
    window.APP_CONFIG = {
      googleMapsApiKey: <?php echo json_encode(getenv('GOOGLE_MAPS_API_KEY') ?: ''); ?>
    };
  </script>

    <style>
        body {
            background: radial-gradient(1200px 800px at 70% 10%, #0b2a36 0%, #071823 40%, #051119 70%, #030a10 100%);
            background-color: #030a10;
        }
        /* Reveal on scroll */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .65s ease, transform .65s ease; will-change: opacity, transform; }
        .reveal.in-view { opacity: 1; transform: none; }
        .reveal-delay-1 { transition-delay: .08s; }
        .reveal-delay-2 { transition-delay: .16s; }
        .reveal-delay-3 { transition-delay: .24s; }
        .reveal-delay-4 { transition-delay: .32s; }
        .glow-box {
            box-shadow: 0 0 8px #00ffff33, 0 0 16px #00ffff22;
        }
        .glow-text {
            text-shadow: 0 0 4px #00ffff, 0 0 8px #00ffff66;
        }
        .bg-glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(6px);
        }
        .hero-section {
            position: relative;
        }
        .hero-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }
        .overlay {
            background: rgba(15, 23, 42, 0.7);
            position: absolute;
            inset: 0;
            z-index: 1;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 0 12px #00ffff88, 0 4px 20px #00ffff44;
        }

        .hero-section {
            background-image: url('../../assets/img/saibaFotoMobi.png'); /* Mobile padrão */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @media (min-width: 768px) {
            .hero-section {
                background-image: url('../../assets/img/saibaFotoDesk.png'); /* Desktop */
            }
        }

        /* Animações para modal PWA */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-30px);
            }
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.5s ease-out forwards;
        }

        .animate-fadeOutUp {
            animation: fadeOutUp 0.3s ease-in forwards;
        }

        .hero-section {
            background-image: url('../../assets/img/saibaFotoMobi.png'); /* Mobile padrão */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @media (min-width: 768px) {
            .hero-section {
                background-image: url('../../assets/img/saibaFotoDesk.png'); /* Desktop */
            }
        }


    </style>

  <!-- Manifest PWA -->
<link rel="manifest" href="/assets/manifest.json">
<meta name="theme-color" content="#125f7a">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="mobile-web-app-capable" content="yes">
</head>

<!-- Modal de Instalação PWA -->
<div id="installModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 transition-all">
  <div class="bg-slate-900/95 backdrop-blur-md rounded-2xl p-8 max-w-md mx-4 border border-cyan-500/30 shadow-2xl">
    <!-- Cabeçalho -->
    <div class="text-center mb-6">
      <div class="w-16 h-16 bg-cyan-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-download text-cyan-400 text-2xl"></i>
      </div>
      <h3 class="text-2xl font-bold text-white mb-2">Instalar Ninja Control</h3>
      <p class="text-slate-300 text-sm">Instale nosso app para uma experiência melhor!</p>
    </div>

    <!-- Benefícios -->
    <div class="space-y-3 mb-6">
      <div class="flex items-center text-sm text-slate-300">
        <i class="fas fa-mobile-alt text-cyan-400 mr-3"></i>
        <span>Acesso rápido pelo celular</span>
      </div>
      <div class="flex items-center text-sm text-slate-300">
        <i class="fas fa-wifi-slash text-cyan-400 mr-3"></i>
        <span>Funciona offline</span>
      </div>
      <div class="flex items-center text-sm text-slate-300">
        <i class="fas fa-bolt text-cyan-400 mr-3"></i>
        <span>Carregamento mais rápido</span>
      </div>
      <div class="flex items-center text-sm text-slate-300">
        <i class="fas fa-bell text-cyan-400 mr-3"></i>
        <span>Notificações importantes</span>
      </div>
    </div>

    <!-- Botões -->
    <div class="flex gap-3">
      <button onclick="installPWA()" 
              class="flex-1 bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 glow-box">
        <i class="fas fa-download mr-2"></i>
        Instalar
      </button>
      <button onclick="dismissInstallPrompt()" 
              class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300">
        <i class="fas fa-times mr-2"></i>
        Agora não
      </button>
    </div>

    <!-- Informação adicional -->
    <p class="text-xs text-slate-400 text-center mt-4">
      Você pode instalar depois pelo menu do navegador
    </p>
  </div>
</div>

<!-- Modal de Imagem -->
<div id="imageModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 transition-all">
  <div class="relative max-w-4xl w-[90%]">
      <!-- Botão de Fechar -->
      <button id="closeModal" class="absolute -top-5 -right-5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-full p-2 shadow-lg transition-transform hover:scale-110">
          <i class="fas fa-times"></i>
      </button>

      <!-- Imagem dentro do modal -->
      <div class="rounded-2xl overflow-hidden border-4 border-cyan-500/60 shadow-2xl">
          <img id="modalImage" src="" alt="Imagem Ampliada"
               class="w-full object-contain max-h-[85vh] bg-slate-900">
      </div>
  </div>
</div>

<body class="font-sans text-white">

    <!-- Canvas de partículas de fundo -->
    <canvas id="bg-particles" style="position:fixed; inset:0; width:100vw; height:100vh; z-index:0; pointer-events:none;"></canvas>

    <!-- Header com Menu Hamburger -->
    <header class="bg-slate-900/80 fixed w-full z-10 top-0 backdrop-blur-md shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-2 sm:py-3 flex items-center justify-between">
            <!-- Logo e Nome -->
            <div class="flex items-center gap-3 reveal in-view">
                <img src="assets/img/ninjaLogo.png" alt="Logo Ninja" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full shadow-md">
                <span class="text-white font-sans font-extrabold tracking-tight text-lg sm:text-xl md:text-2xl drop-shadow-sm">
                    NINJA CONTROL
                </span>
            </div>

            <!-- Menu Desktop -->
            <nav class="hidden sm:flex">
                <ul class="flex gap-6 text-white font-sans font-semibold text-base md:text-lg">
                    <li><a href="#home" class="hover:text-cyan-400 transition">Home</a></li>
                    <li><a href="#sobre" class="hover:text-cyan-400 transition">Sobre Nós</a></li>
                    <li><a href="#contato" class="hover:text-cyan-400 transition">Contato</a></li>
                    <li><a href="/../../indexLogin.php" class="hover:text-cyan-400 transition">Entrar</a></li>
                    <li><a href="/../../indexLogin.php" class="hover:text-cyan-400 transition">Cadastrar</a></li>
                </ul>
            </nav>

            <!-- Hamburger Mobile -->
            <div class="sm:hidden">
                <button id="menu-btn" class="text-white focus:outline-none">
                    <!-- ícone hamburger -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Mobile -->
        <!-- Mobile Drawer -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 hidden sm:hidden" style="z-index:9;"></div>
        <nav id="mobile-menu" class="fixed top-0 right-0 h-full w-72 max-w-[80%] bg-slate-900/95 backdrop-blur-md shadow-2xl transform translate-x-full transition-transform duration-300 sm:hidden" style="z-index:10;">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-700">
                <span class="text-white font-bold">Menu</span>
                <button id="menu-close" class="text-white" aria-label="Fechar menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="flex flex-col gap-3 p-4 text-white font-sans font-semibold text-base">
                <li><a href="#home" class="hover:text-cyan-400 transition">Home</a></li>
                <li><a href="#sobre" class="hover:text-cyan-400 transition">Sobre Nós</a></li>
                <li><a href="#contato" class="hover:text-cyan-400 transition">Contato</a></li>
                <li><a href="/../../indexLogin.php" class="hover:text-cyan-400 transition">Entrar</a></li>
                <li><a href="/../../indexLogin.php" class="hover:text-cyan-400 transition">Cadastrar</a></li>
            </ul>
        </nav>

        <script>
            // Mobile drawer interactions
            $(function(){
                const $menu = $('#mobile-menu');
                const $overlay = $('#mobile-overlay');
                function openMenu(){
                    $menu.removeClass('translate-x-full');
                    $overlay.removeClass('hidden');
                }
                function closeMenu(){
                    $menu.addClass('translate-x-full');
                    $overlay.addClass('hidden');
                }
                $('#menu-btn').on('click', function(){ openMenu(); });
                $('#menu-close, #mobile-overlay').on('click', function(){ closeMenu(); });
                // Fecha com ESC
                $(document).on('keydown', function(e){ if (e.key === 'Escape') closeMenu(); });
            });
        </script>
    </header>


    <!-- Hero -->
    <section id="home" class="hero-section flex items-center justify-start pt-32 pb-32 px-8 min-h-screen relative" style="z-index:1;">
        <div class="overlay absolute inset-0 bg-black/40 z-0"></div>
        <div class="relative max-w-4xl flex flex-col justify-center z-10">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-4 reveal">Controle de Ponto Moderno</h1>
        <p class="text-lg sm:text-xl md:text-2xl text-slate-200 mb-8 max-w-2xl reveal reveal-delay-1">
            O <span class="font-bold text-cyan-400">Ninja Control</span> é a solução inovadora para gestão de ponto, trazendo tecnologia, segurança e praticidade para empresas e funcionários.
        </p>
        <div class="flex gap-4 flex-wrap reveal reveal-delay-2">
            <a href="/indexLogin.php" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-600 rounded-full font-bold shadow-lg glow-box transition">Entrar</a>
            <a href="/indexLogin.php" class="px-8 py-3 bg-slate-800/70 hover:bg-slate-900/80 border border-cyan-500 rounded-full font-bold shadow-lg glow-box transition">Cadastrar</a>
        </div>
        </div>
    </section>


    <!-- Seção Sobre Nós -->
    <section id="sobre" class="py-24 px-6 bg-slate-900/90" style="z-index:1;">
        <div class="relative max-w-7xl mx-auto z-10">
            <!-- Título acima dos cards -->
            <h2 class="text-4xl font-bold text-cyan-400 glow-text mb-8 text-center reveal">Sobre Nós</h2>

            <!-- Descrição -->
            <div class="flex flex-col md:flex-row items-start gap-12 mb-12">
                <div class="flex-1 reveal">
                    <p class="text-slate-200 mb-6">
                        Somos apaixonados por tecnologia e inovação. O Ninja Control nasceu para revolucionar o controle de ponto, oferecendo uma plataforma segura, rápida e fácil de usar.
                    </p>
                </div>
            </div>

            <!-- Cards de Benefícios -->
            <div class="flex flex-wrap gap-8 md:flex-row sm:flex-col lg:flex-row">
                <div class="bg-slate-900/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex-1 min-w-[280px] reveal">
                    <i class="fas fa-shield-alt text-cyan-400 text-3xl mb-2"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Segurança</h3>
                    <p class="text-slate-300">Dados protegidos e autenticação avançada para garantir total segurança.</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex-1 min-w-[280px] reveal reveal-delay-1">
                    <i class="fas fa-bolt text-cyan-400 text-3xl mb-2"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Agilidade</h3>
                    <p class="text-slate-300">Processos rápidos e automatizados para facilitar o dia a dia.</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex-1 min-w-[280px] reveal reveal-delay-2">
                    <i class="fas fa-users text-cyan-400 text-3xl mb-2"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Facilidade</h3>
                    <p class="text-slate-300">Interface intuitiva e moderna, pensada para todos os públicos.</p>
                </div>
            </div>
        </div>
    </section >

  <!-- Seção de Prints do Sistema -->
  <section class="py-24 px-6 bg-slate-900/90" style="z-index:1;">
      <div class="max-w-6xl mx-auto text-center mb-12">
          <h2 class="text-3xl sm:text-4xl font-bold text-cyan-400 glow-text mb-4 reveal">Veja o Ninja Control em Ação</h2>
          <p class="text-slate-300 text-lg reveal reveal-delay-1">Confira abaixo algumas telas do nosso sistema em funcionamento.</p>
      </div>

      <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
          <!-- Imagem 1 (horizontal - maior) -->
          <div class="flex justify-center bg-slate-800/80 rounded-2xl overflow-hidden glow-box hover:scale-[1.02] transition-all duration-300 shadow-lg border-4 border-cyan-500/60 p-2 w-full max-w-[95%] mx-auto reveal">
              <a href="/../../assets/img/painelPrint.png" class="block w-full h-full">
                  <img src="/../../assets/img/painelPrint.png" alt="Tela do Sistema 1" class="w-full h-full object-cover">
              </a>
          </div>

          <!-- Imagem 2 (vertical - menor) -->
          <div class="flex justify-center bg-slate-800/80 rounded-2xl overflow-hidden glow-box hover:scale-[1.02] transition-all duration-300 shadow-lg border-4 border-cyan-500/60 p-2 w-full max-w-[80%] mx-auto reveal reveal-delay-1">
              <a href="/../../assets/img/pontoPrint.png" class="block w-full h-full">
                  <img src="/../../assets/img/pontoPrint.png" alt="Tela do Sistema 2" class="w-full h-full object-cover">
              </a>
          </div>
      </div>
  </section>

    <!-- Seção de Funcionalidades -->
    <section class="py-24 px-8 bg-slate-900/80 backdrop-blur-md" style="z-index:1;">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-cyan-400 glow-text mb-4 reveal">Nossas Funcionalidades!</h2>
            <p class="text-slate-300 text-lg reveal reveal-delay-1">Confira abaixo as qualidades que garantimos para você!</p>
        </div>

        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="bg-slate-800/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex flex-col items-center text-center reveal">
                <i class="fas fa-cogs text-cyan-400 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-2">Automação</h3>
                <p class="text-slate-300">Automatize tarefas repetitivas e ganhe tempo na gestão de ponto.</p>
            </div>
            <div class="bg-slate-800/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex flex-col items-center text-center reveal reveal-delay-1">
                <i class="fas fa-chart-line text-cyan-400 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-2">Relatórios</h3>
                <p class="text-slate-300">Acompanhe métricas e dados em tempo real com dashboards intuitivos.</p>
            </div>
            <div class="bg-slate-800/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex flex-col items-center text-center reveal reveal-delay-2">
                <i class="fas fa-mobile-alt text-cyan-400 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-2">Mobile</h3>
                <p class="text-slate-300">Acesse todas as funções do Ninja Control pelo celular de forma prática.</p>
            </div>
        </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="py-24 px-8 relative" style="z-index:1;">
        <div class="bg-slate-800/80 absolute inset-0 z-0 rounded-xl"></div>
        <div class="relative max-w-3xl mx-auto flex flex-col gap-6 z-10">
            <h2 class="text-3xl font-bold text-cyan-400 glow-text reveal">Fale Conosco</h2>
            <p class="text-slate-200 reveal reveal-delay-1">Tem dúvidas ou quer saber mais? Entre em contato conosco!</p>

            <a href="mailto:contato@ninjacontrol.com" class="inline-block px-8 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-bold rounded-full shadow-lg glow-box transition reveal reveal-delay-2">contato@ninjacontrol.com</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800/80 py-6 backdrop-blur-md" style="z-index:1;">
        <div class="max-w-7xl mx-auto px-4 flex flex-col items-center gap-4 text-center">
            <div class="flex items-center gap-2">
                <img src="assets/img/ninjaLogo.png" alt="Logo Ninja" class="w-8 h-8">
                <span class="font-bold text-white">Ninja Control</span>
            </div>

            <!-- Ícones centralizados -->
            <div class="flex gap-6 justify-center">
                <a href="https://ninjaControlOficial" target="_blank" class="hover:text-cyan-400 glow-text" aria-label="Instagram">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
                <a href="mailto:ninjaControlOficial" class="hover:text-cyan-400 glow-text" aria-label="Email">
                    <i class="fas fa-envelope text-xl"></i>
                </a>
                <a href="https://ninjaControlOficial" target="_blank" class="hover:text-cyan-400 glow-text" aria-label="TikTok">
                    <i class="fab fa-tiktok text-xl"></i>
                </a>
            </div>

            <span class="text-sm">&copy; 2025 Ninja Control. Todos os direitos reservados.</span>
        </div>
    </footer>

    <!-- Service Worker Registration -->
  <script>
let deferredPrompt;
let isPWAInstalled = false;

// Detectar iOS
function isIOS() {
  return /iphone|ipad|ipod/i.test(navigator.userAgent);
}

// Detectar se está instalado (iOS + Android)
function isInStandaloneMode() {
  return (window.matchMedia('(display-mode: standalone)').matches) ||
         (window.navigator.standalone === true);
}

// Bloquear tudo no iOS (APPLE não permite PWA instalar via JS)
const isIOSDevice = isIOS();
if (isIOSDevice && !isInStandaloneMode()) {
  console.log("📱 iOS detectado — instalação via beforeinstallprompt bloqueada.");
} else {

  // --- ANDROID NORMAL ---

  // beforeinstallprompt → pode instalar
  window.addEventListener('beforeinstallprompt', (e) => {
    console.log('PWA pode ser instalado');
    e.preventDefault();
    deferredPrompt = e;
    showInstallPrompt();
  });
}

// Evento quando já estiver instalado
window.addEventListener('appinstalled', () => {
  console.log('PWA foi instalado');
  isPWAInstalled = true;
  hideInstallPrompt();
});

// Detectar standalone
if (isInStandaloneMode()) {
  console.log('Rodando como PWA');
  isPWAInstalled = true;
}

function showInstallPrompt() {
  if (isPWAInstalled || isIOSDevice) return;

  setTimeout(() => {
    const installModal = document.getElementById('installModal');
    if (installModal && !isPWAInstalled) {
      installModal.classList.remove('hidden');
      installModal.classList.add('animate-fadeInDown');
    }
  }, 5000);
}

function hideInstallPrompt() {
  const installModal = document.getElementById('installModal');
  if (installModal) {
    installModal.classList.add('animate-fadeOutUp');
    setTimeout(() => {
      installModal.classList.add('hidden');
    }, 300);
  }
}

async function installPWA() {
  if (!deferredPrompt) {
    console.log('PWA não pode ser instalado');
    return;
  }

  deferredPrompt.prompt();

  const { outcome } = await deferredPrompt.userChoice;
  console.log(`PWA instalação: ${outcome}`);

  deferredPrompt = null;
  hideInstallPrompt();
}

function dismissInstallPrompt() {
  hideInstallPrompt();
  localStorage.setItem('pwa-install-dismissed', Date.now());
}

// Teste manual
function testModal() {
  if (isIOSDevice) {
    console.log("❌ No iOS o modal nunca aparecerá.");
    return;
  }

  const installModal = document.getElementById('installModal');
  if (installModal) {
    installModal.classList.remove('hidden');
    installModal.classList.add('animate-fadeInDown');
  }
}

function clearPWAStorage() {
  localStorage.removeItem('pwa-install-dismissed');
  console.log('🗑️ localStorage limpo - modal aparecerá novamente');
}

window.testModal = testModal;
window.clearPWAStorage = clearPWAStorage;

// Ao carregar página
window.addEventListener('load', () => {
  console.log('🚀 Página carregada');

  if (isIOSDevice && !isInStandaloneMode()) {
    console.log("📱 iOS — Modal de instalação bloqueado");
    return;
  }

  if (isPWAInstalled) return;

  // Verificar se rejeitado recentemente
  const dismissed = localStorage.getItem('pwa-install-dismissed');
  if (dismissed) {
    const diff = (Date.now() - parseInt(dismissed)) / (1000 * 60 * 60);
    if (diff < 24) {
      return;
    }
  }

  // Mostrar depois de 5s (ANDROID)
  setTimeout(() => {
    const installModal = document.getElementById('installModal');
    if (installModal && !isPWAInstalled) {
      installModal.classList.remove('hidden');
      installModal.classList.add('animate-fadeInDown');
    }
  }, 5000);
});

  </script>

  <script>
    // Reveal on scroll com IntersectionObserver
    (function(){
      var els = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
      if (!('IntersectionObserver' in window) || els.length === 0) {
        // Fallback: mostra tudo
        els.forEach(function(el){ el.classList.add('in-view'); });
        return;
      }
      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
          if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            io.unobserve(entry.target);
          }
        });
      }, { rootMargin: '0px 0px -10% 0px', threshold: 0.12 });
      els.forEach(function(el){ io.observe(el); });
    })();
    // Partículas ciano leves e animadas
    (function(){
      var canvas = document.getElementById('bg-particles');
      if (!canvas) return;
      var ctx = canvas.getContext('2d');
      var width, height, dpr, particles, lastTs;

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
        var area = width * height;
        var density = Math.min(120, Math.max(35, Math.floor(area / 22000)));
        particles = Array(density).fill(0).map(function(){
          return {
            x: rand(0, width),
            y: rand(0, height),
            r: rand(0.8, 2.0),
            vx: rand(-0.08, 0.08),
            vy: rand(-0.08, 0.08),
            alpha: rand(0.35, 0.85),
            alphaSpeed: rand(0.002, 0.006)
          };
        });
      }

      function step(ts){
        var dt = lastTs ? Math.min((ts - lastTs) / 16.666, 2) : 1;
        lastTs = ts;
        ctx.clearRect(0, 0, width, height);

        var g = ctx.createRadialGradient(width*0.7, height*0.1, 100, width*0.5, height*0.5, Math.max(width, height));
        g.addColorStop(0, 'rgba(0, 255, 255, 0.04)');
        g.addColorStop(1, 'rgba(0, 0, 0, 0)');
        ctx.fillStyle = g;
        ctx.fillRect(0,0,width,height);

        ctx.save();
        ctx.shadowColor = 'rgba(0,255,255,0.5)';
        ctx.shadowBlur = 6;
        for (var i=0;i<particles.length;i++){
          var p = particles[i];
          p.x += p.vx * dt * 1.2;
          p.y += p.vy * dt * 1.2;
          p.alpha += p.alphaSpeed * dt;
          if (p.alpha > 0.9 || p.alpha < 0.3) p.alphaSpeed *= -1;

          if (p.x < -5) p.x = width + 5;
          if (p.x > width + 5) p.x = -5;
          if (p.y < -5) p.y = height + 5;
          if (p.y > height + 5) p.y = -5;

          ctx.beginPath();
          ctx.fillStyle = 'rgba(0, 255, 255, ' + p.alpha + ')';
          ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
          ctx.fill();

          ctx.beginPath();
          ctx.strokeStyle = 'rgba(0, 255, 255, ' + (p.alpha * 0.35) + ')';
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
    // Inicializa SimpleLightbox
    var lightbox = new SimpleLightbox('a', { captionsData: 'alt', captionDelay: 250 });

    // Registrar Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
        .then(() => console.log('Service Worker registrado'))
        .catch(err => console.error('Erro no SW:', err));
    }

    // Script AES: execute **após SW registrar**
    window.addEventListener('load', () => {
      // slowAES.js deve estar em /aes.js
      if (typeof slowAES !== 'undefined') {
        function toNumbers(d){var e=[];d.replace(/(..)/g,function(d){e.push(parseInt(d,16))});return e}
        function toHex(){for(var d=[],d=1==arguments.length&&arguments[0].constructor==Array?arguments[0]:arguments,e="",f=0;f<d.length;f++)e+=(16>d[f]?"0":"")+d[f].toString(16);return e.toLowerCase()}
        var a=toNumbers("f655ba9d09a112d4968c63579db590b4"),
            b=toNumbers("98344c2eee86c3994890592585b49f80"),
            c=toNumbers("4e3e639fc310e64a1f63d033f8dac706");
        document.cookie="__test="+toHex(slowAES.decrypt(c,2,a,b))+"; max-age=21600; expires=Thu, 31-Dec-37 23:55:55 GMT; path=/";
        location.href="/backend/backend.php";
      }
    });
  </script>

</body>
</html>