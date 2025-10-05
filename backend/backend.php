<?php
$db = new SQLite3(__DIR__ . '/../dataBase/bancoDados.db');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['function'])) {
    $function = $data['function'];

    switch ($function) {
        case 'loadEmpresa': // Login Empresa
            loadEmpresa($db, $data);
            break;

        case 'loadFuncionario': // Load funcionário
            loadFuncionario($db, $data);
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

        case 'getLocEmpresa': // get localização empresa
            getLocEmpresa($db, $data);
            break;

        case 'getDadosFuncionario': // get dados funcionário
            getDadosFuncionario($db, $data);
            break;

        case 'getDadosEmpresa': // get dados empresa
            getDadosEmpresa($db, $data);
            break;

        case 'getSenhaAtualEmpresa': // get senha atual Empresa
            getSenhaAtualEmpresa($db, $data);
            break;

        case 'applyEmpresa': // Inserir empresa
            applyEmpresa($db, $data);
            break;

        case 'applyFuncionario': // Inserir funcionário
            applyFuncionario($db, $data);
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

function loadEmpresa($db, $data){
    session_start(); 

    if ($data) {
        $cnpjEmpresa = $data['CNPJ_EMPRESA'];
        $senhaEmpresa = $data['SENHA_EMPRESA'];

        $stmt = $db->prepare("SELECT ID_EMPRESA, CNPJ_EMPRESA, SENHA_EMPRESA, RAZAO_FANTASIA, RAZAO_SOCIAL, LOC_EMPRESA, DSC_EMPRESA, TEL_EMPRESA, EMAIL_EMPRESA FROM EMPRESA WHERE CNPJ_EMPRESA = ? AND SENHA_EMPRESA = ?");
        $stmt->bindValue(1, $cnpjEmpresa);
        $stmt->bindValue(2, $senhaEmpresa);

        $result = $stmt->execute();

        if ($result) {
            $empresa = $result->fetchArray(SQLITE3_ASSOC);
            if ($empresa) {
            
                $_SESSION['empresa_razao_fantasia'] = $empresa['RAZAO_FANTASIA'];
                $_SESSION['empresa_razao_social'] = $empresa['RAZAO_SOCIAL'];
                $_SESSION['empresa_cnpj'] = $empresa['CNPJ_EMPRESA'];
                $_SESSION['empresa_loc'] = $empresa['LOC_EMPRESA'];
                $_SESSION['empresa_dsc'] = $empresa['DSC_EMPRESA'];
                $_SESSION['empresa_tel'] = $empresa['TEL_EMPRESA'];
                $_SESSION['empresa_email'] = $empresa['EMAIL_EMPRESA'];
                $_SESSION['empresa_id'] = $empresa['ID_EMPRESA'];

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
                "error" => "Erro ao executar a consulta."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Dados não fornecidos."
        ]);
    }
}

function loadFuncionario($db, $data){
    session_start();
    if ($data) {
        $cpfFuncionario = $data['CPF'];
        $senhaFuncionario = $data['SENHA_FUNCIONARIO'];

        $stmt = $db->prepare("SELECT ID_FUNCIONARIO, NOME_FUNCIONARIO, CPF, RG, DATA_NASCIMENTO, FACEID, FK_EMPRESA FROM FUNCIONARIOS WHERE CPF = ? AND SENHA_FUNCIONARIO = ?");
        $stmt->bindValue(1, $cpfFuncionario);
        $stmt->bindValue(2, $senhaFuncionario);

        $result = $stmt->execute();

        if ($result) {
            $idFuncionario = $result->fetchArray(SQLITE3_ASSOC);
            if ($idFuncionario) {
                $_SESSION['funcionario_id'] = $idFuncionario['ID_FUNCIONARIO'];
                $_SESSION['funcionario_nome'] = $idFuncionario['NOME_FUNCIONARIO'];
                $_SESSION['funcionario_rg'] = $idFuncionario['RG'];
                $_SESSION['funcionario_data_nascimento'] = $idFuncionario['DATA_NASCIMENTO'];
                $_SESSION['funcionario_cpf'] = $idFuncionario['CPF'];
                $_SESSION['funcionario_faceid'] = $idFuncionario['FACEID'];
                $_SESSION['funcionario_fk_empresa'] = $idFuncionario['FK_EMPRESA'];

                if (!empty($idFuncionario['FK_EMPRESA'])) {
                    $stmtEmpresa = $db->prepare("SELECT RAZAO_SOCIAL, RAZAO_FANTASIA, CNPJ_EMPRESA, LOC_EMPRESA, DSC_EMPRESA, TEL_EMPRESA, EMAIL_EMPRESA FROM EMPRESA WHERE ID_EMPRESA = ?");
                    $stmtEmpresa->bindValue(1, $idFuncionario['FK_EMPRESA']);

                    $resultEmpresa = $stmtEmpresa->execute();
                    $empresaRow = $resultEmpresa->fetchArray(SQLITE3_ASSOC);

                    if ($empresaRow) {
                        $nomeEmpresa = $_SESSION['empresa_razao_fantasia'] = $empresaRow['RAZAO_FANTASIA'];
                        $_SESSION['empresa_razao_social'] = $empresaRow['RAZAO_SOCIAL'];
                        $_SESSION['empresa_cnpj'] = $empresaRow['CNPJ_EMPRESA'];
                        $_SESSION['empresa_loc'] = $empresaRow['LOC_EMPRESA'];
                        $_SESSION['empresa_dsc'] = $empresaRow['DSC_EMPRESA'];
                        $_SESSION['empresa_tel'] = $empresaRow['TEL_EMPRESA'];
                        $_SESSION['empresa_email'] = $empresaRow['EMAIL_EMPRESA'];
                    }
                }

                echo json_encode([
                    "success" => true,
                    "message" => "Login bem-sucedido!",
                    "data" => $idFuncionario,
                    "nomeEmpresa" => $nomeEmpresa
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
                "error" => "Erro ao executar a consulta."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Dados não fornecidos."
        ]);
    }
}

function loadDadosFuncionario($db, $data){
    session_start();

    if ($data) {
        $idFuncionarioLogado = $data['ID_FUNCIONARIO'];
     
        $stmt = $db->prepare("SELECT ID_FUNCIONARIO, NOME_FUNCIONARIO, CPF, RG, SENHA_FUNCIONARIO, DATA_NASCIMENTO, FACEID, TEL_FUNCIONARIO, EMAIL_FUNCIONARIO FROM FUNCIONARIOS WHERE ID_FUNCIONARIO = ?");

        $stmt->bindValue(1, $idFuncionarioLogado);

        $result = $stmt->execute();

        if ($result) {
            $funcionario = $result->fetchArray(SQLITE3_ASSOC);
            if ($funcionario) {
                
                $_SESSION['funcionario_id'] = $funcionario['ID_FUNCIONARIO'];

                echo json_encode([
                    "success" => true,
                    "message" => "Dados Funcionario Logado: ",
                    "data" => $funcionario
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "error" => "Funcionario não encontrado"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Erro ao executar a consulta."
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

    $result = $db->query("SELECT * FROM funcionarios WHERE FK_EMPRESA = " . $_SESSION['empresa_id']);
    $funcionarios = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $funcionarios[] = $row;
    }

    echo json_encode($funcionarios);
}

function applyEmpresa($db, $data){
    if ($data) {

        $nomeEmpresa = $data['RAZAO_SOCIAL'];
        $senhaEmpresa = $data['SENHA_EMPRESA'];
        $razaoFantasia = $data['RAZAO_FANTASIA'];
        $cnpjEmpresa = $data['CNPJ_EMPRESA'];
        $empresaTipo = $data['TIPO'];

        $stmt = $db->prepare("INSERT INTO EMPRESA (RAZAO_SOCIAL, SENHA_EMPRESA, RAZAO_FANTASIA, CNPJ_EMPRESA, TIPO) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $nomeEmpresa);
        $stmt->bindValue(2, $senhaEmpresa);
        $stmt->bindValue(3, $razaoFantasia);
        $stmt->bindValue(4, $cnpjEmpresa);
        $stmt->bindValue(5, $empresaTipo);



        if ($stmt->execute()) {
            echo json_encode(["message" => "Funcionário cadastrado com sucesso!",
                                     "status" => "success"]);
        } else {
            echo json_encode(["error" => "Erro ao cadastrar funcionário."]);
        }
    }
}

function applyFuncionario($db, $data){
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
        $nomeFuncionario = $data['NOME_FUNCIONARIO'];
        $cpfFuncionario = $data['CPF'];
        $rgFuncionario = $data['RG'];
        $dataNascimento = $data['DATA_NASCIMENTO'];
        $telFuncionario = $data['TEL_FUNCIONARIO'];
        $emailFuncionario = $data['EMAIL_FUNCIONARIO'];
        $faceId = $data['FACEID'];
        $senhaFuncionario = isset($data['SENHA_FUNCIONARIO']) ? $data['SENHA_FUNCIONARIO'] : '';

        $stmt = $db->prepare("INSERT INTO FUNCIONARIOS (NOME_FUNCIONARIO, CPF, RG, DATA_NASCIMENTO, FACEID, FK_EMPRESA, TIPO, SENHA_FUNCIONARIO, TEL_FUNCIONARIO, EMAIL_FUNCIONARIO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bindValue(1, $nomeFuncionario);
        $stmt->bindValue(2, $cpfFuncionario);
        $stmt->bindValue(3, $rgFuncionario);
        $stmt->bindValue(4, $dataNascimento);
        $stmt->bindValue(5, $faceId);
        $stmt->bindValue(6, $empresaId);
        $stmt->bindValue(7, 'F');
        $stmt->bindValue(8, $senhaFuncionario);
        $stmt->bindValue(9, $telFuncionario);
        $stmt->bindValue(10, $emailFuncionario);

        $result = $stmt->execute();
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Funcionário cadastrado com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro ao cadastrar funcionário."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos."
        ]);
    }
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

    $stmt = $db->prepare("SELECT RAZAO_FANTASIA FROM EMPRESA WHERE ID_EMPRESA = ?");
    $resultado = [];
    $stmt->bindValue(1, $empresaId);

    $result = $stmt->execute();


    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $resultado[] = $row;
    }

    echo json_encode($resultado);
}

function getLocEmpresa($db, $data){
    session_start(); 

    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }

    $empresaId = $_SESSION['empresa_id'];

    $stmt = $db->prepare("SELECT LOC_EMPRESA FROM EMPRESA WHERE ID_EMPRESA = ?");
    $resultado = [];
    $stmt->bindValue(1, $empresaId);

    $result = $stmt->execute();

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $resultado[] = $row;
    }

    echo json_encode($resultado);
}

function getSenhaAtualEmpresa($db, $data){
    session_start();
    if ($data) {
        $senhaAtual = $data['SENHA_EMPRESA'];
        $idEmpresa = $data['ID_EMPRESA'];

        $stmt = $db->prepare("SELECT * FROM EMPRESA WHERE ID_EMPRESA = ? AND SENHA_EMPRESA = ?");
        $stmt->bindValue(1, $idEmpresa);
        $stmt->bindValue(2, $senhaAtual);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode([
                "success" => true,
                "error" => "Senha correta."
            ]);

        } else {
            echo json_encode([
                "success" => false,
                "error" => "Erro ao executar a consulta."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Dados não fornecidos."
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

    $stmt = $db->prepare("SELECT ID_FUNCIONARIO, NOME_FUNCIONARIO, CPF_FUNCIONARIO, RG_FUNCIONARIO, DATA_NASCIMENTO, FACEID FROM FUNCIONARIOS WHERE ID_FUNCIONARIO = ?");
    $resultado = [];
    $stmt->bindValue(1, $idFuncionario);

    $result = $stmt->execute();


    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $resultado[] = $row;
    }

    echo json_encode($resultado);
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

    $stmt = $db->prepare("SELECT ID_EMPRESA, RAZAO_SOCIAL, RAZAO_FANTASIA, CNPJ_EMPRESA, TEL_EMPRESA, EMAIL_EMPRESA, LOC_EMPRESA, DSC_EMPRESA FROM EMPRESA WHERE ID_EMPRESA = ?");
    $resultado = [];
    $stmt->bindValue(1, $idEmpresa);

    $result = $stmt->execute();

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $resultado[] = $row;
    }

    echo json_encode($resultado);
}

function updateFuncionario($db, $data){
    session_start();
    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }
    if ($data && isset($data['ID_FUNCIONARIO'])) {
        $idFuncionario = $data['ID_FUNCIONARIO'];
        $nomeFuncionario = $data['NOME_FUNCIONARIO'];
        $cpfFuncionario = $data['CPF'];
        $rgFuncionario = $data['RG'];
        $dataNascimento = $data['DATA_NASCIMENTO'];
        $faceId = $data['FACEID'];
        $senhaFuncionario = isset($data['SENHA_FUNCIONARIO']) ? $data['SENHA_FUNCIONARIO'] : '';
        $stmt = $db->prepare("UPDATE FUNCIONARIOS SET NOME_FUNCIONARIO = ?, CPF = ?, RG = ?, DATA_NASCIMENTO = ?, FACEID = ?, SENHA_FUNCIONARIO = ? WHERE ID_FUNCIONARIO = ?");
        $stmt->bindValue(1, $nomeFuncionario);
        $stmt->bindValue(2, $cpfFuncionario);
        $stmt->bindValue(3, $rgFuncionario);
        $stmt->bindValue(4, $dataNascimento);
        $stmt->bindValue(5, $faceId);
        $stmt->bindValue(6, $senhaFuncionario);
        $stmt->bindValue(7, $idFuncionario);
        $result = $stmt->execute();
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Funcionário atualizado com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro ao atualizar funcionário."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos ou ID ausente."
        ]);
    }
}

function updateEmpresa($db, $data){
    session_start();   
    $idEmpresa = $_SESSION['empresa_id'];

    if ($data && $idEmpresa) {
        $nomeFantasia = $data['RAZAO_FANTASIA'];
        $cnpj = $data['CNPJ_EMPRESA'];
        $email = $data['EMAIL_EMPRESA'];
        $telefone = $data['TEL_EMPRESA'];
        $dscEmpresa = $data['DSC_EMPRESA'];

        $stmt = $db->prepare("UPDATE EMPRESA SET RAZAO_FANTASIA = ?, CNPJ_EMPRESA = ?, EMAIL_EMPRESA = ?, TEL_EMPRESA = ?, DSC_EMPRESA = ? WHERE ID_EMPRESA = ?");
        $stmt->bindValue(1, $nomeFantasia);
        $stmt->bindValue(2, $cnpj);
        $stmt->bindValue(3, $email);
        $stmt->bindValue(4, $telefone);
        $stmt->bindValue(5, $dscEmpresa);
        $stmt->bindValue(6, $idEmpresa);
        $result = $stmt->execute();
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Empresa atualizada com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro ao atualizar funcionário."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos ou ID ausente."
        ]);
    }
}

function updateLocEmpresa($db, $data){
    session_start();   
    $idEmpresa = $_SESSION['empresa_id'];

    if ($data && $idEmpresa) {
        $endereco = $data['LOC_EMPRESA'];
        $stmt = $db->prepare("UPDATE EMPRESA SET LOC_EMPRESA = ? WHERE ID_EMPRESA = ?");
       
        $stmt->bindValue(1, $endereco);
        $stmt->bindValue(2, $idEmpresa);
        $result = $stmt->execute();
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Empresa atualizada com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro ao atualizar funcionário."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos ou ID ausente."
        ]);
    }
}

function updateSenhaEmpresa($db, $data){
    session_start();
    if (!isset($_SESSION['empresa_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "Sessão expirada. Faça login novamente."
        ]);
        exit;
    }
    if ($data && isset($data['ID_EMPRESA'])) {
        $senhaEmpresa = $data['SENHA_EMPRESA'];
        $idEmpresa = $data['ID_EMPRESA'];

        $stmt = $db->prepare("UPDATE EMPRESA SET SENHA_EMPRESA = ? WHERE ID_EMPRESA = ?");
        $stmt->bindValue(1, $senhaEmpresa);
        $stmt->bindValue(2, $idEmpresa);

        $result = $stmt->execute();
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Senha Empresa atualizada com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro ao atualizar senha da empresa."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dados não fornecidos ou ID ausente."
        ]);
    }
}

function deletaFuncionario($db, $data){
    if ($data) {
        $id = $data['ID_FUNCIONARIO'];

        $getEmpresaStmt = $db->prepare("SELECT FK_EMPRESA FROM FUNCIONARIOS WHERE ID_FUNCIONARIO = ?");
        $getEmpresaStmt->bindValue(1, $id);
        $result = $getEmpresaStmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);

        if (!$row) {
            echo json_encode(["error" => "Funcionário não encontrado."]);
            return;
        }

        $fkEmpresa = $row['FK_EMPRESA'];

        $stmt = $db->prepare("DELETE FROM FUNCIONARIOS WHERE ID_FUNCIONARIO = ?");
        $stmt->bindValue(1, $id);

        if ($stmt->execute()) {
            $countStmt = $db->prepare("SELECT COUNT(*) as total FROM FUNCIONARIOS WHERE FK_EMPRESA = ?");
            $countStmt->bindValue(1, $fkEmpresa);
            $countResult = $countStmt->execute();
            $countRow = $countResult->fetchArray(SQLITE3_ASSOC);
            $totalFuncionarios = $countRow['total'];

            echo json_encode([
                "message" => "Funcionário deletado com sucesso!",
                "empresa_id" => $fkEmpresa,
                "total_funcionarios_empresa" => $totalFuncionarios
            ]);
        } else {
            echo json_encode(["error" => "Erro ao deletar funcionário."]);
        }
    }
}

?>