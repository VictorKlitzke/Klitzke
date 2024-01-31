<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

function status_boxpdv($status)
{
    $sql = Db::Connection();

    $exec = $sql->prepare("SELECT COUNT(*) FROM boxpdv WHERE status = :status");
    $exec->bindParam(':status', $status, PDO::PARAM_INT);
    $exec->execute();

    return $exec->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $selectedPaymentMethod = $requestData['idPaymentMethod'] ?? '';
    $id_sales_client = $requestData['salesIdClient'] ?? '';
    $selectedProducts = $requestData['products'] ?? [];
    $selectedPortion = $requestData['selectedPortion'] ?? [];

    try {

        $sql = Db::Connection();
        $sql->beginTransaction();

        $status = 1;
        $boxpdv_open = status_boxpdv($status);

        if (!$boxpdv_open) {
            throw new Exception('O caixa está fechado. Não é possível registrar a venda.');
        } else {

            $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

            $checkBoxOpen = $sql->prepare("SELECT id FROM boxpdv WHERE id_users = :user_id AND status = 1");
            $checkBoxOpen->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $checkBoxOpen->execute();
            $id_boxpdv = $checkBoxOpen->fetchColumn();

            $exec = $sql->prepare("SELECT * FROM sales WHERE id_users = :user_id");
            $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result = $exec->fetchAll(PDO::FETCH_ASSOC);

            $exec = $sql->prepare("INSERT INTO sales (id_payment_method, id_client, id_boxpdv, id_users, date_sales, status) 
                VALUES (:paymentMethod, :salesClient, :id_boxpdv, :id_users, NOW(), :status)");
            $exec->bindParam(':paymentMethod', $selectedPaymentMethod, PDO::PARAM_INT);
            $exec->bindParam(':salesClient', $id_sales_client, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->bindParam(':id_boxpdv', $id_boxpdv, PDO::PARAM_INT);
            $status = 1;
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->execute();

            $lastSaleId = $sql->lastInsertId();

            foreach ($selectedProducts as $product) {
                $product_id = isset($product['id']) ? $product['id'] : null;
                if ($product_id === null) {
                    break;
                } else {

                    $productQuantity = $product['stock_quantity'];
                    $productValue = floatval($product['value']);

                    $exec = $sql->prepare("INSERT INTO sales_items (id_sales, id_product, amount, price_sales, status_item) 
                                VALUES (:lastSaleId, :product_id, :productQuantity, :productValue, :status_item)");
                    $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
                    $exec->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $exec->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                    $exec->bindParam(':productValue', $productValue, PDO::PARAM_STR);
                    $status_item = 1;
                    $exec->bindParam(':status_item', $status_item, PDO::PARAM_INT);
                    $exec->execute();

                }
            }

            $checkStock = $sql->prepare("SELECT id FROM products WHERE id = :product_id AND stock_quantity - :productQuantity < 0");
            $checkStock->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $checkStock->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
            $checkStock->execute();

            if ($checkStock->rowCount() > 0) {
                $updateStatus = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity - :productQuantity, status_product = 'negativado' WHERE id = :product_id");
                $updateStatus->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $updateStatus->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $updateStatus->execute();
            } else {
                $updateStock = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity - :productQuantity, status_product = 'Em estoque' WHERE id = :product_id");
                $updateStock->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $updateStock->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $updateStock->execute();
            }

            $exec = $sql->prepare("UPDATE sales SET total_value = :totalValue WHERE id = :lastSaleId");
            $exec->bindParam(':totalValue', $requestData['totalValue'], PDO::PARAM_STR);
            $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
            $exec->execute();

            foreach ($selectedPortion as $portion) {
                $portion_value = floatval($portion['portionValue']);
                $portion_total = $portion['portionTotal'];
            
                $execPortion = $sql->prepare("INSERT INTO sales_portion (number_portion, date_portion, value_portion, id_sales) 
                    VALUES (:portionTotal, NOW(), :portionValue, :lastSaleId)");
                $execPortion->bindParam(':portionTotal', $portion_total, PDO::PARAM_INT);
                $execPortion->bindParam(':portionValue', $portion_value, PDO::PARAM_STR);
                $execPortion->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
                $execPortion->execute();
            }
            

            $sql->commit();

            echo json_encode(['success' => true, 'message' => htmlspecialchars('Venda registrada com sucesso')]);
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