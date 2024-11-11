<?php

include_once '../vendor/autoload.php';
use Smalot\PdfParser\Parser;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf-file'])) {
    $fileTmpPath = $_FILES['pdf-file']['tmp_name'];
    $parser = new Parser();
    $pdf = $parser->parseFile($fileTmpPath);
    $pages = $pdf->getPages();

    $notaInfo = [];

    foreach ($pages as $page) {
        $pageText = $page->getText();

        $lines = explode("\n", $pageText);
        foreach ($lines as $line) {
            // Aqui você pode visualizar as linhas individuais para garantir que a regex está aplicando corretamente
            echo '<pre>';
            echo htmlspecialchars($line);
            echo '</pre>';
            exit;
        }


        // Extrai a chave de acesso
        preg_match('/(\d{44})/', $pageText, $chaveAcesso);
        if (isset($chaveAcesso[1])) {
            $notaInfo['chaveAcesso'] = $chaveAcesso[1];
        }

        // Extrai o CNPJ do fornecedor
        preg_match('/CNPJ\s*[:\-]?\s*([\d\.\-\/]+)/', $pageText, $emitterCnpj);
        if (!isset($notaInfo['emitterCnpj']) && isset($emitterCnpj[1])) {
            $notaInfo['emitterCnpj'] = trim($emitterCnpj[1]);
        }

        // Extrai o Valor Total
        preg_match('/Valor Total\s*[:\s]*R?\$\s*([\d\.,]+)/i', $pageText, $totalValue);
        if (!isset($notaInfo['totalValue']) && isset($totalValue[1])) {
            $notaInfo['totalValue'] = trim($totalValue[1]);
        }

        // Divide o conteúdo da página em linhas para facilitar a extração dos produtos
        $lines = explode("\n", $pageText);
        foreach ($lines as $line) {
            // Ajusta a regex para capturar melhor os produtos
            if (preg_match('/(\d{6,8})\s+([A-Za-z0-9\s\-\/\.\,]+?)\s+(\d{8,12})\s+(UN|PC|KG|FD|CX|MTR|MT|LT)\s+([\d\.,]+)\s+([\d\.,]+)\s+([\d\.,]+)/', $line, $product)) {
                $productId = trim($product[1]); // Código do produto
                $descricao = trim($product[2]); // Descrição do produto
                $unidade = trim($product[4]); // Unidade
                $quantidade = floatval(str_replace(',', '.', $product[5])); // Quantidade
                $vlUnitario = floatval(str_replace(',', '.', $product[6])); // Valor unitário
                $valorTotal = floatval(str_replace(',', '.', $product[7])); // Valor total

                // Adiciona o produto ao array
                $notaInfo['products'][] = [
                    'id' => $productId,
                    'descricao' => $descricao,
                    'unidade' => $unidade,
                    'quantidade' => $quantidade,
                    'vl_unitario' => $vlUnitario,
                    'valor_total' => $valorTotal
                ];
            }

            echo '<pre>';
            var_dump($product); // Para depurar o que está sendo capturado
            echo '</pre>';
            exit;


        }
    }

    // echo '<pre>';
    // echo htmlspecialchars($pageText); // Para evitar problemas com caracteres especiais
    // echo '</pre>';

    // Verifica se os produtos foram extraídos corretamente
    echo '<pre>';
    print_r($notaInfo);
    echo '</pre>';

    session_start();
    $_SESSION['notaInfo'] = $notaInfo;
    exit;
} else {
    echo 'Erro no envio do arquivo. Verifique e tente novamente.';
}
