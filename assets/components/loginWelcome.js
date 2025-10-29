document.addEventListener("DOMContentLoaded", function () {
    console.log("Documento pronto - Vanilla JS");

    const welcome = document.getElementById("mensagemBemvindo");
    const login = document.getElementById("login-system2");

    if (!welcome || !login) {
        console.error("Erro: elementos não encontrados no DOM!");
        return;
    }

    // 1️⃣ Aplica animação de entrada
    welcome.classList.add("fade-in-up");
    console.log("fade-in-up aplicado");

    // 2️⃣ Espera 3 segundos (mensagem visível)
    setTimeout(() => {
        console.log("Iniciando fade-out-up");

        // 3️⃣ Troca as classes para animar saída
        welcome.classList.remove("fade-in-up");
        welcome.classList.add("fade-out-up");

        // 4️⃣ Após 0.8s (duração da animação), esconde o elemento e mostra o login
            console.log("Transição para login");
            welcome.style.display = "none";
            login.classList.remove("hidden");
            login.classList.add("fade-in-up");
            console.log("Login exibido com sucesso!");
    }, 1500);
});

