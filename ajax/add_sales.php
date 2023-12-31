<?php

include_once '../config/config.php';
include_once '../services/db.php';

function status_boxpdv($status)
{
    $status = 1;
    $sql = Db::Connection();

    $exec = $sql->prepare("SELECT COUNT(*) FROM boxpdv WHERE status = :status");
    $exec->bindParam(':status', $status, PDO::PARAM_INT);
    $exec->execute();

    return $exec->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $selectedPaymentMethod = $requestData['id_payment_method'] ?? '';
    $id_sales_client = $requestData['sales_id_client'] ?? '';
    $user_id = $requestData['user_id'] ?? '';
    $selectedProducts = $requestData['products'] ?? [];

    try {

        $sql = Db::Connection();
        $sql->beginTransaction();

        $boxpdv_open = status_boxpdv($status);

        if (!$boxpdv_open) {
            throw new Exception('O caixa está fechado. Não é possível registrar a venda.');
        } else {

            $exec = $sql->prepare("INSERT INTO sales (id_payment_method, id_client, id_users, date_sales, status) 
                VALUES (:paymentMethod, :salesClient, :id_users, NOW(), :status)");
            $exec->bindParam(':paymentMethod', $selectedPaymentMethod, PDO::PARAM_INT);
            $exec->bindParam(':salesClient', $id_sales_client, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            // $exec->bindParam(':id_boxpdv', $id_boxpdv, PDO::PARAM_INT);
            $status = 1;
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->execute();

            $lastSaleId = $sql->lastInsertId();

            foreach ($selectedProducts as $product) {
                $productId = $product['id'];
                $productQuantity = $product['stock_quantity'];
                $productValue = $product['value'];
                $productValue = floatval($productValue);

                $exec = $sql->prepare("INSERT INTO sales_items (id_sales, id_product, amount, price_sales, status_item) 
                                VALUES (:lastSaleId, :productId, :productQuantity, :productValue, :status_item)");
                $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
                $exec->bindParam(':productId', $productId, PDO::PARAM_INT);
                $exec->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $exec->bindParam(':productValue', $productValue, PDO::PARAM_STR);
                $status_item = 1;
                $exec->bindParam(':status_item', $status_item, PDO::PARAM_INT);
                $exec->execute();
            }

            $check_stock = $sql->prepare("SELECT id FROM products WHERE id = :productId AND stock_quantity - :productQuantity < 0");
            $check_stock->bindParam(':productId', $productId, PDO::PARAM_INT);
            $check_stock->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
            $check_stock->execute();

            if ($check_stock->rowCount() > 0) {
                $exec = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity - :productQuantity AND status_product = 'Negativado' WHERE id = :productId");
                $exec->bindParam(':productId', $productId, PDO::PARAM_INT);
                $exec->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $exec->execute();
            } else {
                $exec = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity - :productQuantity AND status_product = 'Em estoque' WHERE id = :productId");
                $exec->bindParam(':productId', $productId, PDO::PARAM_INT);
                $exec->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
                $exec->execute();
            }

            $exec = $sql->prepare("SELECT SUM(amount * price_sales) - SUM(discount) AS total FROM sales_items WHERE id_sales = :lastSaleId");
            $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
            $exec->execute();

            // $exec = $sql->prepare("UPDATE sales_items SET total_items = :total_items WHERE id_sales = :lastSaleId");
            // $exec->bindParam(':total_items', $totalValue, PDO::PARAM_STR);
            // $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
            // $exec->execute();

            $totalValue = $exec->fetchColumn();

            $exec = $sql->prepare("UPDATE sales SET total_value = :totalValue WHERE id = :lastSaleId");
            $exec->bindParam(':totalValue', $totalValue, PDO::PARAM_STR);
            $exec->bindParam(':lastSaleId', $lastSaleId, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            echo json_encode(['success' => true, 'message' => 'Venda registrada com sucesso']);

        }

    } catch (PDOException $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao adicionar venda ao banco de dados: ' . $e->getMessage()]);
    } finally {
        $sql = null;
    }
}

?>