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
$sql = Db::Connection();
$response = [];

$check_access = Querys::QueryAccess($user_id, $sql);
$response['access'] = $check_access;

$query_warnings = Querys::QueryWarnings($sql);
$response['query_warnings'] = $query_warnings;

$list_products = Querys::QueryListProduct($sql);
$response['list_products'] = $list_products;

$list_portion = Querys::QueryListPortionProduct($sql);
$response['list_portion'] = $list_portion;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['type']) && $data['type'] === 'queryuser' && isset($data['id_user_menu'])) {
        $id_user_menu = base64_decode($data['id_user_menu']);

        $menu_access_response = Querys::MenuAccessEditRemove($id_user_menu, $sql);

        if (isset($menu_access_response['error'])) {
            $response['error'] = $menu_access_response['error'];
        } else {
            $response['menu_user'] = $menu_access_response['menu_user'];
        }

    } else {
        $response['error'] = 'Parâmetros inválidos ou incompletos.';
    }

    if (isset($data['type']) && $data['type'] === 'detailsclients' && isset($data['id_client_detals'])) {
        $id_client_details = base64_decode($data['id_client_detals']);

        $client_response = Querys::Moredetails($id_client_details, $sql);

        if (isset($client_response['error'])) {
            $response['error'] = $client_response['error'];
        } else {
            $response['details_param_clients'] = $client_response['details_param_clients'];
        }
    } else {
        $response['error'] = 'Parâmetros inválidos ou incompletos.';
    }
    echo json_encode($response);
    exit();
}

echo json_encode($response);

class Querys
{
    public static function MenuAccessEditRemove($id_user_menu, $sql)
    {
        try {

            $exec = $sql->prepare("SELECT menu, released, user_id FROM menu_access WHERE user_id = :user_id");
            $exec->BindParam('user_id', $id_user_menu, PDO::PARAM_INT);
            $exec->execute();
            $menu_access_user = $exec->fetchAll(PDO::FETCH_ASSOC);

            return [
                'menu_user' => $menu_access_user
            ];

        } catch (Exception $e) {
            return ['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()];
        }
    }
    public static function Moredetails($id_client_details, $sql)
    {
        if (empty($id_client_details) || !is_numeric($id_client_details)) {
            return ['error' => 'ID do cliente inválido.'];
        }

        try {
            $exec = $sql->prepare("
            SELECT 
                s.`total_value` AS total_value,
                si.`amount` AS quantity,
                si.`price_sales` AS value_unit,
                c.`name` AS client,
                fp.`name` AS form_payment,
                s.`id` AS sale_id,
                p.`name` AS product
            FROM 
                sales s
                INNER JOIN `sales_items` si ON si.`id_sales` = s.`id`
                INNER JOIN `products` p ON p.`id` = si.`id_product`
                INNER JOIN `clients` c ON c.id = s.`id_client`
                INNER JOIN `form_payment` fp ON fp.`id` = s.`id_payment_method`
            WHERE s.`id_client` = :id_client_details
        ");

            $exec->BindParam(':id_client_details', $id_client_details, PDO::PARAM_INT);
            $exec->execute();
            $sales_details = $exec->fetchAll(PDO::FETCH_ASSOC);

            return [
                'details_param_clients' => $sales_details
            ];

        } catch (Exception $e) {
            return ['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()];
        }
    }
    public static function QueryAccess($user_id, $sql)
    {
        try {
            if (!$user_id) {
                return ['error' => 'Usuário não autenticado.'];
            }

            $check_access = $sql->prepare("SELECT access FROM users WHERE id = :user_id");
            $check_access->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $check_access->execute();

            return $check_access->fetchColumn();
        } catch (Exception $e) {
            return ['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()];
        }
    }
    public static function QueryWarnings($sql)
    {
        try {
            $exec = $sql->prepare("SELECT * FROM financial_control");
            $exec->execute();
            $resultFinancialControl = $exec->fetchAll(PDO::FETCH_ASSOC);

            $exec1 = $sql->prepare("SELECT * FROM conditional ORDER BY id DESC");
            $exec1->execute();
            $resultConditional = $exec1->fetchAll(PDO::FETCH_ASSOC);

            return [
                'financial_control' => $resultFinancialControl,
                'conditional' => $resultConditional
            ];

        } catch (Exception $e) {
            return [
                'error' => 'Erro no banco de dados: ' . $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    public static function QueryListPortionProduct($sql) {
        try {

            $exec = $sql->prepare("SELECT id,name,stock_quantity FROM products");
            $exec->execute();
            return $exec->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return ['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()];
        }
    }
    public static function QueryListProduct($sql)
    {
        try {

            $exec = $sql->prepare("SELECT 
                                        pm.product_id, 
                                        p.name AS product_name,
                                        p.stock_quantity AS quantity,
                                        SUM(CASE WHEN pm.type = 'Entrada' THEN pm.quantity ELSE 0 END) + 
                                        SUM(CASE WHEN pm.type = 'Inventario' THEN pm.quantity_inventary ELSE 0 END) AS total_entry,
                                        SUM(CASE WHEN pm.type = 'Saida' THEN pm.quantity ELSE 0 END) AS total_exit,
                                        (SUM(CASE WHEN pm.type = 'Entrada' THEN pm.quantity ELSE 0 END) +
                                        SUM(CASE WHEN pm.type = 'Saida' THEN pm.quantity ELSE 0 END)) AS stock_difference
                                    FROM 
                                        product_movements pm
                                    INNER JOIN 
                                        products p ON p.id = pm.product_id
                                    GROUP BY 
                                        pm.product_id,
                                        p.name,
                                        p.stock_quantity
                                    ");
            $exec->execute();
            $list_products = $exec->fetchAll(PDO::FETCH_ASSOC);
            return $list_products;

        } catch (Exception $e) {
            return ['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()];
        }
    }
}
