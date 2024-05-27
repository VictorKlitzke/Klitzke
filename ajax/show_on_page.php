<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$sql = Db::Connection();

if (!isset($data['id_product']) || empty($data['id_product'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID do pedido não fornecido.'
    ]);
    exit;
}

$id_product_page = $data['id_product'];

$exec = $sql->prepare("SELECT products WHERE id = :id_product");
$exec->bindValue('id_product', $id_product_page, PDO::PARAM_INT);
$exec->execute();
$result = $exec->fetch();

if ($result['show_on_page'] != 1) {
    showPage($id_product_page, $sql);
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

function showPage($id_product_page, $sql) {

    $show_on_page = 1;

    try {

        $sql->beginTransaction();

        $exec = $sql->prepare("UPDATE products SET show_on_page = :show_on_page WHERE id = :id_product");
        $exec->bindValue('show_on_page', $show_on_page, PDO::PARAM_INT);
        $exec->bindValue('id_product', $id_product_page, PDO::PARAM_INT);
        $exec->execute();

        $sql->commit();

    } catch (Exception $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    }
}




?>
