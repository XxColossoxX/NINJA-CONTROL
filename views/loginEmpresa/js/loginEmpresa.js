$(document).ready(async function() {
    Inputmask("99.999.999/9999-99").mask("#inputCnpjLogin");


    //!BOTOES
    //funcaoOlhoSenha
   //#region 
    // Função para alternar a visibilidade da senha
$('#toggleSenhaLogin').on('click', function() {
    const input = $('#inputSenhaLogin');
    const icon = $('#iconSenhaLogin');
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});
   
   //#endregion
    //#region
   $("#btnEntrar").on('click', function(){
        loginEmpresa();
   })
   //#endregion

   //!FUNCOES:    
   //loginEmpresa
   //#region 
   async function loginEmpresa() {
    const cnpjEmpresa   = $("#inputCnpjLogin").val(); ;
    const senhaEmpresa  = $("#inputSenhaLogin").val(); ;

    if (!cnpjEmpresa || !senhaEmpresa) {
        showAlert("Por favor, preencha todos os campos.");
        return;
    }

    try {
        const res = await axios.post('/backend/backend.php',
            {
                function: "loadEmpresa",
                CNPJ_EMPRESA: cnpjEmpresa,
                SENHA_EMPRESA: senhaEmpresa,
            },
            {
                headers: {
                    "Content-Type": "application/json"
                }
            }
        );

        if (res.data.success) {
            sessionStorage.setItem('RAZAO_FANTASIA', res.data.data['RAZAO_FANTASIA']);
            sessionStorage.setItem('locEmpresa', res.data.data['LOC_EMPRESA']);
            sessionStorage.setItem('latEmpresa', res.data.data['LAT_EMPRESA']);
            sessionStorage.setItem('longEmpresa', res.data.data['LONG_EMPRESA']);
            // Redireciona para a página principal
            window.location.href = "../../views/painelPrincipal/painelPrincipal.php";
        } else {
            // Exibe mensagem de erro
            showAlert(res.data.error);
        }
    } catch (error) {
        showAlert("Erro ao conectar ao servidor.", "error");
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