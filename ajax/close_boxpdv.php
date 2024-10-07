<?php

include_once '../classes/panel.php';
include_once '../helpers/response.php';
include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

$sql = Db::Connection();
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$today = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $value_debit = $requestData['value_debit'] ?? 0;
    $value_credit = $requestData['value_credit'] ?? 0;
    $value_pix = $requestData['value_pix'] ?? 0;
    $value_money = $requestData['value_money'] ?? 0;
    $value_aprazo = $requestData['value_aprazo'] ?? 0;
    $date_close = $requestData['close_date'] ?? '';

    try {

        $sql->beginTransaction();
        $status_update = 2;
        $status_current = 1;

        $checkBoxOpen = $sql->prepare("SELECT id FROM boxpdv WHERE status = :status_current AND id_users = :id_users");
        $checkBoxOpen->bindParam(':status_current', $status_current, PDO::PARAM_INT);
        $checkBoxOpen->bindParam(':id_users', $user_id, PDO::PARAM_INT);
        $checkBoxOpen->execute();
        $id_boxpdv = $checkBoxOpen->fetchColumn();

        if (!$id_boxpdv) {
            throw new Exception('Nenhum caixa aberto encontrado.');
        } else {
            $value_debit = floatval($value_debit);
            $value_credit = floatval($value_credit);
            $value_pix = floatval($value_pix);
            $value_money = floatval($value_money);
            $value_aprazo = floatval($value_aprazo);

            $date_close = date('Y-m-d H:i:s', strtotime($date_close));

            $exec = $sql->prepare("INSERT INTO box_closing (id_boxpdv, value_debit, value_credit, value_pix, value_money, value_aprazo, date_close) 
                    VALUES (:id_boxpdv, :value_debit, :value_credit, :value_pix, :value_money, :value_aprazo, :date_close)");

            $exec->bindParam(':id_boxpdv', $id_boxpdv, PDO::PARAM_INT);
            $exec->bindParam(':value_debit', $value_debit, PDO::PARAM_STR);
            $exec->bindParam(':value_credit', $value_credit, PDO::PARAM_STR);
            $exec->bindParam(':value_pix', $value_pix, PDO::PARAM_STR);
            $exec->bindParam(':value_money', $value_money, PDO::PARAM_STR);
            $exec->bindParam(':value_aprazo', $value_aprazo, PDO::PARAM_STR);
            $exec->bindParam(':date_close', $date_close, PDO::PARAM_STR);
            $exec->execute();

            $exec = $sql->prepare("UPDATE boxpdv SET status = :status_update WHERE id_users = :id_users");
            $exec->bindParam(':status_update', $status_update, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Caixa Fechado";
            Panel::LogAction($user_id, 'Caixa fechado com sucesso', $message_log, $today);
            Response::send(true, 'Caixa fechado com sucesso', $today);
        }

    } catch (Exception $e) {
        $sql->rollBack();
        http_response_code(500);

        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        $sql = null;
    }
}
?>