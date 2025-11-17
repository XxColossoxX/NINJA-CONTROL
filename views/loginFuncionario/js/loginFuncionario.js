$(document).ready(async function() {
    let input = $("#inputCpfFuncionario")
    Inputmask("999.999.999-99").mask(input)

    // $("#inputCpfFuncionario").Inputmask("999.999.999-99")

    //!BOTOES
    //Botao de Login Empresa
    //#region
   $("#btnEntrar").on('click', function(){
        loginFuncionario();
   })
   //#endregion
   
   // Botao Alternar visibilidade da senha
   //#region
$('#toggleSenhaFuncionario').on('click', function() {
    const input = $('#inputSenhaFuncionario');
    const icon = $('#iconSenhaFuncionario');
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});
   //#endregion


   //!FUNCOES:
   //cadastrarEmpresa
   //#region 
   async function loginFuncionario() {
    const usuarioFuncionario  = $("#inputCpfFuncionario").val(); ;
    const senhaFuncionario    = $("#inputSenhaFuncionario").val(); ;

    if (!usuarioFuncionario || !senhaFuncionario) {
        showAlert("Por favor, preencha todos os campos.", "error");
        return;
    }

    try {
        const res = await axios({
            url: "/backend/backend.php",
            method: "POST",
            withCredentials: true,
            data:{
                function: "loadFuncionario",
                CPF: usuarioFuncionario,
                SENHA_FUNCIONARIO: senhaFuncionario,

            },
            headers: {
                    "Content-Type": "application/json"
                }
        });

        if (res.data.success) {
            // Login bem-sucedido, redireciona para o dashboard
            console.log(res.data.message, "success");
            window.location.href = `../../../views/pontoFuncionario/pontoFuncionario.php?`;
        } else {
            // Exibe mensagem de erro
            showAlert(res.data.error);
        }
    } catch (error) {
        console.error("Erro ao fazer login:", error);
        alert("Erro ao conectar ao servidor.");
    }
}
   //#endregion
     
   //alertBox
    //#region
    function showAlert(message, type = "error") {
        const alertBox = document.getElementById("alert-box");
        const alertMessage = document.getElementById("alert-message");
    
        if (!alertBox || !alertMessage) {
            console.error("Elementos de alerta não encontrados no DOM.");
            return;
        }
    
        // Define a mensagem
        alertMessage.textContent = message;
    
        // Limpa classes antigas e aplica a cor conforme o tipo
        alertBox.classList.remove("hidden", "alert-hide", "bg-red-500", "bg-green-500");
    
        if (type === "success") {
            alertBox.classList.add("bg-green-500");
        } else {
            alertBox.classList.add("bg-red-500");
        }
    
        // Adiciona a animação de entrada
        alertBox.classList.add("alert-show");
    
        // Remove a animação de entrada após 3 segundos e adiciona a de saída
        setTimeout(() => {
            alertBox.classList.remove("alert-show");
            alertBox.classList.add("alert-hide");
    
            // Oculta o alerta após a animação de saída
            setTimeout(() => {
                alertBox.classList.add("hidden");
            }, 500); // Aguarda a animação de saída terminar
        }, 3000);
    }
    //#endregion
       });