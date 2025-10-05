let foto64 = ""; 
let id = '';
let dataMethod = ""; 
const tabela = $("#tblFuncionario tbody");


$(document).ready( async function() {
    carregarNomeEmpresa()

    Inputmask("999.999.999-99").mask("#inputCpfFuncionario");
    Inputmask("99.999.999-9").mask("#inputRgFuncionario");
    Inputmask("(99)99999-9999").mask("#inputCelularFuncionario");

    Inputmask("999.999.999-99").mask("#showCpf");
    Inputmask("99.999.999-9").mask("#showRg");

    await recarregaTabela();
    await verificaLoc();
    
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

});

$('#btnAdd').on("click", async function(){
    dataMethod = "insert";
    id = '';

    $("#form-modal").removeClass("hidden");
    $("#inputNomeFuncionario").val('');
    $("#inputCpfFuncionario").val('');
    $("#inputRgFuncionario").val('');
    $("#inputSenhaFuncionario").val('');
    $("#inputDataNascFuncionario").val('');

    inputNome  = ('')  
    inputCpf   = ('')
    inputRg    = ('')
    inputData  = ('')
    inputSenha = ('')
});

$('#btnAddMobile').on("click", async function(){
    dataMethod = "insert";
    id = '';

    $("#form-modal").removeClass("hidden");
    $("#inputNomeFuncionario").val('');
    $("#inputCpfFuncionario").val('');
    $("#inputRgFuncionario").val('');
    $("#inputSenhaFuncionario").val('');
    $("#inputDataNascFuncionario").val('');

    inputNome  = ('')  
    inputCpf   = ('')
    inputRg    = ('')
    inputData  = ('')
    inputSenha = ('')
});

$(document).on("click", ".delete-icon", async function () {
    const id = $(this).data("id");

    if (confirm("Tem certeza que deseja excluir este funcionário?")) {
        try {
            const res = await axios({
                url: "../../../backend/backend.php",
                method: "POST",
                data: {
                    function: "deletaFuncionario",
                    ID_FUNCIONARIO: id,
                },
            });

            if (res.data) {
                recarregaTabela();
                $("#totalFunc").text(res.data.total_funcionarios_empresa);
                showAlert("Funcionário deletado!", "false");
            
            } else if (res.data.error) {
                showAlert("Erro: " + res.data.error, "true");
            }
        } catch (error) {
            console.error(error);
            showAlert("Erro na requisição!", "true");
        }
    }
});

$(document).on("click", ".edit-icon", async function () {
    id = $(this).data("id");
    dataMethod = "update";

    $("#inputNomeFuncionario").val('');
    $("#inputCpfFuncionario").val('');
    $("#inputRgFuncionario").val('');
    $("#inputSenhaFuncionario").val('');
    $("#inputDataNascFuncionario").val('')

    const res = await axios({
        url: "../../../backend/backend.php",
        method: "POST",
        data: {
            function: "loadDadosFuncionario",
            ID_FUNCIONARIO: id,
        },
    })
    console.log(res.data.data);
    $("#form-modal").removeClass("hidden");
    $("#inputNomeFuncionario").val(res.data.data.NOME_FUNCIONARIO);
    $("#inputCpfFuncionario").val(res.data.data.CPF);
    $("#inputRgFuncionario").val(res.data.data.RG);
    $("#inputSenhaFuncionario").val(res.data.data.SENHA_FUNCIONARIO);
    $("#inputDataNascFuncionario").val(res.data.data.DATA_NASCIMENTO);
    $("#inputCelularFuncionario").val(res.data.data.TEL_FUNCIONARIO);
    $("#inputEmailFuncionario").val(res.data.data.EMAIL_FUNCIONARIO);
});

//!FUNCOES
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
    const inputSenha    = $("#inputSenhaFuncionario").val()
    const inputCelular  = $("#inputCelularFuncionario").val();
    const inputEmail    = $("#inputEmailFuncionario").val()


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

        if(dataMethod === "update") {
            const res = await axios({
                url: "../../../backend/backend.php",
                method: "POST",
                data: {
                    function: "updateFuncionario",
                    ID_FUNCIONARIO: id,
                    NOME_FUNCIONARIO: inputNome,
                    CPF: inputCpf,
                    DATA_NASCIMENTO: inputData,
                    SENHA_FUNCIONARIO: inputSenha,
                    TEL_FUNCIONARIO: inputCelular,
                    EMAIL_FUNCIONARIO: inputEmail,
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
        }

        if(dataMethod === "insert") {
            const res = await axios({
                url: "../../../backend/backend.php",
                method: "POST",
                data: {
                    function: "applyFuncionario",
                    ID_FUNCIONARIO: id,
                    NOME_FUNCIONARIO: inputNome,
                    CPF: inputCpf,
                    DATA_NASCIMENTO: inputData,
                    SENHA_FUNCIONARIO: inputSenha,
                    TEL_FUNCIONARIO: inputCelular,
                    EMAIL_FUNCIONARIO: inputEmail,
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
        }
    });

    return stream;
};

async function preencheTabela(res) {
    const tabela = $("#tblFuncionario tbody");
    tabela.empty();

    if (res.data.length === 0) {
        const conteudo = `
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="px-6 py-4 text-center text-gray-400" colspan="5">Nenhum funcionário cadastrado</td>
            </tr>
        `;
        tabela.append(conteudo);
        return;
    }

    for (let i = 0; i < res.data.length; i++) {
        const func = res.data[i];
        const primeiroNome = func.NOME_FUNCIONARIO.split(" ")[0];

        const conteudo = `
            <tr class="border-b border-gray-200 hover:bg-blue-50 transition-all">
                <td class="px-2 py-2 text-center align-middle" style="min-width:56px; width:56px;">
                    <button class="info-icon icon-btn inline-flex items-center justify-center w-9 h-9 bg-gray-100 hover:bg-blue-200 text-blue-600 text-base rounded-full transition" data-id="${func.ID_FUNCIONARIO}" title="Mais informações">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </td>


                <!-- Imagem + Nome -->
                <td class="px-2 py-2 sm:px-4 sm:py-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-blue-300 bg-center bg-cover shadow-md" style="background-image: url('${func.FACEID}');"></div>
                        <span class="font-semibold text-gray-800 text-base sm:text-lg">
                            <span class="block sm:hidden">${primeiroNome}</span>
                            <span class="hidden sm:block">${func.NOME_FUNCIONARIO}</span>
                        </span>
                    </div>
                </td>

                <!-- CPF (visível apenas no desktop) -->
                <td class="hidden md:table-cell px-2 py-2 text-center text-gray-600">${func.CPF}</td>

                <!-- Botão Editar -->
                <td class="px-2 py-2 text-center">
                    <button class="icon-btn edit-icon inline-flex items-center justify-center w-9 h-9 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition" data-id="${func.ID_FUNCIONARIO}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>

                <!-- Botão Excluir -->
                <td class="px-2 py-2 text-center">
                    <button class="icon-btn delete-icon inline-flex items-center justify-center w-9 h-9 bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-id="${func.ID_FUNCIONARIO}" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        tabela.append(conteudo);
    }
};

// Modal de informações do funcionário
$(document).on("click", ".info-icon", async function () {
    const id = $(this).data("id");
    const res = await axios({
        url: "../../../backend/backend.php",
        method: "POST",
        data: {
            function: "loadDadosFuncionario",
            ID_FUNCIONARIO: id,
        },
    });
    const f = res.data.data;
    // Remove modal anterior se existir
    $("#modal-info-funcionario").remove();
    // Cria modal
    const modal = `
        <div id="modal-info-funcionario" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md relative animate-fade-in">
                <button id="close-modal-info" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-xl"><i class="fas fa-times"></i></button>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-24 h-24 rounded-full border-4 border-blue-300 bg-center bg-cover mb-2" style="background-image: url('${f.FACEID}');"></div>
                    <h2 class="text-xl font-bold text-blue-700 mb-1">${f.NOME_FUNCIONARIO}</h2>
                    <div class="w-full flex flex-col gap-2 text-gray-700">
                        <div><i class="fas fa-id-card mr-2 text-blue-500"></i> <b>CPF:</b> ${f.CPF}</div>
                        <div><i class="fas fa-address-card mr-2 text-blue-500"></i> <b>RG:</b> ${f.RG}</div>
                        <div><i class="fas fa-calendar-alt mr-2 text-blue-500"></i> <b>Nascimento:</b> ${f.DATA_NASCIMENTO}</div>
                        ${f.TEL_FUNCIONARIO ? `<div><i class='fas fa-phone mr-2 text-blue-500'></i> <b>Telefone:</b> ${f.TEL_FUNCIONARIO}</div>` : ''}
                        ${f.EMAIL_FUNCIONARIO ? `<div><i class='fas fa-envelope mr-2 text-blue-500'></i> <b>Email:</b> ${f.EMAIL_FUNCIONARIO}</div>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    $("body").append(modal);
});

$(document).on("click", "#close-modal-info", function () {
    $("#modal-info-funcionario").remove();
});

async function carregarNomeEmpresa() {
    const res = await axios({
            url: "../../../backend/backend.php",
            method: "POST",
            data: {
                function: "getNomeEmpresa",
            },
        });
    let nomeEmpresa = res.data[0].RAZAO_FANTASIA.toUpperCase();
    let divBemVindo = $("#bemVindo");
    let conteudo = `
    <div id="welcome-message" class="fixed inset-0 flex items-center justify-center z-50 text-center">
        <div>
            <h1 class="text-4xl text-white font-sans"><i><strong>BEM-VINDO(A) DE VOLTA</strong></i></h1>
            <h2 id="tituloEmpresa" class="text-2xl text-white font-sans underline text-center"><i><strong>${nomeEmpresa}</strong></i></h2>
        </div>
    </div>`;

    divBemVindo.append(conteudo);
    // Animação de entrada
    setTimeout(() => {
        $("#welcome-message").addClass("entrada");
    }, 100);

    // Animação de saída após 3s
    setTimeout(() => {
        const el = $("#welcome-message");
        el.removeClass("entrada");
        el.removeClass("saida");
        setTimeout(() => {
            el.remove();
            $(".controlador").removeClass("hidden");
            $("#controlador").removeClass("hidden");
        }, 500);
    }, 1500);
    return;
};

async function recarregaTabela() {
    const tabela = $("#tblFuncionario tbody");
    tabela.empty();

    await loadFuncionariosEmpresa()
    return
};

async function loadFuncionariosEmpresa(){
    tabela.empty();
    const res = await axios({
        url: "../../../backend/backend.php",
        method: "POST",
        data: {
            function: "loadPainel",
            
        },
    });
        await preencheTabela(res);
        $("totalFunc").empty();
        $("#totalFunc").text(res.data.length);
        return
}; 

async function verificaLoc(){
    const res = await axios({
        url: "../../../backend/backend.php",
        method: "POST",
        data: {
            function: "getLocEmpresa",
        },
    });
    console.log(res);
    if(res.data[0].LOC_EMPRESA === null || res.data[0].LOC_EMPRESA === ""){
        showAlert("Por favor, defina a localização da empresa em Dados Empresa.", "warning");
    }
}

// FRONT-END
$(document).ready(function () {
    //#region

    // Botões de fechar modal
    $('#close-form-modal').on('click', function () {
        $('#form-modal').addClass('hidden');
    });

    $('#close-camera-modal').on('click', function () {
        const video = $('#register-camera')[0];

        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }

        $('#camera-modal').addClass('hidden');
    });

    // Menu hambúrguer
    $('#menu-toggle').on('click', function () {
        $('#menu').removeClass('menu-hidden').addClass('menu-visible');
    });

    $('#menu-close').on('click', function () {
        $('#menu').removeClass('menu-visible').addClass('menu-hidden');
    });

    // Abrir modal de formulário de funcionário
    $('#add-employee-btn').on('click', function () {
        $('#form-modal').removeClass('hidden');
    });

    //#endregion
});

//#endregion
});
