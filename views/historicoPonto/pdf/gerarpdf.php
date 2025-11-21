<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// recebe os dados
$dados = json_decode($_POST['dados'], true);
if (!$dados) {
    die("Nenhum dado recebido.");
}

// ===== CONVERTE LOGO PARA BASE64 =====
$logoPath = __DIR__ . '/../../../assets/img/ninjaLogo.png';
$logoBase64 = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/png;base64,' . $logoBase64;

// ===== CONFIGURA DOMPDF =====
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

ob_start();
?>

<html>
<head>
<meta charset="UTF-8">
<style>

body {
    font-family: Helvetica, Arial, sans-serif;
    padding: 25px;
    color: #111;
}

/* ------------ CABEÇALHO ------------ */
.header {
    display: flex;
    align-items: center;
    gap: 15px;
    border-bottom: 2px solid #0ea5e9;
    padding-bottom: 10px;
    margin-bottom: 20px;
}
.header img {
    width: 55px;
}
.header-title {
    font-size: 24px;
    font-weight: bold;
    color: #0ea5e9;
}

/* ------------ TABELA ------------ */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th {
    background: #0f172a;
    color: white;
    padding: 8px;
    font-size: 14px;
}
td {
    font-size: 13px;
    padding: 6px;
    border-bottom: 1px solid #ddd;
}

/* Centralizar tudo */
td, th {
    text-align: center;
}

/* Zebra */
tr:nth-child(even) { background: #f3f4f6; }
tr:nth-child(odd)  { background: #ffffff; }

/* Status cores */
.status-gray  { color: #6b7280; font-weight: bold; }
.status-green { color: #16a34a; font-weight: bold; }
.status-red   { color: #dc2626; font-weight: bold; }
.status-blue  { color: #2563eb; font-weight: bold; }

/* Rodapé */
@page {
    margin: 20px 20px 40px 20px;
}
footer {
    position: fixed;
    bottom: -20px;
    width: 100%;
    text-align: center;
    font-size: 11px;
    color: #555;
}
.page-number:after {
    content: counter(page);
}

</style>
</head>

<body>

<!-- Cabeçalho -->
<div class="header">
    <img src="<?= $logoSrc ?>">
    <div class="header-title">Relatório de Pontos — Ninja Control</div>
</div>

<p style="font-size:14px; margin-bottom: 10px;">
    Histórico de pontos registrados no sistema.
</p>

<table>
    <tr>
        <th>Data</th>
        <th>Entrada 1</th>
        <th>Saída 1</th>
        <th>Entrada 2</th>
        <th>Saída 2</th>
        <th>Status</th>
    </tr>

    <?php foreach ($dados as $p):

        // ------------ LÓGICA DE STATUS ------------ //
        if (!$p['ENTRADA1'] && !$p['SAIDA1'] && !$p['ENTRADA2'] && !$p['SAIDA2']) {
            $status = "Não trabalhado";
            $cls = "status-gray";

        } else if ($p['ENTRADA1'] && $p['SAIDA1'] && $p['ENTRADA2'] && $p['SAIDA2']) {
            $status = "Concluído";
            $cls = "status-green";

        } else if ($p['ENTRADA1'] && !$p['SAIDA1']) {
            $status = "Atrasado";
            $cls = "status-red";

        } else {
            $status = "Adiantado";
            $cls = "status-blue";
        }

    ?>
    <tr>
        <td><?= $p['DATA'] ?></td>
        <td><?= $p['ENTRADA1'] ?: "-" ?></td>
        <td><?= $p['SAIDA1'] ?: "-" ?></td>
        <td><?= $p['ENTRADA2'] ?: "-" ?></td>
        <td><?= $p['SAIDA2'] ?: "-" ?></td>
        <td class="<?= $cls ?>"><?= $status ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<footer>
    Página <span class="page-number"></span>
</footer>

</body>
</html>

<?php

$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("relatorio_pontos.pdf", ["Attachment" => true]);

