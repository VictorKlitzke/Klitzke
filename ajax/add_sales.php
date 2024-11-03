<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../helpers/response.php';
include_once '../classes/panel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$today = date('Y-m-d H:i:s');

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

    $selectedPaymentMethod = isset($requestData['idPaymentMethod']) ? $requestData['idPaymentMethod'] : '';
    $id_sales_client = $requestData['selectedClientId'] ?? '';
    $selectedProducts = $requestData['selectedProducts'] ?? [];
    $totalValueSales = $requestData['totalValue'] ?? '';

    try {

        $sql = Db::Connection();
        $sql->beginTransaction();

        $status = 1;
        $boxpdv_open = status_boxpdv($status);
        $type_movement = 'Saida';
        $stock = 'Em estoque';
        $negative = 'Negativado';

        if (!$boxpdv_open) {
            throw new Exception('O caixa está fechado. Não é possível registrar a venda.');
        } else {

            $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

            $checkBoxOpen = $sql->prepare("SELECT id FROM boxpdv WHERE id_users = :user_id AND status = 1");
            $checkBoxOpen->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $checkBoxOpen->execute();
            $id_boxpdv = $checkBoxOpen->fetchColumn();

            if (!$id_boxpdv) {
                echo json_encode(['error' => 'Nenhum caixa aberto']);
                return;
            }

            $exec = $sql->prepare("SELECT * FROM sales WHERE id_users = :user_id");
            $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result = $exec->fetchAll(PDO::FETCH_ASSOC);

            if (!$user_id) {
                echo json_encode(['error' => 'Nenhum usuário logado']);
                return;
            }

            $exec = $sql->prepare("INSERT INTO sales (id_payment_method, id_client, id_boxpdv, id_users, date_sales, status, total_value) 
                VALUES (:paymentMethod, :salesClient, :id_boxpdv, :id_users, NOW(), :status, :totalValueSales)");
            $exec->bindParam(':paymentMethod', $selectedPaymentMethod, PDO::PARAM_INT);
            $exec->bindParam(':salesClient', $id_sales_client, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->bindParam(':id_boxpdv', $id_boxpdv, PDO::PARAM_INT);
            $status = 1;
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->bindParam(':totalValueSales', $totalValueSales, PDO::PARAM_INT);
            $exec->execute();

            $lastSaleId = $sql->lastInsertId();

            foreach ($selectedProducts as $product) {

                $product_id = isset($product['productId']) ? $product['productId'] : null;
                $productQuantity = isset($product['quantity']) ? $product['quantity'] : 0; 
                $productValue = floatval($product['productPrice']);
            
                if ($product_id === null) {
                    throw new Exception("Erro ao obter ID do produto.");
                }
            
                $exec = $sql->prepare("INSERT INTO sales_items (id_sales, id_product, amount, price_sales, status_item) 
                            VALUES (:lastSaleId, :product_id, :productQuantity, :productValue, :status_item)");
                $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
                $exec->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $exec->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $exec->bindParam(':productValue', $productValue, PDO::PARAM_STR);
                $status_item = 1;
                $exec->bindParam(':status_item', $status_item, PDO::PARAM_INT);
                $exec->execute();
            
                $checkStock = $sql->prepare("SELECT product_id FROM product_movements WHERE product_id = :productId GROUP BY product_id HAVING SUM(quantity) < :productQuantity");
                $checkStock->bindParam(':productId', $product_id, PDO::PARAM_INT);
                $checkStock->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $checkStock->execute();
            
                if ($checkStock->rowCount() > 0) {
                    $insertNegativeMovement = $sql->prepare("INSERT INTO product_movements (product_id, type, quantity, value, date, status_product) 
                                                            VALUES (:productId, :type, -:negativeQuantity, :value, NOW(), :status_product)");
                    $insertNegativeMovement->bindParam(':productId', $product_id, PDO::PARAM_INT);
                    $insertNegativeMovement->bindParam(':type', $type_movement, PDO::PARAM_STR);
                    $insertNegativeMovement->bindParam(':negativeQuantity', $productQuantity, PDO::PARAM_INT);
                    $insertNegativeMovement->bindParam(':value', $productValue, PDO::PARAM_STR); 
                    $insertNegativeMovement->bindParam(':status_product', $negative, PDO::PARAM_STR);
                    $insertNegativeMovement->execute();
                } else {
                    $insertMovement = $sql->prepare("INSERT INTO product_movements (product_id, type, quantity, value, date, status_product) 
                                                    VALUES (:productId, :type, -:quantity, :value, NOW(), :status_product)");
                    $insertMovement->bindParam(':productId', $product_id, PDO::PARAM_INT);
                    $insertMovement->bindParam(':type', $type_movement, PDO::PARAM_STR);
                    $insertMovement->bindParam(':quantity', $productQuantity, PDO::PARAM_INT);
                    $insertMovement->bindParam(':value', $productValue, PDO::PARAM_STR); 
                    $insertMovement->bindParam(':status_product', $stock, PDO::PARAM_STR);
                    $insertMovement->execute();
                }
            }

            $exec = $sql->prepare("UPDATE sales SET total_value = :totalValue WHERE id = :lastSaleId");
            $exec->bindParam(':totalValue', $requestData['totalValue'], PDO::PARAM_STR);
            $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Venda realizada com sucesso";
            Panel::LogAction($user_id, 'Venda realizada com com sucesso: ID Forma de pagamento: ' . $selectedPaymentMethod, $message_log, $today);
            echo json_encode(['success' => true, 'message' => 'Venda registrada com sucesso']);
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