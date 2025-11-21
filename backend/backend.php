<?php
require __DIR__ . '/../vendor/autoload.php';
file_put_contents("debug.txt", file_get_contents("php://input"));

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();    

$host       = $_ENV['DB_HOST'];
$banco      = $_ENV['DB_NAME'];
$usuario    = $_ENV['DB_USER'];
$senha      = $_ENV['DB_PASS'];

try {
    $db = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(["erro" => "Erro de conexão: " . $e->getMessage()]));
}

$raw = file_get_contents("php://input");
file_put_contents("debug.txt", $raw);
$data = json_decode($raw, true);

if (isset($data['function'])) {
    $function = $data['function'];

    switch ($function) {
        case 'loadEmpresa': // Login Empresa
            loadEmpresa($db, $data);
            break;

        case 'loadFuncionario': // Load funcionário
            loadFuncionario($db, $data);
            break;
        
        case 'loadPontoFuncionario': // Load ponto funcionário
            loadPontoFuncionario($db, $data);
            break;

        case 'loadPontosFuncionario': // Load historico pontos funcionário
            loadPontosFuncionario($db, $data);
            break;

        case 'loadDadosFuncionario': // Load dados funcionário
            loadDadosFuncionario($db, $data);
            break;

        case 'loadPainel': // Carregar funcionários
            loadPainel($db);
            break;

        case 'getNomeEmpresa': // get nome empresa
            getNomeEmpresa($db, $data);
            break;

        case 'getLocaEmpresa': // get localização empresa
            getLocaEmpresa($db, $data);
            break;

        case 'getDadosFuncionario': // get dados funcionário
            getDadosFuncionario($db, $data);
            break;

        case 'getLatLongFromCEP': // get dados funcionário
            getLatLongFromCEP($db, $data);
            break;

        case 'getDadosEmpresa': // get dados empresa
            getDadosEmpresa($db, $data);
            break;

        case 'getSenhaAtualEmpresa': // get senha atual Empresa
            getSenhaAtualEmpresa($db, $data);
            break;
        
        case 'getEnderecoFromLatLong': // get endereço from lat long
            getEnderecoFromLatLong($db, $data);
            break;

        case 'applyEmpresa': // Inserir empresa
            applyEmpresa($db, $data);
            break;

        case 'applyFuncionario': // Inserir funcionário
            applyFuncionario($db, $data);
            break;
            
        case 'applyPontoFuncionario': // Inserir ponto funcionário
            applyPontoFuncionario($db, $data);
            break;

        case 'applyHorarioFuncionario': // Inserir Horario Funcionario
            applyHorarioFuncionario($db, $data);
            break;

        case 'updateHorarioFuncionario': // Atualiza Horario Funcionario
            updateHorarioFuncionario($db, $data);
            break;

        case 'updateFuncionario': 
            updateFuncionario($db, $data);
            break;

        case 'updateEmpresa': 
            updateEmpresa($db, $data);
            break;

        case 'updateLocEmpresa': 
            updateLocEmpresa($db, $data);
            break;            
            
        case 'updateSenhaEmpresa': 
            updateSenhaEmpresa($db, $data);
            break;            

        case 'deletaFuncionario': // Deletar funcionário
            deletaFuncionario($db, $data);
            break;

        default:
            echo json_encode(["error" => "Função inválida"]);
            break;
    }
} else {
    echo json_encode(["error" => "Nenhuma função especificada"]);
}

function loadEmpresa($db, $data) {
    session_start();

    if ($data) {
        $cnpjEmpresa = $data['CNPJ_EMPRESA'];
        $senhaEmpresa = $data['SENHA_EMPRESA'];

        $stmt = $db->prepare("
            SELECT ID_EMPRESA, CNPJ_EMPRESA, SENHA_EMPRESA, RAZAO_FANTASIA, 
                   RAZAO_SOCIAL, LOC_EMPRESA, LAT_EMPRESA, LONG_EMPRESA, DSC_EMPRESA, TEL_EMPRESA, EMAIL_EMPRESA
            FROM EMPRESA 
            WHERE CNPJ_EMPRESA = ? AND SENHA_EMPRESA = ?
        ");
        
        $stmt->execute([$cnpjEmpresa, $senhaEmpresa]);

        $empresa = $stmt->fetch();  // <-- AGORA SIM, usando PDO

        if ($empresa) {

            $_SESSION['empresa_razao_fantasia'] = $empresa['RAZAO_FANTASIA'];
            $_SESSION['empresa_razao_social']   = $empresa['RAZAO_SOCIAL'];
            $_SESSION['empresa_cnpj']           = $empresa['CNPJ_EMPRESA'];
            $_SESSION['empresa_loc']            = $empresa['LOC_EMPRESA'];
            $_SESSION['empresa_lat']            = $empresa['LAT_EMPRESA'];
            $_SESSION['empresa_long']           = $empresa['LONG_EMPRESA'];
            $_SESSION['empresa_dsc']            = $empresa['DSC_EMPRESA'];
            $_SESSION['empresa_tel']            = $empresa['TEL_EMPRESA'];
            $_SESSION['empresa_email']          = $empresa['EMAIL_EMPRESA'];
            $_SESSION['empresa_id']             = $empresa['ID_EMPRESA'];

            echo json_encode([
                "success" => true,
                "message" => "Login bem-sucedido!",
                "data" => $empresa
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Usuário ou senha inválidos."
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "error" => "Dados não fornecidos."
        ]);
    }
}

function loadFuncionario($db, $data) {
    session_start();

    if ($data) {
        $cpfFuncionario   = $data['CPF'];
        $senhaFuncionario = $data['SENHA_FUNCIONARIO'];

        $stmt = $db->prepare("
            SELECT ID_FUNCIONARIO, NOME_FUNCIONARIO, CPF, RG, DATA_NASCIMENTO, FACEID, FK_EMPRESA
            FROM FUNCIONARIOS 
            WHERE CPF = ? AND SENHA_FUNCIONARIO = ?
        ");
        $stmt->execute([$cpfFuncionario, $senhaFuncionario]);

        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($funcionario) {

            $_SESSION['funcionario_id']              = $funcionario['ID_FUNCIONARIO'];
            $_SESSION['funcionario_nome']            = $funcionario['NOME_FUNCIONARIO'];
            $_SESSION['funcionario_rg']              = $funcionario['RG'];
            $_SESSION['funcionario_data_nascimento'] = $funcionario['DATA_NASCIMENTO'];
            $_SESSION['funcionario_cpf']             = $funcionario['CPF'];
            $_SESSION['funcionario_faceid']          = $funcionario['FACEID'];
            $_SESSION['funcionario_fk_empresa']      = $funcionario['FK_EMPRESA'];

            $nomeEmpresa  = null;
            $empresaRow   = null;
            $horariosRow  = null;

            if (!empty($funcionario['FK_EMPRESA'])) {

                $stmtEmpresa = $db->prepare("
                    SELECT ID_EMPRESA, RAZAO_SOCIAL, RAZAO_FANTASIA, CNPJ_EMPRESA, 
                           LOC_EMPRESA, DSC_EMPRESA, TEL_EMPRESA, EMAIL_EMPRESA
                    FROM EMPRESA 
                    WHERE ID_EMPRESA = ?
                ");
                $stmtEmpresa->execute([$funcionario['FK_EMPRESA']]);

                $empresaRow = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

                if ($empresaRow) {

                    $nomeEmpresa = $empresaRow['RAZAO_FANTASIA'];

                    $_SESSION['empresa_id']             = $empresaRow['ID_EMPRESA'];
                    $_SESSION['empresa_razao_fantasia'] = $empresaRow['RAZAO_FANTASIA'];
                    $_SESSION['empresa_razao_social']   = $empresaRow['RAZAO_SOCIAL'];
                    $_SESSION['empresa_cnpj']           = $empresaRow['CNPJ_EMPRESA'];
                    $_SESSION['empresa_loc']            = $empresaRow['LOC_EMPRESA'];
                    $_SESSION['empresa_dsc']            = $empresaRow['DSC_EMPRESA'];
                    $_SESSION['empresa_tel']            = $empresaRow['TEL_EMPRESA'];
                    $_SESSION['empresa_email']          = $empresaRow['EMAIL_EMPRESA'];
                }
            }

            if (!empty($funcionario['ID_FUNCIONARIO'])) {

                $stmtHorarios = $db->prepare("
                    SELECT ENTRADA1, SAIDA1, ENTRADA2, SAIDA2
                    FROM HORARIOS_FUNCIONARIOS 
                    WHERE FK_FUNCIONARIO = ?
                ");
                $stmtHorarios->execute([$funcionario['ID_FUNCIONARIO']]);

                $horariosRow = $stmtHorarios->fetch(PDO::FETCH_ASSOC);

                if ($horariosRow) {

                    $_SESSION['entrada1'] = $horariosRow['ENTRADA1'];
                    $_SESSION['saida1']   = $horariosRow['SAIDA1'];
                    $_SESSION['entrada2'] = $horariosRow['ENTRADA2'];
                    $_SESSION['saida2']   = $horariosRow['SAIDA2'];
                }
            }

            echo json_encode([
                "success"       => true,
                "message"       => "Login bem-sucedido!",
                "data"          => $funcionario,
                "empresa"       => $empresaRow,
                "nomeEmpresa"   => $nomeEmpresa,
                "dadosHorarios" => $horariosRow
            ]);
        }

        else {
            echo json_encode([
                "success" => false,
                "error"   => "Usuário ou senha inválidos."
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "error"   => "Dados não fornecidos."
        ]);
    }
}

function loadPontoFuncionario($db, $data) {
    session_start();

    if (!isset($_SESSION['funcionario_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $idFuncionarioLogado = $_SESSION['funcionario_id'];
    $now = date('Y-m-d');

    $stmt = $db->prepare("
        SELECT DATA, ENTRADA1, STATUS_ENTRADA1, SAIDA1, STATUS_SAIDA1, ENTRADA2, STATUS_ENTRADA2, STATUS_SAIDA2, SAIDA2
        FROM PONTO 
        WHERE FK_FUNCIONARIO = ? AND DATA = ?
    ");
    
    // CORREÇÃO AQUI
    $stmt->execute([$idFuncionarioLogado, $now]);

    $pontos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "pontos" => $pontos
    ]);
    exit;
}

function loadPontosFuncionario($db, $data) {
    session_start();

    if (!isset($_SESSION['funcionario_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $idFuncionarioLogado = $_SESSION['funcionario_id'];
    $now = date('Y-m-d');

    $stmt = $db->prepare("
        SELECT DATA, ENTRADA1, STATUS_ENTRADA1, SAIDA1, STATUS_SAIDA1, ENTRADA2, STATUS_ENTRADA2, STATUS_SAIDA2, SAIDA2
        FROM PONTO 
        WHERE FK_FUNCIONARIO = ?
    ");
    
    // CORREÇÃO AQUI
    $stmt->execute([$idFuncionarioLogado]);

    $pontos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pontos = array_reverse($pontos);   
    
    echo json_encode([
        "pontos" => $pontos
    ]);
    exit;
}

function loadDadosFuncionario($db, $data) {
    session_start();

    if ($data) {

        $idFuncionarioLogado = $data['ID_FUNCIONARIO'];

        $stmt = $db->prepare("
            SELECT ID_FUNCIONARIO, NOME_FUNCIONARIO, CPF, RG, SENHA_FUNCIONARIO,
                   DATA_NASCIMENTO, FACEID, TEL_FUNCIONARIO, EMAIL_FUNCIONARIO
            FROM FUNCIONARIOS 
            WHERE ID_FUNCIONARIO = ?
        ");

        $stmt->execute([$idFuncionarioLogado]);

        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($funcionario) {

            $_SESSION['funcionario_id'] = $funcionario['ID_FUNCIONARIO'];

            echo json_encode([
                "success" => true,
                "message" => "Dados do funcionário logado:",
                "data" => $funcionario
            ]);

        } else {
            echo json_encode([
                "success" => false,
                "error" => "Funcionário não encontrado."
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "error" => "Dados não fornecidos."
        ]);
    }
}

function loadPainel($db) {
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        header("Location: index.php");
        exit;
    }

    $stmt = $db->prepare("
        SELECT ID_FUNCIONARIO, NOME_FUNCIONARIO, CPF, RG, DATA_NASCIMENTO, FACEID, 
               TEL_FUNCIONARIO, EMAIL_FUNCIONARIO, FK_EMPRESA
        FROM FUNCIONARIOS 
        WHERE FK_EMPRESA = ?
    ");

    $stmt->execute([$_SESSION['empresa_id']]);

    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($funcionarios);
}

function applyEmpresa($db, $data) {
    if ($data) {

        $nomeEmpresa    = $data['RAZAO_SOCIAL'];
        $senhaEmpresa   = $data['SENHA_EMPRESA'];
        $razaoFantasia  = $data['RAZAO_FANTASIA'];
        $cnpjEmpresa    = $data['CNPJ_EMPRESA'];
        $empresaTipo    = $data['TIPO'];

        $stmt = $db->prepare("
            INSERT INTO EMPRESA 
                (RAZAO_SOCIAL, SENHA_EMPRESA, RAZAO_FANTASIA, CNPJ_EMPRESA, TIPO) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $executou = $stmt->execute([
            $nomeEmpresa,
            $senhaEmpresa,
            $razaoFantasia,
            $cnpjEmpresa,
            $empresaTipo
        ]);

        echo json_encode(
            $executou ?
            ["message" => "Empresa cadastrada com sucesso!", "status" => "success"] :
            ["error" => "Erro ao cadastrar empresa."]
        );

    } else {
        echo json_encode(["error" => "Dados não fornecidos."]);
    }
}

function applyFuncionario($db, $data) {
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $empresaId = $_SESSION['empresa_id'];

    if ($data) {

        $nomeFuncionario  = $data['NOME_FUNCIONARIO'];
        $cpfFuncionario   = $data['CPF'];
        $rgFuncionario    = $data['RG'];
        $dataNascimento   = $data['DATA_NASCIMENTO'];
        $telFuncionario   = $data['TEL_FUNCIONARIO'];
        $emailFuncionario = $data['EMAIL_FUNCIONARIO'];
        $faceId           = $data['FACEID'];
        $senhaFuncionario = $data['SENHA_FUNCIONARIO'] ?? "";

        try {
            $stmt = $db->prepare("
                INSERT INTO FUNCIONARIOS 
                (NOME_FUNCIONARIO, CPF, RG, DATA_NASCIMENTO, FACEID, FK_EMPRESA, TIPO, 
                 SENHA_FUNCIONARIO, TEL_FUNCIONARIO, EMAIL_FUNCIONARIO) 
                VALUES (:nome, :cpf, :rg, :dataNascimento, :faceId, :empresaId, :tipo, 
                        :senha, :telefone, :email)
            ");

            $executou = $stmt->execute([
                ':nome'          => $nomeFuncionario,
                ':cpf'           => $cpfFuncionario,
                ':rg'            => $rgFuncionario,
                ':dataNascimento'=> $dataNascimento,
                ':faceId'        => $faceId,
                ':empresaId'     => $empresaId,
                ':tipo'          => 'F',
                ':senha'         => $senhaFuncionario,
                ':telefone'      => $telFuncionario,
                ':email'         => $emailFuncionario
            ]);

            if ($executou) {
                // Pega o ID do funcionário recém-cadastrado
                $ultimoId = $db->lastInsertId();

                echo json_encode([
                    "success" => true,
                    "message" => "Cadastrado com sucesso.",
                    "id_funcionario" => $ultimoId
                ]);
                return;
            } else {
                $errorInfo = $stmt->errorInfo();
                echo json_encode([
                    "success" => false,
                    "message" => "Erro ao cadastrar funcionário.",
                    "mysql_error" => $errorInfo
                ]);
                return;
            }

        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "CPF Já Cadastrado.",
                "exception" => $e->getMessage()
            ]);
            return;
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos."
        ]);
    }
}

function applyHorarioFuncionario($db, $data) {
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $empresaId = $_SESSION['empresa_id'];

    if ($data) {

        $entrada1      = $data['ENTRADA1'];
        $saida1        = $data['SAIDA1'];
        $entrada2      = $data['ENTRADA2'];
        $saida2        = $data['SAIDA2'];
        $idFuncionario = $data['ID_FUNCIONARIO'];
        $dataAtual     = date('Y-m-d');

        $stmt = $db->prepare("
            INSERT INTO HORARIOS_FUNCIONARIOS
            (FK_FUNCIONARIO, FK_EMPRESA, ENTRADA1, SAIDA1, ENTRADA2, SAIDA2, DATA) 
            VALUES (:idFunc, :idEmpresa, :entrada1, :saida1, :entrada2, :saida2, :data)
        ");

        $executou = $stmt->execute([
            ':idFunc'    => $idFuncionario,
            ':idEmpresa' => $empresaId,
            ':entrada1'  => $entrada1,
            ':saida1'    => $saida1,
            ':entrada2'  => $entrada2,
            ':saida2'    => $saida2,
            ':data'      => $dataAtual
        ]);

        if($executou){
            echo json_encode(
                ["success" => true, "message" => "Horário cadastrado com sucesso!"]
            );
        } else {
            echo json_encode(
                ["success" => false, "message" => "Erro ao cadastrar horário."]
            );
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos."
        ]);
    }
}

function applyPontoFuncionario($db, $data) {
    session_start();

    if (!isset($_SESSION['funcionario_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $funcionarioId = $_SESSION['funcionario_id'];
    $empresaId     = $_SESSION['funcionario_fk_empresa'];
    $nomeFuncionario = $_SESSION['funcionario_nome'];

    $stmtt = $db->prepare("
        SELECT ENTRADA1, SAIDA1, ENTRADA2, SAIDA2 
        FROM HORARIOS_FUNCIONARIOS
        WHERE FK_FUNCIONARIO = ?
    ");
    $stmtt->execute([$funcionarioId]);
    $horarios = $stmtt->fetch(PDO::FETCH_ASSOC);

    if (!$horarios) {
        echo json_encode(["success" => false, "message" => "Nenhum horário encontrado para este funcionário."]);
        exit;
    }

    $dataAtual = date("Y-m-d");

    $stmtPonto = $db->prepare("
        SELECT * FROM PONTO 
        WHERE FK_FUNCIONARIO = ? AND DATA = ?
    ");
    $stmtPonto->execute([$funcionarioId, $dataAtual]);
    $ponto = $stmtPonto->fetch(PDO::FETCH_ASSOC);

    date_default_timezone_set("America/Sao_Paulo");
    $now = date("H:i:s");

    $ordemCampos = [
        "ENTRADA1" => $horarios['ENTRADA1'],
        "SAIDA1"   => $horarios['SAIDA1'],
        "ENTRADA2" => $horarios['ENTRADA2'],
        "SAIDA2"   => $horarios['SAIDA2']
    ];

    $campoAtual = null;
    foreach ($ordemCampos as $coluna => $horaProgramada) {
        if (!isset($ponto[$coluna]) || empty($ponto[$coluna])) {
            $campoAtual = $coluna;
            $horaProgramadaColuna = $horaProgramada;
            break;
        }
    }

    if (!$campoAtual) {
        echo json_encode(["success" => false, "message" => "Todas as batidas do dia já foram registradas."]);
        exit;
    }


    $diferenca = strtotime($now) - strtotime($horaProgramadaColuna);

    if (abs($diferenca) <= 300) { // 5 minutos
        $status = "CONCLUIDO";
    } elseif ($diferenca > 300) {
        $status = "ATRASADO";
    } else {
        $status = "ADIANTADO";
    }

    if (!$ponto) {
        // INSERT
        $stmtInsert = $db->prepare("
            INSERT INTO PONTO 
            (FK_FUNCIONARIO, FK_EMPRESA, NOME_FUNCIONARIO, $campoAtual, STATUS_$campoAtual, DATA)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $ok = $stmtInsert->execute([
            $funcionarioId,
            $empresaId,
            $nomeFuncionario,
            $now,
            $status,
            $dataAtual
        ]);

    } else {
        // UPDATE
        $stmtUpdate = $db->prepare("
            UPDATE PONTO
            SET $campoAtual = ?, STATUS_$campoAtual = ?
            WHERE ID_PONTO = ?
        ");

        $ok = $stmtUpdate->execute([$now, $status, $ponto['ID_PONTO']]);
    }

    echo json_encode([
        "success" => $ok,
        "campoRegistrado" => $campoAtual,
        "hora" => $now,
        "status" => $status,
        "message" => $ok ? "Ponto registrado com sucesso!" : "Erro ao registrar o ponto."
    ]);
}

function getNomeEmpresa($db, $data){
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $empresaId = $_SESSION['empresa_id'];

    $stmt = $db->prepare("SELECT RAZAO_FANTASIA FROM EMPRESA WHERE ID_EMPRESA = :id");
    $stmt->execute([":id" => $empresaId]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getLatLongFromCEP($db, $data){
    header("Content-Type: application/json; charset=UTF-8");

    if (!isset($data['CEP']) || empty($data['CEP'])) {
        echo json_encode([
            "success" => false,
            "message" => "CEP não enviado."
        ]);
        exit;
    }

    $cep = preg_replace('/\D/', '', $data['CEP']); // limpa caracteres não numéricos
    $token = trim($_ENV['TOKEN_CEP']);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.cepaberto.com/api/v3/cep?cep={$cep}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Token token={$token}",
            "Accept: application/json"
        ]
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($response === false || $httpCode !== 200) {
        echo json_encode([
            "success" => false,
            "message" => "Erro ao acessar a API CepAberto.",
            "http_code" => $httpCode
        ]);
        exit;
    }

    $json = json_decode($response, true);

    if (!$json || !isset($json["latitude"])) {
        echo json_encode([
            "success" => false,
            "message" => "Coordenadas não encontradas no CEP."
        ]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "data" => [
            "lat" => $json["latitude"],
            "lon" => $json["longitude"]
        ]
    ]);
    exit;
}

function getEnderecoFromLatLong($db, $data) {
    header("Content-Type: application/json; charset=UTF-8");

    if (!isset($data['lat']) || !isset($data['lon'])) {
        echo json_encode([
            "success" => false,
            "message" => "Latitude ou longitude não enviados."
        ]);
        exit;
    }

    $lat = trim($data['lat']);
    $lon = trim($data['lon']);

    // URL do Nominatim
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";

    // Obrigatório: definir User-Agent para evitar bloqueio
    $opts = [
        "http" => [
            "header" => "User-Agent: MeuSistema/1.0\r\n"
        ]
    ];

    $context = stream_context_create($opts);

    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        echo json_encode([
            "success" => false,
            "message" => "Erro ao conectar ao Nominatim."
        ]);
        exit;
    }

    $json = json_decode($response, true);

    if (!isset($json["address"])) {
        echo json_encode([
            "success" => false,
            "message" => "Endereço não encontrado."
        ]);
        exit;
    }

    $a = $json["address"];

    echo json_encode([
        "success" => true,
        "data" => [
            "numero"  => $a["house_number"] ?? "",
            "rua"     => $a["road"] ?? ($a["pedestrian"] ?? ($a["footway"] ?? "")),
            "bairro"  => $a["suburb"] ?? ($a["neighbourhood"] ?? ""),
            "cidade"  => $a["city"] ?? ($a["town"] ?? ($a["village"] ?? ($a["city_district"] ?? ""))),
            "estado"  => $a["state"] ?? "",
            "cep"     => $a["postcode"] ?? "",
            "lat"     => $lat,
            "lon"     => $lon
        ]
    ]);
    exit;
}

function getLocaEmpresa($db, $data){
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $empresaId = $_SESSION['empresa_id'];

    $stmt = $db->prepare("SELECT LOC_EMPRESA, LAT_EMPRESA, LONG_EMPRESA FROM EMPRESA WHERE ID_EMPRESA = ?");
    $stmt->execute([$empresaId]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getSenhaAtualEmpresa($db, $data){
    session_start();

    if ($data) {

        $senhaAtual = $data['SENHA_EMPRESA'];
        $idEmpresa  = $data['ID_EMPRESA'];

        $stmt = $db->prepare("
            SELECT ID_EMPRESA 
            FROM EMPRESA 
            WHERE ID_EMPRESA = ? AND SENHA_EMPRESA = ?
        ");

        $stmt->execute([$idEmpresa, $senhaAtual]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                "success" => true,
                "message" => "Senha correta."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Senha incorreta."
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos."
        ]);
    }
}

function getDadosFuncionario($db, $data){
    session_start(); 

    if (!isset($_SESSION['id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $idFuncionario = $_SESSION['id'];

    $stmt = $db->prepare("
        SELECT 
            ID_FUNCIONARIO, 
            NOME_FUNCIONARIO, 
            CPF, 
            RG, 
            DATA_NASCIMENTO, 
            FACEID 
        FROM FUNCIONARIOS 
        WHERE ID_FUNCIONARIO = ?
    ");

    $stmt->execute([$idFuncionario]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getDadosEmpresa($db, $data){
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $idEmpresa = $_SESSION['empresa_id'];

    $stmt = $db->prepare("
        SELECT 
            ID_EMPRESA, 
            RAZAO_SOCIAL, 
            RAZAO_FANTASIA, 
            CNPJ_EMPRESA, 
            TEL_EMPRESA, 
            EMAIL_EMPRESA, 
            LOC_EMPRESA, 
            DSC_EMPRESA 
        FROM EMPRESA 
        WHERE ID_EMPRESA = ?
    ");

    $stmt->execute([$idEmpresa]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function updateFuncionario($db, $data){
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode(["success" => false, "message" => "Sessão expirada. Faça login novamente."]);
        exit;
    }

    if ($data && isset($data['ID_FUNCIONARIO'])) {

        $idFuncionario   = $data['ID_FUNCIONARIO'];
        $nome            = $data['NOME_FUNCIONARIO'];
        $cpf             = $data['CPF'];
        $rg              = $data['RG'];
        $dataNascimento  = $data['DATA_NASCIMENTO'];
        $email           = $data['EMAIL_FUNCIONARIO'];
        $faceId          = $data['FACEID'];
        $senha           = $data['SENHA_FUNCIONARIO'] ?? '';

        
            $stmt = $db->prepare("
                UPDATE FUNCIONARIOS SET 
                    NOME_FUNCIONARIO = ?, 
                    CPF = ?, 
                    RG = ?, 
                    DATA_NASCIMENTO = ?, 
                    FACEID = ?, 
                    SENHA_FUNCIONARIO = ?,
                    EMAIL_FUNCIONARIO = ?
                WHERE ID_FUNCIONARIO = ?
            ");

            $executou = $stmt->execute([$nome, $cpf, $rg, $dataNascimento, $faceId, $senha, $email, $idFuncionario]);

        echo json_encode([
            "success" => $executou ? true : false,
            "message" => $executou ? "Funcionário atualizado com sucesso!" : "Erro ao atualizar funcionário.",
            "id_funcionario" => $idFuncionario
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Dados não fornecidos ou ID ausente."]);
    }
}

function updateHorarioFuncionario($db, $data){
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode(["success" => false, "message" => "Sessão expirada. Faça login novamente."]);
        exit;
    }

    if ($data && isset($data['ID_EMPRESA'])) {

        $idFuncionario = $data['ID_FUNCIONARIO'];
        $entrada1      = $data['ENTRADA1'];
        $saida1        = $data['SAIDA1'];
        $entrada2      = $data['ENTRADA2'];
        $saida2        = $data['SAIDA2'];

        $stmt = $db->prepare("
            UPDATE HORARIOS_FUNCIONARIOS 
            SET ENTRADA1 = ?, SAIDA1 = ?, ENTRADA2 = ?, SAIDA2 = ?
            WHERE FK_FUNCIONARIO = ?
        ");

        $executou = $stmt->execute([$entrada1, $saida1, $entrada2, $saida2, $idFuncionario]);

        echo json_encode([
            "success" => $executou ? true : false,
            "message" => $executou ? "Horário atualizado com sucesso!" : "Erro ao atualizar horário."
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Dados não fornecidos ou ID ausente."]);
    }
}

function updateEmpresa($db, $data){
    session_start();
    $idEmpresa = $_SESSION['empresa_id'] ?? null;

    if ($data && $idEmpresa) {
        $nomeFantasia = $data['RAZAO_FANTASIA'];
        $cnpj         = $data['CNPJ_EMPRESA'];
        $email        = $data['EMAIL_EMPRESA'];
        $telefone     = $data['TEL_EMPRESA'];
        $dscEmpresa   = $data['DSC_EMPRESA'];

        $stmt = $db->prepare("
            UPDATE EMPRESA SET 
                RAZAO_FANTASIA = ?, 
                CNPJ_EMPRESA = ?, 
                EMAIL_EMPRESA = ?, 
                TEL_EMPRESA = ?, 
                DSC_EMPRESA = ?
            WHERE ID_EMPRESA = ?
        ");

        $executou = $stmt->execute([$nomeFantasia, $cnpj, $email, $telefone, $dscEmpresa, $idEmpresa]);

        echo json_encode([
            "success" => $executou ? true : false,
            "message" => $executou ? "Empresa atualizada com sucesso!" : "Erro ao atualizar empresa."
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Dados não fornecidos ou ID ausente."]);
    }
}

function updateLocEmpresa($db, $data){
    session_start();
    $idEmpresa = $_SESSION['empresa_id'] ?? null;

    if ($data && $idEmpresa) {

        $endereco = $data['LOC_EMPRESA'];
        $lat = $data['LAT_EMPRESA'];
        $lon = $data['LONG_EMPRESA'];

        $stmt = $db->prepare("UPDATE EMPRESA SET LOC_EMPRESA = ?, LAT_EMPRESA = ?, LONG_EMPRESA = ? WHERE ID_EMPRESA = ?");
        $executou = $stmt->execute([$endereco, $lat, $lon, $idEmpresa]);

        echo json_encode([
            "success" => $executou ? true : false,
            "message" => $executou ? "Endereço atualizado com sucesso!" : "Erro ao atualizar o endereço."
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Dados não fornecidos ou ID ausente."]);
    }
}

function updateSenhaEmpresa($db, $data){
    session_start();

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode(["success" => false, "message" => "Sessão expirada."]);
        exit;
    }

    if ($data && isset($data['ID_EMPRESA'])) {

        $novaSenha = $data['SENHA_EMPRESA'];
        $idEmpresa = $data['ID_EMPRESA'];

        $stmt = $db->prepare("UPDATE EMPRESA SET SENHA_EMPRESA = ? WHERE ID_EMPRESA = ?");
        $executou = $stmt->execute([$novaSenha, $idEmpresa]);

        echo json_encode([
            "success" => $executou ? true : false,
            "message" => $executou ? "Senha atualizada com sucesso!" : "Erro ao atualizar senha."
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Dados não fornecidos ou ID ausente."]);
    }
}

function deletaFuncionario($db, $data){
    $id = (int)$data['ID_FUNCIONARIO'];

    try {
        // 2) BUSCA A EMPRESA DO FUNCIONÁRIO
        $getEmpresaStmt = $db->prepare("SELECT FK_EMPRESA FROM FUNCIONARIOS WHERE ID_FUNCIONARIO = ?");
        $getEmpresaStmt->bindValue(1, $id, PDO::PARAM_INT);
        $getEmpresaStmt->execute();

        $row = $getEmpresaStmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(["error" => "Funcionário não encontrado."]);
            return;
        }

        $fkEmpresa = $row['FK_EMPRESA'];

        // 3) DELETA TODOS OS HORÁRIOS DO FUNCIONÁRIO
        $delHorariosStmt = $db->prepare("DELETE FROM horarios_funcionarios WHERE FK_FUNCIONARIO = ?");
        $delHorariosStmt->bindValue(1, $id, PDO::PARAM_INT);
        $delHorariosStmt->execute();

        // 4) DELETA O FUNCIONÁRIO
        $delFuncionarioStmt = $db->prepare("DELETE FROM FUNCIONARIOS WHERE ID_FUNCIONARIO = ? AND FK_EMPRESA = ?");
        $delFuncionarioStmt->bindValue(1, $id, PDO::PARAM_INT);
        $delFuncionarioStmt->bindValue(2, $fkEmpresa, PDO::PARAM_INT);
        $delFuncionarioStmt->execute();

        // 5) CONTA QUANTOS FUNCIONÁRIOS RESTARAM NA EMPRESA
        $countStmt = $db->prepare("SELECT COUNT(*) AS total FROM FUNCIONARIOS WHERE FK_EMPRESA = ?");
        $countStmt->bindValue(1, $fkEmpresa, PDO::PARAM_INT);
        $countStmt->execute();
        $countRow = $countStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "message" => "Funcionário e todos os horários deletados com sucesso!",
            "empresa_id" => $fkEmpresa,
            "total_funcionarios_empresa" => $countRow['total']
        ]);

    } catch (PDOException $e) {
        echo json_encode(["error" => "Erro ao deletar funcionário ou horários.", "exception" => $e->getMessage()]);
    }
}




