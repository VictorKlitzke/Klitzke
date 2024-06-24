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
    $response_table = $data;
    $response_account = $data;

    if ($data['type'] == 'users') {
        Register::RegisterUsers($sql, $response_users, $user_id);
    } else if ($data['type'] == 'table_request') {
        Register::RegisterTableRequest($sql, $response_table, $user_id);
    } else if ($data['type'] == 'account') {
        Register::RegisterAccount($sql, $response_account, $user_id);
    }

}

class Register
{
    public static function RegisterUsers($sql, $response_users, $user_id)
    {

        $disable = 1;
        $today = date("Y-m-d H:i:s");

        $name = filter_var($response_users['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_users['email'], FILTER_VALIDATE_EMAIL);
        $password = $response_users['password'];
        $login = filter_var($response_users['name'], FILTER_SANITIZE_STRING);
        $phone = filter_var($response_users['phone'], FILTER_SANITIZE_STRING);
        $function = filter_var($response_users['userFunction'], FILTER_SANITIZE_STRING);
        $commission = filter_var($response_users['commission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $target_commission = filter_var($response_users['targetCommission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $access = filter_var($response_users['access'], FILTER_SANITIZE_NUMBER_INT);

        if (!$access || !$name || !$password || !$function || !$phone) {
            Response::json(false, 'Campos invalidos', $today);
        }

        try {

            $sql->BeginTransaction();

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $exec = $sql->prepare("INSERT INTO users (name, email, password, login, phone, function, 
                                commission, target_commission, access, disable)
                                VALUES (:name, :email, :password, :login, :phone, :function, :commission, :target_commission, :access, :disable)");
            $exec->bindValue(':name', $name, PDO::PARAM_STR);
            $exec->bindValue(':email', $email, PDO::PARAM_STR);
            $exec->bindValue(':password', $hashed_password, PDO::PARAM_STR);
            $exec->bindValue(':login', $login, PDO::PARAM_STR);
            $exec->bindValue(':phone', $phone, PDO::PARAM_STR);
            $exec->bindValue(':function', $function, PDO::PARAM_STR);
            $exec->bindValue(':commission', $commission, PDO::PARAM_STR);
            $exec->bindValue(':target_commission', $target_commission, PDO::PARAM_STR);
            $exec->bindValue(':access', $access, PDO::PARAM_STR);
            $exec->bindValue(':disable', $disable, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Usuário $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Usuário', $message_log);
            Response::send(true, 'Usuário cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }

    public static function RegisterClient($sql)
    {

    }

    public static function RegisterCompany($sql)
    {

    }

    public static function RegisterTableRequest($sql, $response_table, $user_id)
    {

        $status = 0;
        $today = date('Y-m-d H:i:s');
        $name = filter_var($response_table['name'], FILTER_SANITIZE_STRING);
        $name_table = "table_requests";

        if (!$name) {
            Response::json(false, 'Campo invalido', $today);
        }

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO $name_table (name, status_table) VALUES (:name, :status_table)");
            $exec->bindValue(':name', $name, PDO::PARAM_STR);
            $exec->bindValue(':status_table', $status, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Mesa $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Mesa', $message_log);
            Response::send(true, 'Mesa cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public static function RegisterAccount($sql, $response_account, $user_id) {

        $today = date('Y-m-d H:i:s');
        $id_company = Controllers::Select('company');
        $company = $id_company['id'];

        $name_holder = filter_var($response_account['name_holder'], FILTER_SANITIZE_STRING);
        $city = filter_var($response_account['city'], FILTER_SANITIZE_STRING);
        $pix = filter_var($response_account['pix'], FILTER_SANITIZE_STRING);

        $name_table = "banck_account";

        if (!$name_holder || !$pix || $city) {
            Response::json(false, 'Campo invalido', $today);
        }

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO $name_table (pix, account_holder_name, city, id_company) 
                                    VALUES (:pix, :account_holder_name, :city, :id_company)");
            $exec->bindValue(':pix', $pix, PDO::PARAM_STR);
            $exec->bindValue(':account_holder_name', $name_holder, PDO::PARAM_STR);
            $exec->bindValue(':city', $city, PDO::PARAM_STR);
            $exec->bindValue(':id_company', $company, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}

?>