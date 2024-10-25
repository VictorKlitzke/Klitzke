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
    $requestDataAPrazo = json_decode(file_get_contents('php://input'), true);

    $selectedPaymentMethod = $requestDataAPrazo['idPaymentMethod'] ?? '';
    $id_sales_client = $requestDataAPrazo['selectedClientId'] ?? '';
    $selectedProducts = $requestDataAPrazo['selectedProducts'] ?? [];
    $selectedAprazo = $requestDataAPrazo['selectedAprazo'] ?? [];

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

            foreach ($selectedAprazo as $Aprazo) {

                $portion_value = floatval($Aprazo['installmentValue']);
                $portionAprazo = floatval($Aprazo['portionAprazo']);
                $date_venciment = $Aprazo['date_venciment'];
                $date = DateTime::createFromFormat('d/m/Y', $date_venciment);
                $date_venciment_SQL = $date->format('Y-m-d');
                $status_aprazo = 'Pendente';
                $type = 'Receita';

                $execPortion = $sql->prepare("INSERT INTO sales_aprazo (date_venciment, value_aprazo, status, sale_id, installments_count, type) 
                                  VALUES (:date_venciment, :installmentValue, :status_aprazo, :lastSaleId, :portionAprazo, :type)");
                $execPortion->bindParam(':date_venciment', $date_venciment_SQL, PDO::PARAM_STR);
                $execPortion->bindParam(':installmentValue', $portion_value, PDO::PARAM_STR);
                $execPortion->bindParam(':status_aprazo', $status_aprazo, PDO::PARAM_STR);
                $execPortion->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
                $execPortion->bindParam(':portionAprazo', $portionAprazo, PDO::PARAM_INT);
                $execPortion->bindParam(':type', $type, PDO::PARAM_STR);

                if ($execPortion->execute() === false) {
                    $errorInfo = $execPortion->errorInfo();
                    http_response_code(500);
                    echo json_encode(['error' => 'Erro no banco de dados: ' . $errorInfo[2], 'code' => $errorInfo[0]]);
                }
            }

            foreach ($selectedProducts as $product) {

                $product_id = isset($product['productId']) ? $product['productId'] : null;

                if ($product_id === null) {
                    throw new Exception("Erro ao obter ID do produto.");
                }

                $productQuantity = $product['quantity'];
                $productValue = isset($product['productPrice']) ? floatval($product['productPrice']) : 0.0;
                
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
            $exec->bindParam(':totalValue', $requestDataAPrazo['totalValue'], PDO::PARAM_STR);
            $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            echo json_encode(['success' => true, 'message' => htmlspecialchars('Venda registrada com sucesso')]);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        $sql = null;
    }
}

?>