<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../classes/panel.php';
include_once '../classes/controllers.php';
include_once '../helpers/response.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$today = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $data = json_decode(file_get_contents('php://input'), true);
    $sql = Db::Connection();

    if (is_null($data)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON data'
        ]);
        exit;
    }

    if (empty($data)) {
        echo json_encode([
            'success' => false,
            'message' => 'No user data found'
        ]);
        exit;
    }

    $id_forn_delete = isset($data['id_forn_delete']) ? base64_decode($data['id_forn_delete']) : null;
    $id_users_delete = isset($data['id_user_delete']) ? base64_decode($data['id_user_delete']) : null;
    $id_clients_delete = isset($data['id_clients_delete']) ? base64_decode($data['id_clients_delete']) : null;

    if (isset($data['type'])) {

        if ($data['type'] == 'deleteUser') {
            Deletes::DeleteUsers($today, $sql, $id_users_delete, $user_id);
        } else if ($data['type'] == 'deleteClients') {
            Deletes::DeleteClients($today, $sql, $id_clients_delete, $user_id);
        } else if ($data['type'] == 'deleteForn') {
            Deletes::DeleteForn($today, $sql, $id_forn_delete, $user_id);
        }
    }

}

class Deletes
{

    public static function UserAccess($sql, $user_id)
    {

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("SELECT access FROM users WHERE ID = :user_id");
            $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();
            return $exec->fetchColumn();

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function DeleteUsers($today, $sql, $id_users_delete, $user_id)
    {

        if (empty($id_users_delete)) {
            Response::json(false, 'Id do usuário vazio');
        }

        try {

            if (self::UserAccess($sql, $user_id) < 100) {
                Response::json(false, 'Usuário não tem permissão para esse comando', $today);
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("DELETE FROM users WHERE id = :id_users_delete");
            $exec->bindParam(':id_users_delete', $id_users_delete);
            $exec->execute();

            $sql->commit();

            $message_log = "Usuário $id_users_delete deletado com sucesso";
            Panel::LogAction($user_id, 'Deletar Usuário', $message_log, $today);
            Response::send(true, 'Usuário deletado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }

    public static function DeleteForn($today, $sql, $id_forn_delete, $user_id)
    {

        if (empty($id_forn_delete)) {
            Response::json(false, 'Id do cliente vazio');
        }

        try {

            if (self::UserAccess($sql, $user_id) < 100) {
                Response::json(false, 'Usuário não tem permissão para esse comando', $today);
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("DELETE FROM suppliers WHERE id = :id_forn_delete");
            $exec->bindParam(':id_forn_delete', $id_forn_delete);
            $exec->execute();

            $sql->commit();

            $message_log = "Fornecedor $id_forn_delete deletado com sucesso";
            Panel::LogAction($user_id, 'Deletar Fornecedor', $message_log, $today);
            Response::send(true, 'Fornecedor deletado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function DeleteClients($today, $sql, $id_clients_delete, $user_id)
    {

        if (empty($id_clients_delete)) {
            Response::json(false, 'Id do cliente vazio');
        }

        try {

            if (self::UserAccess($sql, $user_id) < 100) {
                Response::json(false, 'Usuário não tem permissão para esse comando', $today);
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("DELETE FROM clients WHERE id = :id_clients_delete");
            $exec->bindParam(':id_clients_delete', $id_clients_delete);
            $exec->execute();

            $sql->commit();

            $message_log = "Cliente $id_clients_delete deletado com sucesso";
            Panel::LogAction($user_id, 'Deletar Cliente', $message_log, $today);
            Response::send(true, 'Cliente deletado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
}




?>