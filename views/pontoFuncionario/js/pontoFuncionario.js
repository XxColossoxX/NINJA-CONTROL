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
        $('#inputLocalizacaoEmpresa').val(localizacaoEmpresa);

        const $modalFunc = $('#modal-bater-ponto-funcionario');
        if ($modalFunc.length) { $modalFunc.removeClass('hidden'); }
        else { $('#modal-bater-ponto').removeClass('hidden'); }
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
        const $modalFunc = $('#modal-bater-ponto-funcionario');
        if ($modalFunc.length) { $modalFunc.addClass('hidden'); }
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
                $('#inputLatitude').val(lat);
                $('#inputLongitude').val(lng);
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
    const apiKey = (window.APP_CONFIG && window.APP_CONFIG.googleMapsApiKey) ? window.APP_CONFIG.googleMapsApiKey : '';
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
$(function(){
    // Fechar o modal do formulário
    $('#close-form-modal').on('click', function(){
        $('#form-modal').addClass('hidden');
    });

    // Fechar o modal de captura de rosto
    $('#close-camera-modal').on('click', function(){
        const video = $('#register-camera')[0];
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        $('#camera-modal').addClass('hidden');
    });

    // Menu hambúrguer
    $('#menu-toggle').on('click', function(){
        $('#menu').removeClass('menu-hidden').addClass('menu-visible');
    });
    $('#menu-close').on('click', function(){
        $('#menu').removeClass('menu-visible').addClass('menu-hidden');
    });

    // Abrir modal de formulário de funcionário
    $('#add-employee-btn').on('click', function(){
        $('#form-modal').removeClass('hidden');
    });

    $('#btnCloseModal').on('click', function(){
        $('#modal-bater-ponto').addClass('hidden');
    });
});
//#endregion
});