<?php

include_once '../vendor/autoload.php';
use Smalot\PdfParser\Parser;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf-file'])) {
    $fileTmpPath = $_FILES['pdf-file']['tmp_name'];

    // Cria o objeto Parser
    $parser = new Parser();
    $pdf = $parser->parseFile($fileTmpPath);
    $pages = $pdf->getPages();

    $notaInfo = [];

    foreach ($pages as $page) {
        // Extrai o texto de cada página
        $pageText = $page->getText();

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

        // Extrai o número da nota fiscal
        preg_match('/Número\s*[:\-]?\s*(\d+)/', $pageText, $notaNumber);
        if (!isset($notaInfo['invoiceNumber']) && isset($notaNumber[1])) {
            $notaInfo['invoiceNumber'] = trim($notaNumber[1]);
        }

        // Localiza o trecho dos produtos
        preg_match('/DADOS DOS PRODUTOS \/ SERVIÇOS(.*?)CÁLCULO DO ISSQN/s', $pageText, $productSection);

        if (isset($productSection[1])) {
            $productsText = trim($productSection[1]);

            // Quebra o bloco de produtos em linhas
            $productLines = preg_split('/\r\n|\r|\n/', $productsText);

            foreach ($productLines as $line) {
                // Remove espaços e tabulações duplicados
                $line = preg_replace('/\s+/', ' ', $line);

                // Divide a linha em colunas com base em espaços
                $columns = explode(' ', $line);

                // Verifica se há o número esperado de colunas para capturar dados do produto
                if (count($columns) >= 6) {
                    // Primeiro campo é o código do produto
                    $codigo = $columns[0];

                    // Últimos elementos são unidade, quantidade e valor unitário
                    $unidade = substr($columns[count($columns) - 8], -2);
                    $quantidade = str_replace(',', '.', $columns[count($columns) - 7]);
                    $valorUnitario = $columns[count($columns) - 6];

                    $valorUnitario = str_replace(',', '.', $valorUnitario);

                    $descricao = implode(' ', array_slice($columns, 1, count($columns) - 1));
                    $descricao = preg_replace('/\d{1,}([\,\.]{0,1}\d*)/', '', $descricao);
                    $descricao = trim($descricao);

                    // Armazena o produto no array
                    $notaInfo['products'][] = [
                        'codigo' => $codigo,
                        'descricao' => $descricao,
                        'unidade' => $unidade,
                        'quantidade' => floatval($quantidade),
                        'valor_unitario' => floatval($valorUnitario)
                    ];
                }
            }
        } else {
            echo "Nenhum bloco de produtos encontrado.\n";
        }
    }

    // Exibe os produtos capturados de forma formatada
    echo '<pre>';
    echo "Informações da Nota Fiscal:\n";
    echo "Número da Nota Fiscal: " . ($notaInfo['invoiceNumber'] ?? 'N/A') . "\n";
    echo "CNPJ do Emitente: " . ($notaInfo['emitterCnpj'] ?? 'N/A') . "\n";
    echo "Valor Total: " . ($notaInfo['totalValue'] ?? 'N/A') . "\n";
    echo "Produtos:\n";
    foreach ($notaInfo['products'] as $product) {
        echo "Código: " . $product['codigo'] . "\n";
        echo "Descrição: " . $product['descricao'] . "\n";
        echo "Unidade: " . $product['unidade'] . "\n";
        echo "Quantidade: " . $product['quantidade'] . "\n";
        echo "Valor Unitário: " . $product['valor_unitario'] . "\n";
        echo "-------------------\n";
    }
    echo '</pre>';

    session_start();
    $_SESSION['notaInfo'] = $notaInfo;
    header('Location: ' . 'http://localhost:3000/klitzke/display-invoice.php');
} else {
    echo 'Erro no envio do arquivo. Verifique e tente novamente.';
}
