<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../helpers/response.php';
include_once '../phpqrcode/qrlib.php';
include_once '../classes/payload.php';

use App\Pix\Payload;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

function generateQrCodePIX($totalValue)
{
  try {
    $sql = Db::Connection();
    $exec = $sql->prepare("SELECT id, pix, account_name, city FROM bank_account");
    $exec->execute();
    $pix = $exec->fetch(PDO::FETCH_ASSOC);

    if (!$pix['id']) {
      throw new Exception('Dados da conta bancária não encontrados.');
    }

    $pixKey = $pix['pix'];
    $merchantName = $pix['account_name'];
    $merchantCity = $pix['city'];
    $txid = uniqid();

    $payload = (new Payload)
      ->setPixKey($pixKey)
      ->setMerchantName($merchantName)
      ->setMerchantCity($merchantCity)
      ->setAmount($totalValue)
      ->setTxid($txid)
      ->setUniquePayment(true); 

    $codigoPIX = $payload->getPayload();

    $tempDir = '/Applications/MAMP/htdocs/Klitzke/temp';
    if (!is_dir($tempDir)) {
      if (!mkdir($tempDir, 0777, true)) {
        throw new Exception("Falha ao criar o diretório: $tempDir");
      }
    }

    $fileName = uniqid() . '.png';
    $filePath = $tempDir . '/' . $fileName;

    QRcode::png($codigoPIX, $filePath, QR_ECLEVEL_L, 10);
    $qrCodeImageData = base64_encode(file_get_contents($filePath));
    unlink($filePath);

    return $qrCodeImageData;

  } catch (Exception $e) {
    throw new Exception('Erro na geração do QRCode: ' . $e->getMessage());
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['totalValue']) && is_numeric($requestData['totalValue'])) {
      $totalValue = $requestData['totalValue'];
      $qrCodeData = generateQrCodePIX($totalValue);
      echo json_encode([
        'success' => true,
        'qrCodePIX' => $qrCodeData,
      ]);
    } else {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Valor total inválido ou não fornecido.']);
    }
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
      'success' => false,
      'message' => 'Erro interno: ' . $e->getMessage(),
    ]);
  }
  exit();
}


?>