const sqlite3 = require('sqlite3').verbose();
const path = require('path');
const dbPath = path.join(__dirname, 'bancoDados.db');

const db = new sqlite3.Database(dbPath, (err) => {
    if (err) {
        return console.error(err.message);
    }
    console.log('Conectado ao banco de dados SQLite.');
});

// Criando a tabela Empresa
db.run(`
    CREATE TABLE IF NOT EXISTS EMPRESA (
        ID_EMPRESA INTEGER PRIMARY KEY AUTOINCREMENT,
        RAZAO_SOCIAL TEXT NOT NULL,
        RAZAO_FANTASIA TEXT NOT NULL,
        CNPJ_EMPRESA TEXT NOT NULL,
        LOC_EMPRESA TEXT,
        DSC_EMPRESA TEXT,
        TEL_EMPRESA TEXT,
        EMAIL_EMPRESA TEXT,
        SENHA_EMPRESA TEXT NOT NULL,
        TIPO TEXT CHECK(TIPO IN ('F', 'E')) NOT NULL
    )
`, (err) => {
    if (err) {
        console.error("Erro ao criar a tabela Empresa:", err.message);
    } else {
        console.log("Tabela Empresa criada com sucesso.");
    }
});

// Criando a tabela Funcionarios
db.run(`
    CREATE TABLE IF NOT EXISTS FUNCIONARIOS (
        ID_FUNCIONARIO INTEGER PRIMARY KEY AUTOINCREMENT,
        CPF TEXT UNIQUE NOT NULL,
        SENHA_FUNCIONARIO TEXT NOT NULL,
        NOME_FUNCIONARIO TEXT NOT NULL,
        RG TEXT NOT NULL,
        TEL_FUNCIONARIO TEXT NOT NULL,
        EMAIL_FUNCIONARIO TEXT NOT NULL,
        DATA_NASCIMENTO DATE NOT NULL,
        FACEID TEXT NOT NULL,
        FK_EMPRESA INTEGER NOT NULL,
        TIPO TEXT CHECK(TIPO IN ('F', 'E')) NOT NULL,
        FOREIGN KEY (FK_EMPRESA) REFERENCES EMPRESA(ID_EMPRESA)
    )
`, (err) => {
    if (err) {
        console.error("Erro ao criar a tabela Funcionarios:", err.message);
    } else {
        console.log("Tabela Funcionarios criada com sucesso.");
    }
});

// Criando a tabela Ponto
db.run(`
    CREATE TABLE IF NOT EXISTS PONTO (
        ID_PONTO INTEGER PRIMARY KEY AUTOINCREMENT,
        FK_FUNCIONARIO INTEGER NOT NULL,
        FK_EMPRESA INTEGER NOT NULL,
        NOME_FUNCIONARIO TEXT NOT NULL,
        DATA_HORA DATE NOT NULL,
        TIPO_PONTO TEXT CHECK(TIPO_PONTO IN ('E', 'S')) NOT NULL,
        FACEID_FUNCIONARIO TEXT NOT NULL,
        FOREIGN KEY (FK_FUNCIONARIO) REFERENCES FUNCIONARIOS(ID_FUNCIONARIO)
    )
`, (err) => {
    if (err) {
        console.error("Erro ao criar a tabela Ponto:", err.message);
    } else {
        console.log("Tabela Ponto criada com sucesso.");
    }
    db.close();
});