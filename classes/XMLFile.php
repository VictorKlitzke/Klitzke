<?php

namespace xmlfile;

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

class XMLFile{

    public static function Xml($XMLContent)
    {

        $xml = simplexml_load_string($XMLContent);
        if ($xml === false) {
            throw new Exception('Erro ao carregar o XML.');
        }

        // Namespace XML
        $namespaces = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('nfe', $namespaces['']);

        // Extrair os produtos
        $products = [];
        foreach ($xml->xpath('//nfe:det') as $det) {
            $product = [
                'name' => (string)$det->prod->xProd,
                'quantity' => (string)$det->prod->qCom,
                'price' => (string)$det->prod->vUnCom,
            ];
            $products[] = $product;
        }

        return $products;

    }

}

function sendResponse($data) {
    echo json_encode($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['xmlData'])) {
        try {
            $processor = new XMLProcessor();
            $products = $processor->processXML($requestData['xmlData']);
            sendResponse(['success' => true, 'products' => $products]);
        } catch (Exception $e) {
            sendResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        sendResponse(['success' => false, 'message' => 'Dados XML não encontrados.']);
    }
} else {
    sendResponse(['success' => false, 'message' => 'Método inválido.']);
}