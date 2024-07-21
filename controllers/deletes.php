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

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

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

    $id_users_delete = base64_decode($data['id_user_delete']);

    if (isset($data['type'])) {

        if ($data['type'] == 'deleteUser') {
            Deletes::DeleteUsers($today, $sql, $id_users_delete, $user_id);
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
}




?>