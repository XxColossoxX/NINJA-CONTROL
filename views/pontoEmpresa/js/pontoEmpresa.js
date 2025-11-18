let foto64 = ""; 
let cameraStream = null;
let valorLocAtual = "";
let funcionarios = [];
const selectedFuncionario = document.getElementById('selectedFuncionario');

$(document).ready( async function() {    
    await localizacaoAtual();
    $("#controlador").removeClass("hidden");
    loaderM("Carregando Funcionários", true)

    await carregaFuncionarios();
    loaderM("", false)


$(document).ready(function () {
    let cameraStream = null;

    // Abas
    $('.tab-button').on('click', function () {
        const tabId = $(this).data('tab');
        $('.tab-button').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').addClass('hidden');
        $('#' + tabId).removeClass('hidden');
    });

    // Abrir modal com câmera
    $('#btnBaterPonto').on('click', async function () {
        // Preenche os dados nas abas
        if ($("#nomeFuncDrop").text() === "Selecione o funcionário") {
            showAlert('Selecione um funcionário antes de bater o ponto!', 'error');
            return;
        }
        $('#tab-nome').text($("#nomeTbl").text());
        $('#tab-rg').text($("#rgTbl").text());
        $('#tab-cpf').text($("#cpfTbl").text());
        $('#tab-nascimento').text($("#nascimentoTbl").text());
        $('#tab-localizacao').text($("#localizacaoTbl").text());

        $('#modal-bater-ponto').removeClass('hidden');
        $('.tab-button').first().click(); // Ativa primeira aba

        // Delay para garantir que o vídeo esteja no DOM e visível
        setTimeout(async () => {
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                });

                const videoElement = $('#video-camera')[0];
                if (videoElement) {
                    if ("srcObject" in videoElement) {
                        videoElement.srcObject = cameraStream;
                    } else {
                        // Para navegadores antigos
                        videoElement.src = window.URL.createObjectURL(cameraStream);
                    }
                    videoElement.play();
                } else {
                    alert("Elemento de vídeo não encontrado!");
                }
            } catch (err) {
                alert('Erro ao acessar câmera: ' + err.message);
            }
        }, 100); // 100ms de delay
    });

    // Fechar modal e parar a câmera
    $('#btn-fechar-ponto').on('click', function () {
        $('#modal-bater-ponto').addClass('hidden');
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }
    });

    // Capturar imagem e converter para base64
    $('#btn-efetuar-ponto').on('click', async function () {
        loaderM('Processando face - Aguarde ...',true)
        const video = $('#video-camera')[0];
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const base64Image = canvas.toDataURL('image/jpeg');

        const img = new Image();
        img.src = base64Image;

        img.onload = async () => {
            const fotoBanco = $("#imgFuncionario")[0]; // DOM element
            const fotoAtual = img; // Agora está carregada

            await capturarRosto(fotoBanco, fotoAtual);
        };
    });
});

async function capturarRosto(fotoBanco, fotoAtual){
    await faceapi.nets.ssdMobilenetv1.loadFromUri('/backend/faceId/ssd_mobilenetv1')
    await faceapi.nets.faceLandmark68Net.loadFromUri('/backend/faceId/face_landmark_68')
    await faceapi.nets.faceRecognitionNet.loadFromUri('/backend/faceId/face_recognition')

    const det1 = await faceapi.detectSingleFace(fotoBanco).withFaceLandmarks().withFaceDescriptor()
    const det2 = await faceapi.detectSingleFace(fotoAtual).withFaceLandmarks().withFaceDescriptor()

    if (!det1 || !det2) {
        loaderM("", false);
        showAlert('Rosto não detectado!','error')
        return
    }

    const distance = faceapi.euclideanDistance(det1.descriptor, det2.descriptor)
    let precisao = 0;
    distance <= 0.35 ? await efetuarPonto(distance) : ((precisao = 100 - (distance * 100)) , (precisao = precisao.toFixed(2)), (showAlert(`Rosto não detectado! ${precisao}% de ser o funcionário selecionado.`,'error')), loaderM("", false));
    console.log('Distância:', distance  )

    $('#btn-fechar-ponto').click(); // Fecha o modal
};

async function carregaFuncionarios() {
    let select = $("#funcionario-select");
    select.empty();

    const res = await axios({
        url: "/backend/backend.php",
        method: "POST",
        data: {
            function: "loadPainel",
        },
        headers: { "Content-Type": "application/json" }

    });
    if (res.data.length >= 1) {
        funcionarios = []; // Limpa antes de popular
        for(let i = 0; i < res.data.length; i++){
            funcionarios.push({
                nome: res.data[i]["NOME_FUNCIONARIO"],
                rg: res.data[i]["RG"],
                cpf: res.data[i]["CPF"],
                nascimento: res.data[i]["DATA_NASCIMENTO"],
                foto: res.data[i]["FACEID"] || "./assets/img/iconDefault.png"
            });
        }

        // Popular lista do dropdown personalizado
        const dropdownList = document.getElementById('dropdownList');
        dropdownList.innerHTML = funcionarios.map((f, i) => `
            <div class="flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-cyan-700" data-index="${i}">
                <img src="${f.foto}" alt="Foto" class="w-6 h-6 rounded-full">
                <span>${f.nome}</span>
            </div>
        `).join('');

        // Adiciona evento de clique para seleção
        dropdownList.addEventListener('click', (e) => {
            const item = e.target.closest('[data-index]');
            if (item) {
                const idx = item.getAttribute('data-index');
                const selectedFuncionario = document.getElementById('selectedFuncionario');
                $(`#imgFuncionario`).attr('src', funcionarios[idx].foto);
                $(`#imgFuncionarioDesk`).attr('src', funcionarios[idx].foto);
                $("#nomeTbl").text(funcionarios[idx].nome);
                $("#rgTbl").text(funcionarios[idx].rg);
                $("#cpfTbl").text(funcionarios[idx].cpf);
                $("#nascimentoTbl").text(funcionarios[idx].nascimento);

                selectedFuncionario.innerHTML = `
                    <img src="${funcionarios[idx].foto}" alt="Foto" class="w-6 h-6 rounded-full">
                    <span>${funcionarios[idx].nome}</span>
                `;
                dropdownList.classList.add('hidden');
            }
        });

        return;
    } else {
        showAlert('Não há Funcionários cadastrados', "error");
        return;
    }
}

async function efetuarPonto(distance) {
    let precisao = distance * 100;
    precisao = 100 - precisao;
    precisao = precisao.toFixed(2);
    showAlert(`Ponto registrado com sucesso! ${precisao}% de Precisão!`, 'success');
    loaderM("", false);
}

async function localizacaoAtual() {
    const res = await axios({
        url: "/backend/backend.php",
        method: "POST",
        data: {
            function: "getLocaEmpresa",
        },
        headers: { "Content-Type": "application/json" }
    });
    if (res.data[0]['LOC_EMPRRESA'] != 'success') {
        $("#inputLocalizacao").val(res.data[0]['LOC_EMPRRESA']);
        sessionStorage.setItem('locEmpresa', res.data[0]['LOC_EMPRRESA']);
    } else {
        showAlert('Erro ao carregar localização da empresa.',"error");
        $("#inputLocalizacao").val('Endereço não disponível');
        sessionStorage.setItem('locEmpresa','Endereço não disponível');

        return;
    }
}


// Evento para abrir/fechar dropdown
document.getElementById('dropdownBtn').addEventListener('click', () => {
    document.getElementById('dropdownList').classList.toggle('hidden');
});
//!FRONT-END
//#region
// Botões de fechar os modais e eventos da captura de camera 

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
