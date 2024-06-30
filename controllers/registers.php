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
    $response_company = $data;
    $response_clients = $data;
    $response_products = $data;
    $response_forn = $data;

    if ($data['type'] == 'users') {
        Register::RegisterUsers($sql, $response_users, $user_id);
    } else if ($data['type'] == 'table_request') {
        Register::RegisterTableRequest($sql, $response_table, $user_id);
    } else if ($data['type'] == 'account') {
        Register::RegisterAccount($sql, $response_account, $user_id);
    } else if ($data['type'] == 'forn') {
        Register::RegisterForn($sql, $response_forn, $user_id);
    } else if ($data['type'] == 'clients') {
        Register::RegisterClient($sql, $response_client, $user_id);
    } else if ($data['type'] == 'products') {

    } else if ($data['type'] == 'company') {
        Register::RegisterCompany($sql, $response_company, $user_id);
    }

}

class Register
{
    public static function RegisterUsers($sql, $response_users, $user_id){

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

    public static function RegisterClient($sql, $response_clients, $user_id){

        $today = date("Y-m-d H:i:s");

        $name = filter_var($response_clients['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_clients['email'], FILTER_VALIDATE_EMAIL);
        $social_reason = filter_var($response_clients['social_reason'], FILTER_SANITIZE_STRING);
        $cpf = filter_var($response_clients['cpf'], FILTER_SANITIZE_STRING);
        $phone = filter_var($response_clients['phone'], FILTER_SANITIZE_STRING);
        $address = filter_var($response_clients['address'], FILTER_SANITIZE_STRING);
        $city = filter_var($response_clients['city'], FILTER_SANITIZE_STRING);
        $cep = filter_var($response_clients['cep'], FILTER_VALIDATE_NUMBER);
        $neighborhood = filter_var($response_clients['neighborhood'], FILTER_SANITIZE_NUMBER_INT);

        if ($name == "" || $social_reason == "" || $cpf == "") {
            Response::json(false, 'Campos invalidos', $today);
        }

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("
            INSERT INTO clients 
            (name, email, social_reason, cpf, phone, address, city, cep, neighborhood, users_id, created_at) 
            VALUES 
            (:name, :email, :social_reason, :cpf, :phone, :address, :city, :cep, :neighborhood, :user_id, :created_at)
        ");
            $exec->bindValue(':name', $name, PDO::PARAM_STR);
            $exec->bindValue(':email', $email, PDO::PARAM_STR);
            $exec->bindValue(':social_reason', $social_reason, PDO::PARAM_STR);
            $exec->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            $exec->bindValue(':phone', $phone, PDO::PARAM_STR);
            $exec->bindValue(':address', $address, PDO::PARAM_STR);
            $exec->bindValue(':city', $city, PDO::PARAM_STR);
            $exec->bindValue(':cep', $cep, PDO::PARAM_STR);
            $exec->bindValue(':neighborhood', $neighborhood, PDO::PARAM_STR);
            $exec->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $exec->bindValue(':created_at', $today, PDO::PARAM_STR);

            $exec->execute();

            $sql->commit();

            $message_log = "Cliente $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Cliente', $message_log);
            Response::send(true, 'Cliente cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public static function RegisterCompany($sql, $response_company, $user_id){

        $today = date("Y-m-d H:i:s");

        $name = filter_var($response_company['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_company['email'], FILTER_VALIDATE_EMAIL);
        $social_reason = filter_var($response_company['social_reason'], FILTER_SANITIZE_STRING);
        $cnpj = filter_var($response_company['cnpj'], FILTER_SANITIZE_STRING);
        $state_registration = filter_var($response_company['state_registration'], FILTER_SANITIZE_STRING);
        $address = filter_var($response_company['address'], FILTER_SANITIZE_STRING);
        $city = filter_var($response_company['city'], FILTER_SANITIZE_STRING);
        $phone = filter_var($response_company['phone'], FILTER_SANITIZE_NUMBER_INT);

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("
            INSERT INTO companies 
            (name, email, social_reason, cnpj, state_registration, address, city, phone, users_id, created_at) 
            VALUES 
            (:name, :email, :social_reason, :cnpj, :state_registration, :address, :city, :phone, :user_id, :created_at)
        ");
            $exec->bindValue(':name', $name, PDO::PARAM_STR);
            $exec->bindValue(':email', $email, PDO::PARAM_STR);
            $exec->bindValue(':social_reason', $social_reason, PDO::PARAM_STR);
            $exec->bindValue(':cnpj', $cnpj, PDO::PARAM_STR);
            $exec->bindValue(':state_registration', $state_registration, PDO::PARAM_STR);
            $exec->bindValue(':address', $address, PDO::PARAM_STR);
            $exec->bindValue(':city', $city, PDO::PARAM_STR);
            $exec->bindValue(':phone', $phone, PDO::PARAM_STR);
            $exec->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $exec->bindValue(':created_at', $today, PDO::PARAM_STR);

            $exec->execute();

            $sql->commit();

            $message_log = "Empresa $name cadastrada com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Empresa', $message_log);
            Response::send(true, 'Empresa cadastrada com sucesso', $today);


        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }

    public static function RegisterTableRequest($sql, $response_table, $user_id){

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

    public static function RegisterAccount($sql, $response_account, $user_id){

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

            $message_log = "Conta $pix cadastrada com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Conta', $message_log);
            Response::send(true, 'Conta cadastrada com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public static function RegisterProducts($sql, $response_products, $user_id) {

        $today = date('Y-m-d H:i:s');

        $name = filter_var($response_products['name'], FILTER_SANITIZE_STRING);
        $quantity = filter_var($response_products['quantity'], FILTER_SANITIZE_STRING);
        $stock_quantity = filter_var($response_products['stock_quantity'], FILTER_SANITIZE_STRING);
        $barcode = filter_var($response_products['barcode'], FILTER_SANITIZE_STRING);
        $value_product = filter_var($response_products['value_product'], FILTER_SANITIZE_STRING);
        $cost_value = filter_var($response_products['cost_value'], FILTER_SANITIZE_STRING);
        $reference = filter_var($response_products['reference'], FILTER_SANITIZE_STRING);
        $model = filter_var($response_products['model'], FILTER_SANITIZE_STRING);
        $brand = filter_var($response_products['brand'], FILTER_SANITIZE_STRING);
        $flow = filter_var($response_products['flow'], FILTER_SANITIZE_STRING);
        $register_date = filter_var($response_products['register_date'], FILTER_SANITIZE_STRING);

        try {

            $sql->BeginTransaction();

            $sql->commit();

            $message_log = "Produto $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Produto', $message_log);
            Response::send(true, 'Produto cadastrado com sucesso', $today);

        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }

    public static function RegisterForn($sql, $response_forn, $user_id) {

        $today = date('Y-m-d H:i:s');

        $name_company = filter_var($response_forn['name_company'], FILTER_SANITIZE_STRING);
        $fantasy_name = filter_var($response_forn['fantasy_name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_forn['email'], FILTER_VALIDATE_EMAIL);
        $phone = filter_var($response_forn['phone'], FILTER_SANITIZE_STRING);
        $address = filter_var($response_forn['address'], FILTER_SANITIZE_STRING);
        $city = filter_var($response_forn['city'], FILTER_SANITIZE_STRING);
        $state = filter_var($response_forn['state'], FILTER_SANITIZE_STRING);
        $cnpj = filter_var($response_forn['cnpj'], FILTER_SANITIZE_STRING);
    
        if (empty($name_company) || empty($fantasy_name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($cnpj)) {
            Response::json(false, 'Todos os campos são obrigatórios', $today);
            return;
        }
    
        try {
    
            $sql->beginTransaction();
    
            $exec = $sql->prepare("INSERT INTO suppliers (company, fantasy_name, email, phone, address, city, state, cnpjcpf) 
                                   VALUES (:name_company, :fantasy_name, :email, :phone, :address, :city, :state, :cnpj)");
    
            $exec->bindParam(':name_company', $name_company);
            $exec->bindParam(':fantasy_name', $fantasy_name);
            $exec->bindParam(':email', $email);
            $exec->bindParam(':phone', $phone);
            $exec->bindParam(':address', $address);
            $exec->bindParam(':city', $city);
            $exec->bindParam(':state', $state);
            $exec->bindParam(':cnpj', $cnpj);
    
            $exec->execute();
    
            $sql->commit();

            $message_log = "Fornecedor $name_company cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Fornecedor', $message_log);
            Response::send(true, 'Fornecedor cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}

?>