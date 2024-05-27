<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$sql = Db::Connection();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_inativar']) || empty($data['id_inativar'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID do pedido não fornecido.'
    ]);
    exit;
}

$id_request_inativar = $data['id_inativar'];

$exec = $sql->prepare("SELECT * FROM request WHERE id = :id_inativar");
$exec->bindValue('id_inativar', $id_request_inativar, PDO::PARAM_INT);
$exec->execute();
$request = $exec->fetch();

if ($request && $request['status'] != 2) {
    inativar($id_request_inativar, $sql);
    echo json_encode([
        'success' => true,
        'message' => 'Pedido inativado com sucesso.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Não foi possível inativar o pedido, pois o pedido já está inativado.'
    ]);
}

function inativar($id_request_inativar, $sql)
{
    $status_inativo = 2;

    try {
        $sql->beginTransaction();

        $exec = $sql->prepare("UPDATE request SET status = :status WHERE id = :id_inativar");
        $exec->bindValue('status', $status_inativo, PDO::PARAM_INT);
        $exec->bindValue('id_inativar', $id_request_inativar, PDO::PARAM_INT);
        $exec->execute();

        $exec = $sql->prepare("SELECT id_products, quantity FROM request_items WHERE id_request = :id_inativar");
        $exec->bindValue('id_inativar', $id_request_inativar, PDO::PARAM_INT);
        $exec->execute();
        $items = $exec->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $productId = $item['id_products'];
            $productQuantity = $item['quantity'];

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
