<?php
// Carregador simples de variáveis de ambiente a partir de um arquivo .env na raiz do projeto
// Formato suportado: CHAVE=valor (linhas começando com # são ignoradas)

if (!function_exists('ninja_load_env')) {
    function ninja_load_env($envPath) {
        if (!is_readable($envPath)) {
            return;
        }
        $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return;
        }
        foreach ($lines as $line) {
            $trim = trim($line);
            if ($trim === '' || substr($trim, 0, 1) === '#') {
                continue;
            }
            $pos = strpos($trim, '=');
            if ($pos === false) {
                continue;
            }
            $key = trim(substr($trim, 0, $pos));
            $value = trim(substr($trim, $pos + 1));
            // Remove aspas ao redor, se houver
            $vlen = strlen($value);
            if ($vlen >= 2) {
                $first = substr($value, 0, 1);
                $last = substr($value, -1);
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }
            if ($key !== '') {
                if (getenv($key) === false) {
                    putenv($key . '=' . $value);
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
    }
}

// Tenta carregar o arquivo .env na raiz do projeto
$root = realpath(__DIR__ . '/..');
if ($root) {
    $envFile = $root . DIRECTORY_SEPARATOR . '.env';
    ninja_load_env($envFile);
}


