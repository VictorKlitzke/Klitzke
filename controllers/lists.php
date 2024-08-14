<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../classes/panel.php';
include_once '../classes/controllers.php';
include_once '../helpers/response.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

$input = json_decode(file_get_contents('php://input'), true);
$type = isset($input['type']) ? $input['type'] : null;
$today = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type === 'listproduct') {
        Lists::ListProducts($sql);
    } else if ($type === 'listforn') {
        Lists::ListForn($sql);
    }
}

class lists {
    public static function ListProducts($sql) {

        try {

            $exec = $sql->prepare("select id,name,stock_quantity,value_product,status_product from `products` where status_product = 'negativado'");
            $exec->execute();
            $products = $exec->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'products' => $products
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function ListForn($sql) {

        try {

            $exec = $sql->prepare("select id,company from `suppliers`");
            $exec->execute();
            $products = $exec->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'forn' => $products
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}

?>