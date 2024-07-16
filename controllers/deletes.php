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

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $data = json_decode(file_get_contents('php://input'), true);
    $sql = Db::Connection();

    $id_users_delete = $data['id_users_delete'];

    if ($id_users_delete) {
        Deletes::DeleteUsers($sql, $id_users_delete, $user_id);
    }

}

class Deletes {
    public static function DeleteUsers($sql, $id_users_delete, $user_id) {

        if (empty($id_users_delete)) {
            Response::json(false, 'Id do usuário vazio');
        }

        try {

            $sql->BeginTransaction();

            $sql->commit();

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
}




?>