<?php 

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../helpers/response.php';
include_once '../classes/panel.php';
require_once 'phpqrcode/qrlib.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$today = date('Y-m-d H:i:s');

function generateQrCodePIX($totalValue) {
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

    $payload = "000201"
        . "26" . str_pad(strlen("BR.GOV.BCB.PIX"), 2, '0', STR_PAD_LEFT) . "BR.GOV.BCB.PIX"
        . "01" . str_pad(strlen($pix_key), 2, '0', STR_PAD_LEFT) . $pix_key
        . "52" . "0000"
        . "53" . "986"
        . "54" . str_pad(number_format(floatval($totalValue), 2, '.', ''), 2, '0', STR_PAD_LEFT)
        . "58" . "BR"
        . "59" . str_pad($name_company, 25, ' ', STR_PAD_RIGHT)
        . "60" . str_pad($city_company, 15, ' ', STR_PAD_RIGHT)
        . "62" . str_pad(strlen($transition), 2, '0', STR_PAD_LEFT) . $transition;

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
        return strtoupper(dechex($crc));
    };

    $codigoPIX = $payload . "6304" . $crc16($payload . "6304");

    $tempDir = '../temp/'; 
    $fileName = uniqid() . '.png';
    $filePath = $tempDir . $fileName;

    QRcode::png($codigoPIX, $filePath); 

    $qrCodeImageData = base64_encode(file_get_contents($filePath));
    unlink($filePath); 

    return $qrCodeImageData;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    
    if (isset($requestData['totalValue'])) {
        try {
            $totalValue = $requestData['totalValue'];
            $qrCodePIX = generateQrCodePIX($totalValue); 
            
            error_log("QR Code gerado: " . $qrCodePIX);
            
            echo json_encode([
                'success' => true,
                'qrCodePIX' => $qrCodePIX,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Valor total não fornecido.']);
    }
    exit();
}
?>
