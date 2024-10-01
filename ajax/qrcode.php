<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../helpers/response.php';
include_once '../classes/panel.php';
require_once '../phpqrcode/qrlib.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

function generateQrCodePIX($totalValue)
{
  try {
    $sql = Db::Connection();

    $exec = $sql->prepare("SELECT pix, account_name, city FROM bank_account WHERE id = :id LIMIT 1");
    $id_pix = 1;
    $exec->bindValue(':id', $id_pix, PDO::PARAM_INT);
    $exec->execute();
    $pix = $exec->fetch(PDO::FETCH_ASSOC);

    if (!$pix) {
      throw new Exception('Dados da conta bancária não encontrados.');
    }

    $pix_key = $pix['pix'];
    $name_company = $pix['account_name'];
    $city_company = $pix['city'];
    $transition = uniqid();

    $formattedValue = number_format(floatval($totalValue), 2, '', '');
    $formattedValue = str_pad($formattedValue, 10, '0', STR_PAD_LEFT);

    $payload = "000201"  // Versão do QR Code
      . "26" . str_pad(strlen("BR.GOV.BCB.PIX"), 2, '0', STR_PAD_LEFT) . "BR.GOV.BCB.PIX" // Tipo de chave
      . "01" . str_pad(strlen($pix_key), 2, '0', STR_PAD_LEFT) . $pix_key // Chave do PIX
      . "52" . "0000" // Código da categoria do comerciante
      . "53" . "986" // Código da moeda
      . "54" . str_pad($formattedValue, 10, '0', STR_PAD_LEFT) // Valor
      . "58" . "BR" // País
      . "59" . str_pad(substr($name_company, 0, 25), 25, ' ', STR_PAD_RIGHT) // Nome da empresa
      . "60" . str_pad(substr($city_company, 0, 15), 15, ' ', STR_PAD_RIGHT) // Cidade
      . "62" . str_pad(strlen($transition), 2, '0', STR_PAD_LEFT) . $transition; // ID da transação

    $crc16 = function ($str) {
      $crc = 0xFFFF;
      for ($c = 0; $c < strlen($str); $c++) {
        $crc ^= ord($str[$c]) << 8;
        for ($i = 0; $i < 8; $i++) {
          if (($crc <<= 1) & 0x10000) {
            $crc ^= 0x1021;
          }
          $crc &= 0xFFFF;
        }
      }
      return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    };

    $resultadoCRC16 = $crc16($payload . "6304");

    $codigoPIX = $payload . "6304" . $resultadoCRC16;

    $tempDir = '../../../../temp';
    if (!$tempDir) {
      throw new Exception("Diretório temp não encontrado.");
    }

    $fileName = uniqid() . '.png';
    $filePath = $tempDir . '/' . $fileName;

    if (!is_dir($tempDir)) {
      try {
        $dirIterator = new FilesystemIterator(dirname($tempDir), FilesystemIterator::SKIP_DOTS);
        if ($dirIterator->valid()) {
          if (mkdir($tempDir, 0777, true)) {
            echo "Diretório criado com sucesso: $tempDir\n";
          }
        } else {
          echo "Diretório pai não existe: " . dirname($tempDir) . "\n";
        }
      } catch (Exception $e) {
        echo "Erro ao verificar diretório: " . $e->getMessage();
      }
    }
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
