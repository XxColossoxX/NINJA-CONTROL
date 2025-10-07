let foto64 = ""; 
let cameraStream = null;
let valorLocAtual = "";

$(document).ready( async function() {    
    //!FUNCOES
    setTimeout(async () => {
        await locAtual();
    }, 2500);


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
        $('#tab-nome').text(nomeFuncionario);
        $('#tab-empresa').text(nomeEmpresa);
        $('#tab-rg').text(rgFuncionario);
        $('#tab-cpf').text(cpfFuncionario);
        $('#tab-nascimento').text(dataNascimentoFuncionario);
        $('#tab-localizacao').text(localizacaoEmpresa);

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
    distance <= 0.35 ? await comparaEndereco() : (showAlert('Rosto não reconhecido!','error'), loaderM("", false));

    $('#btn-fechar-ponto').click(); // Fecha o modal
};

async function locAtual() {
    loaderM('Obtendo Endereço Atual - Permita no seu Navegador ...',true)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                getEndereco(lat, lng);
            },
            (error) => {
                console.error("Erro ao obter localização:", error.message);
            },
            {
                enableHighAccuracy: true, // Força o uso do GPS com mais precisão
                timeout: 5000, // Tempo máximo para tentar pegar a localização
                maximumAge: 0 // Não usar dados de localização cacheados
            }
        );
    }
};

function getEndereco(latitude, longitude) {
    const apiKey = '';
    const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`;
    let endereco = {
        numero: '',
        rua: '',
        bairro: '',
        cidade: '',
        estado: '',
        cep: ''
    };

    fetch(url)
        .then(response => response.json())
        .then(data => {
        if (data.status === "OK") {
            endereco.numero = data.results[0].address_components[0]['long_name'];
            endereco.rua = data.results[0].address_components[1]['short_name'];
            endereco.bairro = data.results[0].address_components[2]['long_name'];
            endereco.cidade = data.results[0].address_components[3]['long_name'];
            endereco.estado = data.results[0].address_components[4]['long_name'];
            endereco.cep = data.results[0].address_components[6]['long_name'];

            valorLocAtual = `${endereco.rua}, ${endereco.numero}, ${endereco.bairro}, ${endereco.cep}, ${endereco.cidade}, ${endereco.estado}`;
            console.log('valor atual:',valorLocAtual)
            $("#inputLocalizacao").val(valorLocAtual);

            setTimeout(() => {
                loaderM("", false); 
            }, 2000)
        } else {
            console.error("Erro na geocodificação:", data.status);
            setTimeout(() => {
                loaderM("", false); 
            }, 2000)            }
    }).catch(error => console.error("Erro na requisição:", error));
};

async function comparaEndereco(){
    let localizacaoEmpresa = $("#tab-localizacao").text();
    console.log('local empresa:',localizacaoEmpresa)
    if(valorLocAtual != localizacaoEmpresa){
        loaderM("", false);
        showAlert('Você não está no local de trabalho!','error')
        return
    } else{
        // const res = await axios({
        //     url: "../../../backend/backend.php",
        //     method: "POST",
        //     data: {
        //         function: "baterPonto",
        //         locAtual: valorLocAtual
        //     },
        // })
        loaderM("", false);
        showAlert('Ponto batido com sucesso!','success')
        return
    }

}

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
