<?php

include_once '../classes/panel.php';
include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id_users_inativar'])) {
    $id_users_inativar = $data['id_users_inativar'];
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID do usuário a ser inativado não foi enviado.'
    ]);
    exit;
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

class Inativar
{

    public static function InativarUsers($id_users_inativar, $user_id)
    {

        $message_log = "Usuário ID $id_users_inativar inativado.";

        $sql = Db::Connection();
        $today = date("Y-m-d H:i:s"); 

        $exec1 = $sql->prepare("SELECT * FROM users WHERE id = :id_users");
        $exec1->bindValue(':id_users', $user_id, PDO::PARAM_INT);
        $exec1->execute();
        $result1 = $exec1->fetch();

        if (!$result1) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuário não encontrado ou sem permissão.'
            ]);
            exit;
        }

        $exec = $sql->prepare("SELECT * FROM users WHERE id = :id_users_inativar");
        $exec->bindValue(':id_users_inativar', $id_users_inativar, PDO::PARAM_INT);
        $exec->execute();
        $result = $exec->fetch();

        if (!$result) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuário a ser inativado não encontrado.'
            ]);
            exit;
        }

        if ($result1['access'] == 100) {
            if ($result['disable'] != 2) {
                try {
                    Panel::LogAction($user_id, 'Inativar usuário',$message_log, $today);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Falha ao registrar log: ' . $e->getMessage()
                    ]);
                    return;
                }

                self::UpdateInativar($id_users_inativar, $sql);
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuário inativado com sucesso'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuário já está inativado'
                ]);
            }
        } else {
            Response::json(false, 'Usuário não tem permissão para essa ação', $today);
        }
    }

    public static function UpdateInativar($id_users_inativar, $sql)
    {

        $disable = 2;

        try {
            $sql->beginTransaction();

            $exec = $sql->prepare("UPDATE users SET disable = :disable WHERE id = :id_users_inativar");
            $exec->bindValue(':disable', $disable, PDO::PARAM_INT);
            $exec->bindValue(':id_users_inativar', $id_users_inativar, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

        } catch (Exception $e) {
            $sql->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
}

Inativar::InativarUsers($id_users_inativar, $user_id);

?>