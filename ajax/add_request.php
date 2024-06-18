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
    $request_data = json_decode(file_get_contents('php://input'), true);

    $SelectedFatPed = $request_data['SelectedFatPed'] ?? [];
    $totalCardFinal = $request_data['totalCardFinal'] ?? '';
    $ButtonSelected = $request_data['ButtonSelected'] ?? '';

    try {
        $sql = DB::Connection();
        $sql->beginTransaction();

        $status = 1;
        $boxpdv_open = status_boxpdv_request($status);

        if (!$boxpdv_open) {
            throw new Exception('O caixa está fechado. Não é possível registrar o pedido.');
        }

        $id_users_request = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        $check_box_request = $sql->prepare("SELECT id FROM boxpdv WHERE id_users = :id_users AND status = 1");
        $check_box_request->bindParam(':id_users', $id_users_request, PDO::PARAM_INT);
        $check_box_request->execute();
        $id_boxpdv_request = $check_box_request->fetchColumn();

        if (empty($id_users_request)) {
            echo json_encode(['error' => true, 'message' => htmlspecialchars('Usuario não encontrado')]);
            return;
        }

        if (empty($id_boxpdv_request)) {
            echo json_encode(['error' => true, 'message' => htmlspecialchars('Caixa não encontrado')]);
            return;
        }

        foreach ($SelectedFatPed as $SelectedFatPed) {
            $current_command = $SelectedFatPed['currentCommandId'];

            if ($current_command == null || $current_command == '') {
                echo json_encode(['error' => true, 'message' => htmlspecialchars('Comanda não encontrada')]);
                break;
            }

            if (!is_numeric($current_command)) {
                echo json_encode(['error' => true, 'message' => htmlspecialchars('Valor do produto precisa ser válido')]);
                break;
            }

            $exec = $sql->prepare("INSERT INTO request (id_table, total_request, id_boxpdv_request, id_users_request, date_request, status) 
            VALUES (:id_table, :total_request, :id_boxpdv_request, :id_users_request, NOW(), :status)");

            $exec->bindParam(':id_table', $current_command, PDO::PARAM_INT);
            $exec->bindParam(':id_users_request', $id_users_request, PDO::PARAM_INT);
            $exec->bindParam(':total_request', $totalCardFinal, PDO::PARAM_INT);
            $exec->bindParam(':id_boxpdv_request', $id_boxpdv_request, PDO::PARAM_INT);
            $status = 3;
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->execute();

            $last_request_id = $sql->lastInsertId();

            $productId = isset($SelectedFatPed['productID']) ? $SelectedFatPed['productID'] : null;

            if ($productId === null) {
                echo json_encode(['error' => true, 'message' => htmlspecialchars('Id do produto não encontrado')]);
                return;
            }

            $productQuantity = $SelectedFatPed['quantity'];
            $productValue = floatval($SelectedFatPed['totalcard']);

            if (!$productValue) {
                echo json_encode(['error' => true, 'message' => htmlspecialchars('Valor do produto precisa ser válido')]);
                return;
            }

            if (!$productQuantity) {
                echo json_encode(['error' => true, 'message' => htmlspecialchars('Quantidade do produto precisa ser válida')]);
                return;
            }

            $exec = $sql->prepare("INSERT INTO request_items (id_request, id_products, quantity, price_request) 
                        VALUES (:last_request_id, :product_id, :product_stock_quantity, :product_value)");
            $exec->bindParam(':last_request_id', $last_request_id, PDO::PARAM_INT);
            $exec->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $exec->bindParam(':product_stock_quantity', $productQuantity, PDO::PARAM_INT);
            $exec->bindParam(':product_value', $productValue, PDO::PARAM_STR);
            $exec->execute();

            foreach ($ButtonSelected as $ButtonSelected) {
                $button_id = $ButtonSelected['paymentId'];
                $value_payment = $ButtonSelected['paymentValue'];

                if (!$button_id || !$value_payment) {
                    echo json_encode(['error' => true, 'message' => htmlspecialchars('Não foi possível fazer faturamento de pedido')]);
                    return;
                }

                $payment_table = $sql->prepare("INSERT INTO request_payments (id_request, id_payment_method, value_payment) VALUES(:id_request, :id_payment_method, :value_payment)");
                $payment_table->bindValue('id_request', $last_request_id, PDO::PARAM_INT);
                $payment_table->bindValue('id_payment_method', $button_id, PDO::PARAM_INT);
                $payment_table->bindValue('value_payment', $value_payment, PDO::PARAM_STR);
                $payment_table->execute();

                $exec = $sql->prepare("INSERT INTO sales (id_payment_method, id_client, id_boxpdv, id_users, date_sales, status, id_request) 
                    VALUES (:paymentMethod, :salesClient, :id_boxpdv, :id_users, NOW(), :status, :id_request)");

                $salesClient = 1;
                $exec->bindValue('paymentMethod', $button_id, PDO::PARAM_INT);
                $exec->bindValue('salesClient', $salesClient, PDO::PARAM_INT);
                $exec->bindValue('id_boxpdv', $id_boxpdv_request, PDO::PARAM_INT);
                $exec->bindValue('id_users', $id_users_request, PDO::PARAM_INT);
                $exec->bindValue('status', 4, PDO::PARAM_INT);
                $exec->bindValue('id_request', $last_request_id, PDO::PARAM_INT);
                $exec->execute();
            }

            $table_update = $sql->prepare("UPDATE table_requests SET status_table = 1 WHERE id = :id_table");
            $table_update->bindParam(':id_table', $current_command, PDO::PARAM_INT);
            $table_update->execute();

            $checkoutStock = $sql->prepare("SELECT id FROM products WHERE id = :productid AND stock_quantity - :product_stock_quantity < 0");
            $checkoutStock->bindValue('productid', $productId, PDO::PARAM_INT);
            $checkoutStock->bindValue('product_stock_quantity', $productQuantity, PDO::PARAM_INT);
            $checkoutStock->execute();

            if ($checkoutStock->rowCount() > 0) {
                $exec = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity - :productquantity, status_product = 'negativo' WHERE id = :productId");
                $exec->bindParam('productId', $productId, PDO::PARAM_INT);
                $exec->bindParam('productquantity', $productQuantity, PDO::PARAM_INT);
                $exec->execute();
            } else {
                $exec = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity - :productquantity, status_product = 'Em estoque' WHERE id = :productId");
                $exec->bindParam('productId', $productId, PDO::PARAM_INT);
                $exec->bindParam('productquantity', $productQuantity, PDO::PARAM_INT);
                $exec->execute();
            }
        }

        $sql->commit();
        echo json_encode(['success' => true, 'message' => htmlspecialchars('Pedido registrado com sucesso')]);

    } catch (PDOException $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    } finally {
        $sql = null;
    }
}
?>