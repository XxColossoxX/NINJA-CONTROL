let foto64 = ""; 
const tabela = $("#tblFuncionario tbody");


$(document).ready( async function() {    
   setTimeout(() => {
    document.getElementById("bemVindo").classList.add("hidden");
    document.getElementById("controlador").classList.remove("hidden");
}, 40);


    Inputmask("999.999.999-99").mask("#inputCpfFuncionario");
    Inputmask("99.999.999-9").mask("#inputRgFuncionario");

    Inputmask("999.999.999-99").mask("#showCpf");
    Inputmask("99.999.999-9").mask("#showRg");

    await recarregaTabela();

//!BOTOES

$("#btnProximo").on('click', async function(){
    let inputNome   = $("#inputNomeFuncionario").val()
    let inputCpf    = $("#inputCpfFuncionario").val()
    let inputRg     = $("#inputRgFuncionario").val()
    let inputData   = $("#inputDataNascFuncionario").val()

    if(!inputNome || !inputCpf || !inputRg || !inputData) {
        showAlert("Preencha todos os campos!", "error");
        return;
    }
    $("#form-modal").addClass("hidden");
    $("#camera-modal").removeClass("hidden");
    await abrirCamera();

})


$(document).on("click", ".delete-icon", async function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este funcionário?")) {
        const res = axios({
                url: "../../../backend/backend.php",
                method: "POST",
                data: {
                    function: "deletaFuncionario",
                    ID_FUNCIONARIO: id,
                },
        })
        
        const loadFuncionario = await axios({
            url: "../../../backend/backend.php",
            method: "POST",
            data: {
                function: "load",
                
            },
        });

        $(`i.delete-icon[data-id="${id}"]`).closest("tr").remove();
        $("#totalFunc").empty()
        $("#totalFunc").text(loadFuncionario.data.length)
        showAlert("Funcionário deletado!","false")
    }
});

$(document).on("click", ".edit-icon", function () {
    const id = $(this).data("id");

    const res = axios({
        url: "../../../backend/backend.php",
        method: "POST",
        data: {
            function: "loadFuncionario",
            ID_FUNCIONARIO: id,
        },
    }).then((res) => {
        console.log(res.data);
        console.log(res.data[0]);
        
        $("#form-modal").removeClass("hidden");
        $("#inputNomeFuncionario").val(res.data[0].NOME_FUNCIONARIO);
        $("#inputCpfFuncionario").val(res.data[0].CPF);
        $("#inputRgFuncionario").val(res.data[0].RG);
        $("#inputDataNascFuncionario").val(res.data[0].DATA_NASCIMENTO);
    });

});

//!FUNCOES


async function cadastrarFuncionario(){
    let inputNome   = $("#inputNomeFuncionario").val()
    let inputCpf    = $("#inputCpfFuncionario").val()
    let inputRg     = $("#inputRgFuncionario").val()
    let inputData   = $("#inputDataNascFuncionario").val()

    if(!inputNome || !inputCpf || !inputRg || !inputData) {
        showAlert("Preencha todos os campos!", "error");
        return;
    }

    await abrirCamera();

    // Verificar se a foto foi capturada
    if (!foto64) {
        showAlert("Erro ao capturar a foto. Tente novamente.", "error");
        return;
    }

    try {
        const res = await axios({
            url: "../../../backend/backend.php",
            method: "POST",
            data: {
                function: "apply",
                NOME_FUNCIONARIO: inputNome,
                CPF: inputCpf,
                DATA_NASCIMENTO: inputData,
                RG: inputRg,
                FACEID: foto64,
            },
        });

        if (res.data.success) {
            showAlert("Funcionário cadastrado com sucesso!", "success");
            
        } else {
            showAlert("Erro ao cadastrar funcionário.", "error");
        }
    } catch (error) {
        console.error("Erro ao enviar os dados:", error);
        showAlert("Erro ao enviar os dados. Tente novamente.", "error");
    }
}

async function abrirCamera() {
    const video = document.getElementById("register-camera");
    const cameraError = document.getElementById("camera-error");
    const displayNome = document.getElementById("display-nome");
    const displayCpf = document.getElementById("display-cpf");
    const displayRg = document.getElementById("display-rg");
    const displayData = document.getElementById("display-data");
    const welcomeMessage = document.getElementById("welcome-message");
    const welcomeNome = document.getElementById("welcome-nome");
    let stream;

    // Preencher os dados do formulário na seção de dados
    const inputNome     = $("#inputNomeFuncionario").val();
    const inputCpf      = $("#inputCpfFuncionario").val();
    const inputRg       = $("#inputRgFuncionario").val();
    const inputData     = $("#inputDataNascFuncionario").val();

    displayNome.textContent = inputNome;
    displayCpf.textContent = inputCpf;
    displayRg.textContent = inputRg;
    displayData.textContent = inputData;

    try {
        // Solicitar permissão para acessar a câmera
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        video.classList.remove("hidden");
        cameraError.classList.add("hidden");
    } catch (error) {
        console.error("Erro ao acessar a câmera:", error);
        cameraError.classList.remove("hidden");
        video.classList.add("hidden");
    }

    // Capturar a foto ao clicar no botão "Capturar"
    document.getElementById("btnCapturar").addEventListener("click", async function () {
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Converter a imagem para Base64
        foto64 = canvas.toDataURL("image/png");
        console.log("Foto capturada:", foto64);


        // Exibir mensagem de boas-vindas
        welcomeNome.textContent = inputNome;
        welcomeMessage.classList.remove("hidden");
    
        const res = await axios({
            url: "../../../backend/backend.php",
            method: "POST",
            data: {
                function: "apply",
                NOME_FUNCIONARIO: inputNome,
                CPF: inputCpf,
                DATA_NASCIMENTO: inputData,
                RG: inputRg,
                FACEID: foto64,
            },
        });
    
        if (res.data.success) {
            showAlert(res.data.message, "success");
            $("#close-camera-modal").click();
            recarregaTabela();
            return
        } else {
            showAlert("Erro ao cadastrar funcionário.", "error");
            console.log(res.data);
        }
    });

    return stream;
}

async function preencheTabela(res) {
    tabela.empty();
    if(res.data.length == 0) {
        const tabela    = $("#tblFuncionario tbody");
        const conteudo  = `
                    <tr class="border-b border-gray-200 hover:bg-gray-100 rounded-lg"> 
                        <td class="px-6 py-4 rounded-lg"></td>
                        <td class="px-6 py-4 rounded-lg"></td>
                        <td class="px-6 py-4 rounded-lg"></td>
                        <td class="px-6 py-4 rounded-lg"></td>
                        <td class="px-6 py-4 rounded-lg"></td>
                    </tr>
            `
        tabela.append(conteudo);
    }   


    for (let i = 0; i < res.data.length; i++) {
        let idFuncionario       = res.data[i].ID_FUNCIONARIO;
        let nomeFuncionario     = res.data[i].NOME_FUNCIONARIO;
        let cpfFuncionario      = res.data[i].CPF;
        let rgFuncionario       = res.data[i].RG;
        let imagemFuncionario   = res.data[i].FACEID;

        const tabela = $("#tblFuncionario tbody");
        const conteudo = `
            <tr class="border-b border-gray-200 hover:bg-gray-100 rounded-lg"> 
                <td class="px-4 py-2 text-center border-r-2 border-gray-100">${idFuncionario}</td>
                <td class="px-4 py-2 text-center border-r-2 border-gray-100 sm:rounded-tl-lg">${nomeFuncionario}</td>
                <td class="px-4 py-2 text-center border-r-2 border-gray-100 hidden md:table-cell">${cpfFuncionario}</td>
                <td class="px-4 py-2 text-center border-r-2 border-gray-100 hidden md:table-cell">${rgFuncionario}</td>
                <td class="px-4 py-2 flex justify-center items-center border-r-2 border-gray-100">
                    <img src="${imagemFuncionario}" class="w-16 h-16 rounded-full border-4 border-gray-250">
                </td>
                <td class="px-2 py-1 text-center border-r-2 border-gray-100 ">
                    <i id="btnEdit" class="fas fa-edit text-blue-500 hover:text-blue-700 cursor-pointer edit-icon" data-id="${idFuncionario}" title="Editar"></i>
                
                </td>
                <td class="px-2 py-1 text-center">
                    <i class="fas fa-trash text-red-500 hover:text-red-700 cursor-pointer delete-icon" data-id="${idFuncionario}" id="btnDeletar" title="Excluir"></i>
                </td>
            </tr>
        `;
        tabela.append(conteudo);
    }
    if(res.data.length < 1){
        setTimeout(() => {
            showAlert("NÃO HÁ FUNCIONÁRIOS CADASTRADOS !", "error");
        }, 2000);        
    }
}

async function recarregaTabela() {
    const tabela = $("#tblFuncionario tbody");
    tabela.empty();


    await loadEmpresa()
    return
}

async function loadEmpresa(){
    tabela.empty();
    const res = await axios({
        url: "../../../backend/backend.php",
        method: "POST",
        data: {
            function: "load",
            
        },
    });
        await preencheTabela(res);
        $("totalFunc").empty();
        $("#totalFunc").text(res.data.length);
        return
}

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

//!FRONT-END
//#region
// Botões de fechar os modais
const closeFormModalBtn     = document.getElementById('close-form-modal');
const closeCameraModalBtn   = document.getElementById('close-camera-modal');

// Fechar o modal do formulário
closeFormModalBtn.addEventListener('click', () => {
    formModal.classList.add('hidden');
});

// Fechar o modal de captura de rosto
closeCameraModalBtn.addEventListener('click', () => {
    cameraModal.classList.add('hidden');
});

document.getElementById("close-camera-modal").addEventListener("click", function () {
    const cameraModal = document.getElementById("camera-modal");
    const video = document.getElementById("register-camera");

    // Parar o stream da câmera
    if (video.srcObject) {
        video.srcObject.getTracks().forEach((track) => track.stop());
    }

    cameraModal.classList.add("hidden");
});

const menuToggle    = document.getElementById("menu-toggle");
const menuClose     = document.getElementById("menu-close");
const menu          = document.getElementById("menu");

menuToggle.addEventListener("click", () => {
    menu.classList.remove("menu-hidden");
    menu.classList.add("menu-visible");
});

menuClose.addEventListener("click", () => {
    menu.classList.remove("menu-visible");
    menu.classList.add("menu-hidden");
});

const addEmployeeBtn    = document.getElementById('add-employee-btn');
const formModal         = document.getElementById('form-modal');
const cameraModal       = document.getElementById('camera-modal');
const nextToCameraBtn   = document.getElementById('next-to-camera');
const video             = document.getElementById('video');
const captureBtn        = document.getElementById('capture-btn');

addEmployeeBtn.addEventListener('click', () => {
    formModal.classList.remove('hidden');
});
//#endregion

});
