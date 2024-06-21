<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$sql = Db::Connection();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_sales_cancel']) || empty($data['id_sales_cancel'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID da venda não fornecido.'
    ]);
    exit;
}

$id_sales_cancel = $data['id_sales_cancel'];

$exec = $sql->prepare("SELECT * FROM sales WHERE id = :id_inativar");
$exec->bindValue('id_inativar', $id_sales_cancel, PDO::PARAM_INT);
$exec->execute();
$request = $exec->fetch();

if ($request && $request['status'] != 2) {
    updateSales($id_sales_cancel, $sql);
    echo json_encode([
        'success' => true,
        'message' => 'Venda cancelada com sucesso.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Não foi possível cancelar a venda, pois a venda já está inativada.'
    ]);
}

function updateSales($id_sales_cancel, $sql){

    $status_cancel = 2;
    $status_item_cancel = 2;

    try {

        $sql->beginTransaction();

        $exec = $sql->prepare("UPDATE sales SET status = :status WHERE id = :id_sales_cancel");
        $exec->bindValue('status', $status_cancel, PDO::PARAM_INT);
        $exec->bindValue('id_sales_cancel', $id_sales_cancel, PDO::PARAM_INT);
        $exec->execute();

        $exec_item = $sql->prepare("UPDATE sales_items SET status_item = :status_item_cancel WHERE id_sales = :id_sales_cancel");
        $exec_item->bindValue('status_item_cancel', $status_item_cancel, PDO::PARAM_INT);
        $exec_item->bindValue('id_sales_cancel', $id_sales_cancel, PDO::PARAM_INT);
        $exec_item->execute();

        $exec = $sql->prepare("SELECT id_product, amount FROM sales_items WHERE id_sales = :id_sales_cancel");
        $exec->bindValue('id_sales_cancel', $id_sales_cancel, PDO::PARAM_INT);
        $exec->execute();
        $items = $exec->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $productId = $item['id_product'];
            $productQuantity = $item['amount'];

            if (!$productId) {
                echo json_encode(['error' => 'ID produto invalido' ]);
                return;
            }

            $exec = $sql->prepare("UPDATE products SET stock_quantity = stock_quantity + :productquantity WHERE id = :productId");
            $exec->bindParam('productId', $productId, PDO::PARAM_INT);
            $exec->bindParam('productquantity', $productQuantity, PDO::PARAM_INT);
            $exec->execute();
        }

        $sql->commit();

    } catch (Exception $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    }
}

?>