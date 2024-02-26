<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

function status_boxpdv_request($status)
{
    $sql = Db::Connection();

    $exec = $sql->prepare("SELECT COUNT(*) FROM boxpdv WHERE status = :status");
    $exec->bindParam(':status', $status, PDO::PARAM_INT);
    $exec->execute();

    return $exec->fetchColumn() > 0;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $selectedProducts = $requestData['requestProducts'] ?? [];
    $TotalValueRequest = $requestData['TotalValueRequest'] ?? '';

    try {

        $sql = DB::Connection();
        $sql->beginTransaction();

        $status = 1;
        $boxpdv_open = status_boxpdv_request($status);

        if (!$boxpdv_open) {
            throw new Exception('O caixa está fechado. Não é possível registrar o pedido.');
        } else {

            $id_users_request = isset($_SESSION['id']) ? $_SESSION['id'] : null;

            // var_dump($id_users_request);die();

            $check_box_request = $sql->prepare("SELECT id FROM boxpdv WHERE id_users = :id_users AND status = 1");
            $check_box_request->bindParam(':id_users', $id_users_request, PDO::PARAM_INT);
            $check_box_request->execute();
            $id_boxpdv_request = $check_box_request->fetchColumn();

            $exec = $sql->prepare("SELECT * FROM request WHERE id_users_request = :id_users_request");
            $exec->bindParam(':id_users_request', $id_users_request, PDO::PARAM_INT);
            $exec->execute();
            $result = $exec->fetchAll(PDO::FETCH_ASSOC);

            $exec = $sql->prepare("INSERT INTO request (total_request, id_boxpdv_request, id_users_request, date_request, status) 
                VALUES (:total_request, :id_boxpdv_request, :id_users_request, NOW(), :status)");
            
            $exec->bindParam(':id_users_request', $id_users_request, PDO::PARAM_INT);
            $exec->bindParam(':total_request', $TotalValueRequest, PDO::PARAM_INT);
            $exec->bindParam(':id_boxpdv_request', $id_boxpdv_request, PDO::PARAM_INT);
            $status = 1;
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->execute();

            $last_request_id = $sql->lastInsertId();

            // var_dump($requestProducts);die();

            foreach ($selectedProducts as $requestProducts) {

                $productId = isset($product['id']) ? $product['id'] : null;

                if ($productId === null) {

                    break;

                } else {

                    $productQuantity = $requestProducts['stock_quantity'];
                    $productValue = floatval($requestProducts['value']);

                    $exec = $sql->prepare("INSERT INTO request_items (id_request, id_products, quantity, price_request) 
                                VALUES (:last_request_id, :product_id, :product_stock_quantity, :product_value)");
                    $exec->bindParam(':last_request_id', $last_request_id, PDO::PARAM_INT);
                    $exec->bindParam(':product_id', $productId, PDO::PARAM_INT);
                    $exec->bindParam(':product_stock_quantity', $productQuantity, PDO::PARAM_INT);
                    $exec->bindParam(':product_value', $productValue, PDO::PARAM_STR);
                    $exec->execute();

                }
            }

            $sql->commit();

            echo json_encode(['success' => true, 'message' => htmlspecialchars('Pedido registrado com sucesso')]);
        }
    } catch (PDOException $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    } finally {
        $sql = null;
    }
}
?>