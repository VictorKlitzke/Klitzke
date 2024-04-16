<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id_order_request = $_POST['id_order'];

function status_boxpdv($status)
{
    $sql = Db::Connection();

    $exec = $sql->prepare("SELECT COUNT(*) FROM boxpdv WHERE status = :status");
    $exec->bindParam(':status', $status, PDO::PARAM_INT);
    $exec->execute();

    return $exec->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $new_products = $requestData['newProduct'] ?? '';

    try {

        $status_order = 2;
        $status = 1;
        $boxpdv_open = status_boxpdv($status);

        $sql = Db::Connection();
        $sql->beginTransaction();

        $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        $checkBoxOpen = $sql->prepare("SELECT id FROM boxpdv WHERE id_users = :user_id AND status = 1");
        $checkBoxOpen->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkBoxOpen->execute();
        $id_boxpdv = $checkBoxOpen->fetchColumn();

        $exec = $sql->prepare("UPDATE request SET date_request = ? status = ? AND total_request ? AND id_users_request = ? AND id_boxpdv_request = ? where id = ?");
        $exec->bindValue('id', $id_order_request, PDO::PARAM_INT);
        $exec->bindValue('id_users_request', $user_id, PDO::PARAM_INT);
       
        $exec->execute();


        $sql->commit();

        echo json_encode(['success' => true, 'message' => htmlspecialchars('Pedido registrada com sucesso')]);

    } catch (PDOException $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    } finally {
        $sql = null;
    }

}

?>