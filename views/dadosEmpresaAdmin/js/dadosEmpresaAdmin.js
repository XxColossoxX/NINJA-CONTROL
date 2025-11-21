let foto64 = ""; 
let cameraStream = null;

const endereco = {
    numero: 0 ,
    rua: '',
    bairro: '',
    estado: '',
    cep: ''
};


$(document).ready( async function() {    
    $('#controlador').removeClass('hidden');

    getDadosEmpresa()

    Inputmask("99.999.999/9999-99").mask("#inputCnpj");
    Inputmask("(99)99999-9999").mask("#inputTelefone");

    $('#btn-editar-empresa').on('click', function () {
        $('#modal-editar-empresa').removeClass('hidden');
    });

    $('#fechar-modal-edicao').on('click', function () {
        $('#modal-editar-empresa').addClass('hidden');
    });

    $("#btnLoc").on('click', async function(){
        await locEmpresa();
    });

    $("#btnSalvar").on('click', async function() {
        const nome = $('#inputNomeFantasia').val();
        const endereco = $('#inputEndereco').val();
        const telefone = $('#inputTelefone').val();
        const email = $('#inputEmail').val();
        const descricao = $('#inputDescricao').val();
        const cnpj = $('#inputCnpj').val();

        const res = await axios({
            url: "../../backend/backend.php",
            method: "POST",
            data: {
                function: "updateEmpresa",
                RAZAO_FANTASIA: nome,
                TEL_EMPRESA: telefone,
                EMAIL_EMPRESA: email,
                DSC_EMPRESA: descricao,
                CNPJ_EMPRESA: cnpj,
                ID_EMPRESA: sessionStorage.getItem('empresa_id')
            },
        });  
        if(res.data.success){
            showAlert('Dados atualizados com sucesso!',"success");
            $("#modal-editar-empresa").addClass("hidden");
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert('Erro ao atualizar dados. Tente novamente.',"error");
        }
    });

    $("#btnSalvarEndereco").on('click', function(){
        updateDadosLoc();
    });

    $("#fechar-modal-edicao").on('click', function(){
        setTimeout(() => {
            location.reload();
        }, 10);
    });

    $("#inputCep").on('input',function(){
        let cep = $(this).val();

        if(cep.length === 8){
            buscarEnderecoPorCepGoogle(cep);
        }
    });

    $("#abrir-modal-endereco").on('click',function(){
        verificaLoc();
    });

    const abrirModalEndereco = $("#abrir-modal-endereco");
    const modalEndereco = $("#modal-endereco");
    const fecharModalEndereco = $("#fechar-modal-endereco");

    abrirModalEndereco.on("click", function(){
        modalEndereco.removeClass("hidden");
    });

    fecharModalEndereco.on("click", function(){
        modalEndereco.addClass("hidden");
    })

    //FUNCOES:
    async function locEmpresa() {
        loaderM('Carregando Localização',true)
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    await getEndereco(lat, lng);
                },
                (error) => {
                    console.error("Erro ao obter localização:", error.message);
                    loaderM('Carregando Localização',false)
                    showAlert("Erro ao obter localização. Verifique as permissões do navegador.","error")
                },
                {
                    enableHighAccuracy: true, // Força o uso do GPS com mais precisão
                    timeout: 5000, // Tempo máximo para tentar pegar a localização
                    maximumAge: 0 // Não usar dados de localização cacheados
                }
            );
        }
    };


    async function getEndereco(lat, lon) {
        const apiKey = "919808c69e2e4fe9807e06972f271824"; // coloque sua chave aqui
        const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lon}&key=${apiKey}&language=pt-BR`;
        latAtualValor = lat;
        longAtualValor = lon;


        try {
            const res = await fetch(url);
            const data = await res.json();

            if (data.results.length === 0) {
                return null;
            }

            const info = data.results[0];

            let endereco = {
                rua: info.components.road ?? "",
                bairro: info.components.suburb ?? "",
                cidade: info.components.city ?? info.components.town ?? "",
                estado: info.components.state ?? "",
                numero: info.components.house_number ?? "",
                cep: info.components.postcode ?? ""
            };

            valorLocAtual = `${endereco.rua}, ${endereco.bairro}, ${endereco.cep}, ${endereco.cidade}, ${endereco.estado}`;
                $("#inputRua").val(endereco.rua);
                $("#inputNro").val(endereco.numero);
                $("#inputBairro").val(endereco.bairro);
                $("#inputCidade").val(endereco.cidade);
                $("#inputEstado").val(endereco.estado);
                $("#inputCep").val(endereco.cep);
                $("#inputLat").val(lat);
                $("#inputLong").val(lon);
                loaderM("", false);

        } catch (err) {
            console.error("Erro ao buscar endereço no OpenCage:", err);
            return null;
        }
    };

    async function getDadosEmpresa(){
        const res = await axios({
            url: "../../../backend/backend.php",
            method: "POST",
            data: {
                function: "getDadosEmpresa",
            },
        })
        console.log(res.data);
        if(res.data.length > 0){
            const empresa = res.data[0];
            $('#inputNomeFantasia').val(empresa.RAZAO_FANTASIA);
            $('#inputCnpj').val(empresa.CNPJ_EMPRESA);
            $('#inputEndereco').val(empresa.LOC_EMPRESA);
            $('#inputTelefone').val(empresa.TEL_EMPRESA);
            $('#inputEmail').val(empresa.EMAIL_EMPRESA);
            $('#inputDescricao').val(empresa.DSC_EMPRESA);

            $('#spanNome').text(empresa.RAZAO_FANTASIA);
            $('#spanCnpj').text(empresa.CNPJ_EMPRESA);
            $('#spanEndereco').text(empresa.LOC_EMPRESA);
            $('#spanTelefone').text(empresa.TEL_EMPRESA);
            $('#spanEmail').text(empresa.EMAIL_EMPRESA);
            $('#spanDescricao').text(empresa.DSC_EMPRESA);
        } else {
            showAlert('Erro ao carregar dados da empresa.',"error");
        }
    };

    async function buscarEnderecoPorCepGoogle(cep) {
        const url = `https://viacep.com.br/ws/${cep}/json/`;
        loaderM('Buscando endereço pelo CEP', true);

        try {
            const response = await axios.get(url);
            const data = response.data;

            if (!data.erro) {
                endereco.rua = data.logradouro;
                endereco.bairro = data.bairro;
                endereco.cidade = data.localidade;
                endereco.estado = data.estado;
                endereco.cep = data.cep;

                $("#inputRua").val(endereco.rua);
                $("#inputBairro").val(endereco.bairro);
                $("#inputCidade").val(endereco.cidade);
                $("#inputEstado").val(endereco.estado);
                $("#inputCep").val(endereco.cep);
                $("#inputNro").val('');

                await getLatLngByCep(cep)
                loaderM("", false);

            } else {
                loaderM('Buscando endereço pelo CEP', false);
                showAlert("CEP não encontrado", "warning");
            }
        } catch (e) {
            showAlert("Erro ao conectar com o ViaCEP", "error");
            loaderM('Buscando endereço pelo CEP', false);

        }
    };

    async function getLatLngByCep(cep) {
        try {
            const res = await axios({
                url: "/backend/backend.php",
                method: "POST",
                data: {
                    function: "getLatLongFromCEP",
                    CEP: cep
                },
                Headers: { "Content-Type": "application/json" }
            });
            if (res.data.success) {
                return $("#inputLat").val(res.data.data['lat']), $("#inputLong").val(res.data.data['lon']);
            }
        } catch (error) {
            console.error("Erro ao obter lat/lng do CEP:", error);
            return null;
        }
    };

    async function updateDadosLoc(){
        endereco.cep = $("#inputCep").val();
        endereco.rua = $("#inputRua").val();
        endereco.numero = $("#inputNro").val();
        endereco.bairro = $("#inputBairro").val();
        endereco.cidade = $("#inputCidade").val();
        endereco.estado = $("#inputEstado").val();

        const enderecoString = [
            endereco.rua,
            endereco.numero,
            endereco.bairro,
            endereco.cep,
            endereco.cidade,
            endereco.estado
          ].join(", ");          

        const res = await axios({
            url: "../../backend/backend.php",
            method: "POST",
            data: {
                function: "updateLocEmpresa",
                ID_EMPRESA: sessionStorage.getItem('empresa_id'),
                LOC_EMPRESA: enderecoString,
                LAT_EMPRESA: $("#inputLat").val(),
                LONG_EMPRESA: $("#inputLong").val()

            },
        });  
        if(res.data.success){
            showAlert("Localização Inserida","success")
            $("#modal-endereco").hide()
        }
    };

    async function verificaLoc(){
        const res = await axios({
            url: "../../../backend/backend.php",
            method: "POST",
            data: {
                function: "getLocaEmpresa",
            },
        });
        if(res.data){
            let loc =! '' ? res.data[0]['LOC_EMPRESA'] : 'Endereço não disponível';
            locSeparada = loc.split(', ');
            $("#inputRua").val(locSeparada[0])
            $("#inputNro").val(locSeparada[1]);

            $("#inputBairro").val(locSeparada[2]);
            $("#inputCep").val(locSeparada[3]);
            $("#inputCidade").val(locSeparada[4]);
            $("#inputEstado").val(locSeparada[5]);
        }
    };
});
