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

  <!-- SimpleLightbox CSS -->
  <link href="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.1/dist/simple-lightbox.min.css" rel="stylesheet">

  <!-- SimpleLightbox JS -->
  <script src="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.1/dist/simple-lightbox.min.js"></script>


  <!-- Ícone -->
  <link rel="icon" type="image/png" href="/../../assets/img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/../../assets/img/favicon/favicon.svg" />
  <link rel="shortcut icon" href="/../../assets/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/../../assets/img/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="Ninja Control" />
  <link rel="manifest" href="/../../assets/img/favicon/site.webmanifest" />
    <style>
        body {
            background-color: #0f172a; /* fundo escuro base */
        }
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

    </style>
</head>
<body class="font-sans text-white">

    <!-- Header com Menu Hamburger -->
    <header class="bg-slate-900/80 fixed w-full z-10 top-0 backdrop-blur-md shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-2 sm:py-3 flex items-center justify-between">
            <!-- Logo e Nome -->
            <div class="flex items-center gap-3">
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
        <div id="mobile-menu" class="hidden sm:hidden bg-slate-900/95">
            <ul class="flex flex-col gap-3 p-4 text-white font-sans font-semibold text-base">
                <li><a href="#home" class="hover:text-cyan-400 transition">Home</a></li>
                <li><a href="#sobre" class="hover:text-cyan-400 transition">Sobre Nós</a></li>
                <li><a href="#contato" class="hover:text-cyan-400 transition">Contato</a></li>
                <li><a href="/../../indexLogin.php" class="hover:text-cyan-400 transition">Entrar</a></li>
                <li><a href="/../../indexLogin.php" class="hover:text-cyan-400 transition">Cadastrar</a></li>
            </ul>
        </div>

        <script>
            // Toggle menu mobile
            const btn = document.getElementById('menu-btn');
            const menu = document.getElementById('mobile-menu');

            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        </script>
    </header>


    <!-- Home / Hero -->
    <section id="home" class="hero-section flex items-center justify-start pt-32 pb-32 px-8 min-h-screen relative">
        <div class="overlay absolute inset-0 bg-black/40 z-0"></div>
        <div class="relative max-w-4xl flex flex-col justify-center z-10">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-4">Controle de Ponto Moderno</h1>
            <p class="text-lg sm:text-xl md:text-2xl text-slate-200 mb-8 max-w-2xl">
                O <span class="font-bold text-cyan-400">Ninja Control</span> é a solução inovadora para gestão de ponto, trazendo tecnologia, segurança e praticidade para empresas e funcionários.
            </p>
            <div class="flex gap-4 flex-wrap">
                <a href="/../../indexLogin.php" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-600 rounded-full font-bold shadow-lg glow-box transition">Entrar</a>
                <a href="/../../indexLogin.php" class="px-8 py-3 bg-slate-800/70 hover:bg-slate-900/80 border border-cyan-500 rounded-full font-bold shadow-lg glow-box transition">Cadastrar</a>
            </div>
        </div>
    </section>


    <!-- Seção Sobre Nós -->
    <section id="sobre" class="py-24 px-8 relative">
        <div class="relative max-w-7xl mx-auto z-10">
            <!-- Título acima dos cards -->
            <h2 class="text-4xl font-bold text-cyan-400 glow-text mb-8 text-center">Sobre Nós</h2>

            <!-- Descrição -->
            <div class="flex flex-col md:flex-row items-start gap-12 mb-12">
                <div class="flex-1">
                    <p class="text-slate-200 mb-6">
                        Somos apaixonados por tecnologia e inovação. O Ninja Control nasceu para revolucionar o controle de ponto, oferecendo uma plataforma segura, rápida e fácil de usar.
                    </p>
                </div>
            </div>

            <!-- Cards de Benefícios -->
            <div class="flex flex-wrap gap-8 md:flex-row sm:flex-col lg:flex-row">
                <div class="bg-slate-900/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex-1 min-w-[280px]">
                    <i class="fas fa-shield-alt text-cyan-400 text-3xl mb-2"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Segurança</h3>
                    <p class="text-slate-300">Dados protegidos e autenticação avançada para garantir total segurança.</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex-1 min-w-[280px]">
                    <i class="fas fa-bolt text-cyan-400 text-3xl mb-2"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Agilidade</h3>
                    <p class="text-slate-300">Processos rápidos e automatizados para facilitar o dia a dia.</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex-1 min-w-[280px]">
                    <i class="fas fa-users text-cyan-400 text-3xl mb-2"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Facilidade</h3>
                    <p class="text-slate-300">Interface intuitiva e moderna, pensada para todos os públicos.</p>
                </div>
            </div>
        </div>
    </section>

  <!-- Seção de Prints do Sistema -->
  <section class="py-24 px-6 bg-slate-900/90">
      <div class="max-w-6xl mx-auto text-center mb-12">
          <h2 class="text-3xl sm:text-4xl font-bold text-cyan-400 glow-text mb-4">Veja o Ninja Control em Ação</h2>
          <p class="text-slate-300 text-lg">Confira abaixo algumas telas do nosso sistema em funcionamento.</p>
      </div>

      <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
          <!-- Imagem 1 (horizontal - maior) -->
          <div class="flex justify-center bg-slate-800/80 rounded-2xl overflow-hidden glow-box hover:scale-[1.02] transition-all duration-300 shadow-lg border-4 border-cyan-500/60 p-2 w-full max-w-[95%] mx-auto">
              <a href="/../../assets/img/painelPrint.png" class="block w-full h-full">
                  <img src="/../../assets/img/painelPrint.png" alt="Tela do Sistema 1" class="w-full h-full object-cover">
              </a>
          </div>

          <!-- Imagem 2 (vertical - menor) -->
          <div class="flex justify-center bg-slate-800/80 rounded-2xl overflow-hidden glow-box hover:scale-[1.02] transition-all duration-300 shadow-lg border-4 border-cyan-500/60 p-2 w-full max-w-[80%] mx-auto">
              <a href="/../../assets/img/pontoPrint.png" class="block w-full h-full">
                  <img src="/../../assets/img/pontoPrint.png" alt="Tela do Sistema 2" class="w-full h-full object-cover">
              </a>
          </div>
      </div>
  </section>

    <!-- Seção de Funcionalidades -->
    <section class="py-24 px-8 bg-slate-900/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-cyan-400 glow-text mb-4">Nossas Funcionalidades!</h2>
            <p class="text-slate-300 text-lg">Confira abaixo as qualidades que garantimos para você!</p>
        </div>

        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="bg-slate-800/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex flex-col items-center text-center">
                <i class="fas fa-cogs text-cyan-400 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-2">Automação</h3>
                <p class="text-slate-300">Automatize tarefas repetitivas e ganhe tempo na gestão de ponto.</p>
            </div>
            <div class="bg-slate-800/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex flex-col items-center text-center">
                <i class="fas fa-chart-line text-cyan-400 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-2">Relatórios</h3>
                <p class="text-slate-300">Acompanhe métricas e dados em tempo real com dashboards intuitivos.</p>
            </div>
            <div class="bg-slate-800/70 border border-cyan-500 rounded-xl p-6 glow-box card-hover transition flex flex-col items-center text-center">
                <i class="fas fa-mobile-alt text-cyan-400 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-2">Mobile</h3>
                <p class="text-slate-300">Acesse todas as funções do Ninja Control pelo celular de forma prática.</p>
            </div>
        </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="py-24 px-8 relative">
        <div class="bg-slate-800/80 absolute inset-0 z-0 rounded-xl" style="background: url('assets/img/contato-fundo.jpg') center/cover no-repeat;"></div>
        <div class="relative max-w-3xl mx-auto flex flex-col gap-6 z-10">
            <h2 class="text-3xl font-bold text-cyan-400 glow-text">Fale Conosco</h2>
            <p class="text-slate-200">Tem dúvidas ou quer saber mais? Entre em contato conosco!</p>

            <a href="mailto:contato@ninjacontrol.com" class="inline-block px-8 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-bold rounded-full shadow-lg glow-box transition">contato@ninjacontrol.com</a>
        </div>
    </section>

<!-- Footer -->
<footer class="bg-slate-800/80 py-6 backdrop-blur-md">
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

  <script>
    // Inicializa o SimpleLightbox
    var lightbox = new SimpleLightbox('a', {
        captionsData: 'alt',
        captionDelay: 250
    });
  </script>

</body>
</html>