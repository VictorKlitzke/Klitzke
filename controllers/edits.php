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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = Db::Connection();
    $data = json_decode(file_get_contents('php://input'), true);

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

    $response_users = $data;
    $responseEditClient = $data;
    $responseEditCompany = $data;
    $responseEditSuppliers = $data;

    if ($data['type'] == 'edituser') {
        Edit::UpdateUser($sql, $response_users, $user_id);
    } else if ($data['type'] == 'editclients') {
        Edit::UpdateClient($sql, $responseEditClient, $user_id);
    } else if ($data['type'] == 'editcompany') {
        Edit::UpdateCompany($sql, $responseEditCompany, $user_id);
    } else if ($data['type'] == 'editsuppliers') {
        Edit::UpdateSupplier($sql, $responseEditSuppliers, $user_id);
    } else {
        Response::json(false, 'Tipo type não encontrado', $today);
    }
}

class Edit
{

    public static function UpdateUser($sql, $response_users, $user_id)
    {

        $today = date('Y-m-d H:i:s');

        $name = filter_var($response_users['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_users['email'], FILTER_VALIDATE_EMAIL);
        $login = filter_var($response_users['name'], FILTER_SANITIZE_STRING);
        $phone = filter_var($response_users['phone'], FILTER_SANITIZE_STRING);
        $function = filter_var($response_users['userFunction'], FILTER_SANITIZE_STRING);
        $commission = filter_var($response_users['commission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $target_commission = filter_var($response_users['targetCommission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        try {

            $sql->BeginTransaction();

            $stmt = $sql->prepare("UPDATE users SET 
            name = :name, 
            email = :email, 
            password = :password, 
            login = :login, 
            phone = :phone, 
            userFunction = :userFunction, 
            commission = :commission, 
            targetCommission = :targetCommission
            WHERE id = :id");

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':userFunction', $function);
            $stmt->bindParam(':commission', $commission);
            $stmt->bindParam(':targetCommission', $target_commission);
            $stmt->bindParam(':targetCommission', $target_commission);

            $sql->commit();

            $message_log = "Usuário $name Editadoo com sucesso";
            Panel::LogAction($user_id, 'Editar Usuário', $message_log, $today);
            Response::send(true, 'Usuário editado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function UpdateClient($sql, $responseEditClient, $user_id)
    {

    }
    public static function UpdateCompany($sql, $responseEditCompany, $user_id)
    {

    }
    public static function UpdateSupplier($sql, $responseEditSuppliers, $user_id)
    {

    }

}

?>