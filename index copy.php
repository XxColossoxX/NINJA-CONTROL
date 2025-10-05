<?php
    require_once('assets/components/background.php');
?>

    <!-- Header -->
    <header class="bg-slate-900 shadow-lg fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="assets/img/ninjaLogo.png" alt="Logo Ninja" class="w-10 h-10">
                <span class="text-white text-2xl font-bold tracking-wide">Ninja Control</span>
            </div>
            <nav>
                <ul class="flex gap-8 text-white font-semibold text-lg">
                    <li><a href="#home" class="hover:text-cyan-400 transition">Home</a></li>
                    <li><a href="#sobre" class="hover:text-cyan-400 transition">Sobre Nós</a></li>
                    <li><a href="#contato" class="hover:text-cyan-400 transition">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main class="flex-1 flex flex-col items-center justify-center pt-32 pb-16 px-4" id="home">
        <section class="max-w-3xl w-full text-center">
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white mb-6 drop-shadow-lg">Controle de Ponto Moderno</h1>
            <p class="text-xl sm:text-2xl text-slate-200 mb-8 font-light">
                O <span class="font-bold text-cyan-400">Ninja Control</span> é a solução inovadora para gestão de ponto, trazendo tecnologia, segurança e praticidade para empresas e funcionários. Automatize processos, acompanhe jornadas e tenha controle total com uma interface intuitiva e moderna.
            </p>
            <a href="#sobre" class="inline-block bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition">Saiba Mais</a>
        </section>
    </main>

    <!-- Sobre Nós -->
    <section class="bg-slate-800 py-16 px-4" id="sobre">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-cyan-400 mb-4">Sobre Nós</h2>
            <p class="text-lg text-slate-200 mb-6">
                Somos apaixonados por tecnologia e inovação. O Ninja Control nasceu para revolucionar o controle de ponto, oferecendo uma plataforma segura, rápida e fácil de usar. Nossa missão é simplificar a rotina das empresas e valorizar o tempo dos colaboradores.
            </p>
            
            <!-- Cards em coluna (um embaixo do outro) -->
            <div class="flex flex-col items-center gap-8 mt-8">
                <!-- Card Segurança -->
                <div class="w-full max-w-md">
                    <div class="bg-slate-900 rounded-xl shadow-lg p-6">
                        <i class="fas fa-shield-alt text-cyan-400 text-3xl mb-2"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Segurança</h3>
                        <p class="text-slate-300">Dados protegidos e autenticação avançada para garantir total segurança.</p>
                    </div>
                </div>

                <!-- Card Agilidade -->
                <div class="w-full max-w-md">
                    <div class="bg-slate-900 rounded-xl shadow-lg p-6">
                        <i class="fas fa-bolt text-cyan-400 text-3xl mb-2"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Agilidade</h3>
                        <p class="text-slate-300">Processos rápidos e automatizados para facilitar o dia a dia.</p>
                    </div>
                </div>

                <!-- Card Facilidade -->
                <div class="w-full max-w-md">
                    <div class="bg-slate-900 rounded-xl shadow-lg p-6">
                        <i class="fas fa-users text-cyan-400 text-3xl mb-2"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Facilidade</h3>
                        <p class="text-slate-300">Interface intuitiva e moderna, pensada para todos os públicos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contato -->
    <section class="bg-slate-900 py-12 px-4" id="contato">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-cyan-400 mb-4">Fale Conosco</h2>
            <p class="text-slate-200 mb-6">Tem dúvidas ou quer saber mais? Entre em contato conosco!</p>
            <a href="mailto:contato@ninjacontrol.com" class="inline-block bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-6 rounded-full shadow transition">contato@ninjacontrol.com</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800 text-slate-300 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center gap-2 mb-2 md:mb-0">
                <img src="assets/img/ninjaLogo.png" alt="Logo Ninja" class="w-8 h-8">
                <span class="font-bold text-white">Ninja Control</span>
            </div>
            <div class="flex gap-4">
                <a href="#" class="hover:text-cyan-400"><i class="fab fa-instagram text-xl"></i></a>
                <a href="#" class="hover:text-cyan-400"><i class="fab fa-facebook text-xl"></i></a>
                <a href="#" class="hover:text-cyan-400"><i class="fab fa-linkedin text-xl"></i></a>
            </div>
            <span class="text-sm mt-2 md:mt-0">&copy; 2025 Ninja Control. Todos os direitos reservados.</span>
        </div>
    </footer>
</body>
</html>
