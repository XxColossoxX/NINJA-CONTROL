let foto64 = ""; 
let id = '';
let dataMethod = ""; 
const tabela = $("#tblFuncionario tbody");


$(document).ready( async function() {
    loaderM("Carregando Funcionários... Aguarde Por Favor!");
    carregarNomeEmpresa()
    if(sessionStorage.getItem('locEmpresa') === null || sessionStorage.getItem('locEmpresa') === "" )
        showAlert('Preencha a localização em Dados Empresa!', "warning");

    if(sessionStorage.getItem('latEmpresa') === 'null' || sessionStorage.getItem('longEmpresa') === 'null' )
        showAlert('Defina a Localização da empresa em DADOS EMPRESA!', "warning");
    

    Inputmask("999.999.999-99").mask("#inputCpfFuncionario");
    Inputmask("99.999.999-9").mask("#inputRgFuncionario");
    Inputmask("(99)99999-9999").mask("#inputCelularFuncionario");

    Inputmask("999.999.999-99").mask("#showCpf");
    Inputmask("99.999.999-9").mask("#showRg");

    await recarregaTabela();
    
//!BOTOES
$(document).on('click', "#btnProximo", async function(){
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

    $("#form-modal").show();
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
                url: "/backend/backend.php",
                method: "POST",
                data: {
                    function: "deletaFuncionario",
                    ID_FUNCIONARIO: id,
                },
                headers: { "Content-Type": "application/json"}
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
        url: "/backend/backend.php",
        method: "POST",
        data: {
            function: "loadDadosFuncionario",
            ID_FUNCIONARIO: id,
        },
        headers: { "Content-Type": "application/json"}
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
    const fillDisplay = () => {
        displayNome.textContent = $("#inputNomeFuncionario").val();
        displayCpf.textContent = $("#inputCpfFuncionario").val();
        displayRg.textContent = $("#inputRgFuncionario").val();
        displayData.textContent = $("#inputDataNascFuncionario").val();
    };

    fillDisplay();

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

    // Remover qualquer listener anterior para evitar duplicação
    const btnCapturar = document.getElementById("btnCapturar");
    const novoBtn = btnCapturar.cloneNode(true);
    btnCapturar.parentNode.replaceChild(novoBtn, btnCapturar);

    novoBtn.addEventListener("click", async function () {
        if ($("#inputHoraEntrada").val() === "" || $("#inputHoraSaida").val() === "" ||
            $("#inputHoraIntInicio").val() === "" || $("#inputHoraIntFim").val() === "") {
            showAlert("Defina os horários de trabalho antes de continuar!", "error");
            return;
        }

        // Captura a foto
        const canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext("2d");
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const foto64 = canvas.toDataURL("image/png");
        console.log("Foto capturada:", foto64);

        // Preencher variáveis com os inputs atuais
        const inputNome     = $("#inputNomeFuncionario").val();
        const inputCpf      = $("#inputCpfFuncionario").val();
        const inputRg       = $("#inputRgFuncionario").val();
        const inputData     = $("#inputDataNascFuncionario").val();
        const inputSenha    = $("#inputSenhaFuncionario").val();
        const inputCelular  = $("#inputCelularFuncionario").val();
        const inputEmail    = $("#inputEmailFuncionario").val();
        const entrada      = $("#inputHoraEntrada").val();
        const saida        = $("#inputHoraSaida").val();
        const intIni       = $("#inputHoraIntInicio").val();
        const intFim       = $("#inputHoraIntFim").val();

        // Exibir mensagem de boas-vindas
        welcomeNome.textContent = inputNome;
        welcomeMessage.classList.remove("hidden");

        let res;

        if (dataMethod === "update") {
            res = await axios({
                url: "/backend/backend.php",
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
                    ENTRADA1: entrada,
                    SAIDA1: intIni,
                    ENTRADA2: intFim,
                    SAIDA2: saida,
                },
                headers: { "Content-Type": "application/json" }
            });
        }

        if (dataMethod === "insert") {
            res = await axios({
                url: "/backend/backend.php",
                method: "POST",
                data: {
                    function: "applyFuncionario",
                    NOME_FUNCIONARIO: inputNome,
                    CPF: inputCpf,
                    DATA_NASCIMENTO: inputData,
                    SENHA_FUNCIONARIO: inputSenha,
                    TEL_FUNCIONARIO: inputCelular,
                    EMAIL_FUNCIONARIO: inputEmail,
                    RG: inputRg,
                    FACEID: foto64,
                    ENTRADA1: entrada,
                    SAIDA1: intIni,
                    ENTRADA2: intFim,
                    SAIDA2: saida,
                },
                headers: { "Content-Type": "application/json" }
            });
        }

        if (res.data.success) {
            await salvarHorarios(
                $("#inputHoraEntrada").val(),
                $("#inputHoraSaida").val(),
                $("#inputHoraIntInicio").val(),
                $("#inputHoraIntFim").val(),
                id,
                dataMethod
            );

            $("#close-camera-modal").click();
            recarregaTabela();

            // Limpar inputs
            $("#inputNomeFuncionario").val('');
            $("#inputCpfFuncionario").val('');
            $("#inputRgFuncionario").val('');
            $("#inputDataNascFuncionario").val('');
            $("#inputSenhaFuncionario").val('');
            $("#inputCelularFuncionario").val('');
            $("#inputEmailFuncionario").val('');

            fillDisplay(); // Limpar display
        } else {
            showAlert(res.data.message, "error");
            console.log(res.data);
        }
    });
}

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
            <tr class="border-b bg-slate-800 text-white font-bold border-cyan-200 hover:bg-cyan-700 transition-all">
                <td class="px-2 py-2 text-center align-middle" style="min-width:56px; width:56px;">
                    <button class="info-icon icon-btn inline-flex items-center justify-center w-9 h-9 bg-cyan-100 hover:bg-blue-200 text-blue-600 text-base rounded-full transition" data-id="${func.ID_FUNCIONARIO}" title="Mais informações">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </td>

                <!-- Imagem + Nome -->
                <td class="px-2 py-2 sm:px-4 sm:py-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-blue-300 bg-center bg-cover shadow-md" style="background-image: url('${func.FACEID}');"></div>
                        <span class="font-bold text-white text-base sm:text-lg">
                            <span class="block sm:hidden">${primeiroNome}</span>
                            <span class="hidden sm:block">${func.NOME_FUNCIONARIO}</span>
                        </span>
                    </div>
                </td>

                <!-- CPF (visível apenas no desktop) -->
                <td class="hidden md:table-cell px-2 py-2 text-center text-white font-bold">${func.CPF}</td>

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
        url: "/backend/backend.php",
        method: "POST",
        data: {
            function: "loadDadosFuncionario",
            ID_FUNCIONARIO: id,
        },
        headers: { "Content-Type": "application/json"}
    });
    const f = res.data.data;
    // Remove modal anterior se existir
    $("#modal-info-funcionario").remove();
    // Cria modal
    const modal = `
        <div id="modal-info-funcionario" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-slate-900 w-full max-w-md rounded-lg shadow-lg p-6 relative animate-fade-in overflow-y-auto max-h-[90vh]">
                <!-- Botão fechar -->
                <button id="close-modal-info" class="absolute top-3 right-3 text-white hover:text-red-500 text-xl z-10">
                    <i class="fas fa-times"></i>
                </button>

                <!-- Conteúdo -->
                <div class="flex flex-col items-center gap-3">
                    <div class="w-24 h-24 rounded-full border-4 border-cyan-300 bg-center bg-cover mb-2" style="background-image: url('${f.FACEID}');"></div>
                    <h2 class="text-xl font-bold text-cyan-400 mb-1">${f.NOME_FUNCIONARIO}</h2>
                    <div class="w-full flex flex-col gap-2 text-white">
                        <div><i class="fas fa-id-card mr-2 text-cyan-500"></i> <b>CPF:</b> ${f.CPF}</div>
                        <div><i class="fas fa-address-card mr-2 text-cyan-500"></i> <b>RG:</b> ${f.RG}</div>
                        <div><i class="fas fa-calendar-alt mr-2 text-cyan-500"></i> <b>Nascimento:</b> ${f.DATA_NASCIMENTO}</div>
                        ${f.TEL_FUNCIONARIO ? `<div><i class='fas fa-phone mr-2 text-cyan-500'></i> <b>Telefone:</b> ${f.TEL_FUNCIONARIO}</div>` : ''}
                        ${f.EMAIL_FUNCIONARIO ? `<div><i class='fas fa-envelope mr-2 text-cyan-500'></i> <b>Email:</b> ${f.EMAIL_FUNCIONARIO}</div>` : ''}
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
    let nomeEmpresa = sessionStorage.getItem("RAZAO_FANTASIA");
    let divBemVindo = $("#bemVindo");
    let conteudo = `
    <div id="welcome-message" class="fixed inset-0 flex items-center justify-center z-50 text-center">
    <div class="flex flex-col items-center">
        
        <!-- Título pequeno -->
        <span class="text-sm md:text-base text-white font-semibold tracking-wide">
            BEM-VINDO(A)
        </span>

        <!-- Nome grande -->
        <span class="text-2xl md:text-5xl font-extrabold text-white leading-tight mt-2">
            ${nomeEmpresa}
        </span>

    </div>
</div>    `;

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
    const res = await axios.post('/backend/backend.php',
            {
                function: "loadPainel",
            },
            {
                headers: {
                    "Content-Type": "application/json"
                }
            }
        );

        await preencheTabela(res);
        $("totalFunc").empty();
        $("#totalFunc").text(res.data.length);
        loaderM(false);
        return
}; 

async function salvarHorarios(entrada, saida, intIni, intFim, id, dataMethod){
    const empresaId = $("#empresa-id").text();

    if(dataMethod === "insert"){
        const res = await axios({
            url: "/backend/backend.php",
            method: "POST",
            data: {
                function: "applyHorarioFuncionario",
                ENTRADA1: entrada,
                SAIDA1: intIni,
                ENTRADA2: intFim,
                SAIDA2: saida,
                ID_FUNCIONARIO: id,
                ID_EMPRESA: empresaId,
            },  

            headers: { "Content-Type": "application/json"}
        });
        if(res.data.success){
            showAlert("Funcionário cadastrado com Sucesso!", "success");
            inputNome  = ('')
            inputCpf   = ('')
            inputRg    = ('')
            inputData  = ('')
            inputSenha = ('')
        }
    }

    if(dataMethod === "update"){
        const res = await axios({
            url: "/backend/backend.php",
            method: "POST",
            data: {
                function: "updateHorarioFuncionario",
                ENTRADA1: entrada,
                SAIDA1: intIni,
                ENTRADA2: intFim,
                SAIDA2: saida,
                ID_FUNCIONARIO: id,
                ID_EMPRESA: empresaId,
            },  

            headers: { "Content-Type": "application/json"}
        });
        if(res.data.success){
            showAlert("Funcionário cadastrado com Sucesso!", "success");
        }
    }
};

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

    // Tabs (Dados | Horários) no modal admin
    $(document).on('click', '.tab-admin', function(){
        $('.tab-admin').removeClass('active bg-cyan-500/20').addClass('bg-transparent');
        $(this).addClass('active bg-cyan-500/20');
        const tab = $(this).data('tab');
        $('.admin-pane').addClass('hidden');
        $('#' + tab).removeClass('hidden');
    });

    // Salvar horários (placeholder – integrar backend depois)
    $(document).on('click', '#btnSalvarHorarios', async function(){
        const entrada = $('#inputHoraEntrada').val();
        const saida = $('#inputHoraSaida').val();
        const intIni = $('#inputHoraIntInicio').val();
        const intFim = $('#inputHoraIntFim').val();

        if(!entrada || !saida){
            showAlert('Preencha Entrada e Saída', 'warning');
            return;
        }

        try{
            // await axios.post('../../../backend/backend.php', { function: 'salvarHorarios', entrada, saida, intIni, intFim, id: funcionarioIdSelecionado })
            showAlert('Horários atualizados!', 'success');
        }catch(e){
            console.error(e);
            showAlert('Erro ao salvar horários', 'error');
        }
    });
});
//#endregion
});
