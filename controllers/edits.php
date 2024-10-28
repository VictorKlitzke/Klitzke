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
    $response_clients = $data;
    $response_company = $data;
    $response_forn = $data;
    $response_account_payable = $data;
    $response_inventory = $data;
    $response_products = $data;

    if (isset($data['type'])) {
        if ($data['type'] == 'edituser') {
            Edit::UpdateUser($sql, $response_users, $user_id);
        } else if ($data['type'] == 'editclient') {
            Edit::UpdateClient($sql, $response_clients, $user_id);
        } else if ($data['type'] == 'editcompany') {
            Edit::UpdateCompany($sql, $response_company, $user_id);
        } else if ($data['type'] == 'editsuppliers') {
            Edit::UpdateSupplier($sql, $response_forn, $user_id);
        } else if ($data['type'] == 'editaccountpayable') {
            Edit::UpdateAccountsPayable($sql, $response_account_payable, $user_id);
        } else if ($data['type'] == 'editinventaryquantity') {
            Edit::UpdateInventory($response_inventory, $sql, $user_id);
        } else if ($data['type'] == 'editproducts') {
            Edit::UpdateProduct($response_products, $sql, $user_id);
        }
    } else {
        Response::json(false, 'Tipo type não encontrado', $today);
    }
}

class Edit
{
    public static function UpdateProduct($response_products, $sql, $user_id)
    {
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

        if (!$name || !$quantity || !$value_product || !$cost_value || !$stock_quantity) {
            Response::json(false, 'Campos Inválidos', $today);
        }

        $product_id = filter_var($response_products['id_products'], FILTER_SANITIZE_STRING);
        $product_id = base64_decode($product_id);

        if (!$product_id) {
            Response::json(false, 'ID do produto não informado', $today);
        }

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("UPDATE products 
                                SET name = :name, quantity = :quantity, stock_quantity = :stock_quantity, 
                                    barcode = :barcode, value_product = :value_product, cost_value = :cost_value, 
                                    reference = :reference, model = :model, brand = :brand  
                                WHERE id = :id");

            $exec->bindParam(':name', $name);
            $exec->bindParam(':quantity', $quantity);
            $exec->bindParam(':stock_quantity', $stock_quantity);
            $exec->bindParam(':barcode', $barcode);
            $exec->bindParam(':value_product', $value_product);
            $exec->bindParam(':cost_value', $cost_value);
            $exec->bindParam(':reference', $reference);
            $exec->bindParam(':model', $model);
            $exec->bindParam(':brand', $brand);
            $exec->bindParam(':id', $product_id);
            $exec->execute();

            $sql->commit();

            $message_log = "Produto $name atualizado com sucesso";
            Panel::LogAction($user_id, 'Editar Produto', $message_log, $today);
            Response::send(true, 'Produto atualizado com sucesso', $today);

        } catch (Exception $e) {
            $sql->rollback();
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function UpdateInventory($response_inventory, $sql, $user_id)
    {

        $today = date('Y-m-d H:i:s');
        $quantity_product = filter_var($response_inventory['quantityProduct'], FILTER_SANITIZE_STRING);
        $value_product = filter_var($response_inventory['valueProduct'], FILTER_SANITIZE_STRING);
        $product_id = filter_var($response_inventory['productID'], FILTER_SANITIZE_NUMBER_INT);

        try {

            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("UPDATE products SET stock_quantity = :stock_quantity, 
            value_product = :value_product WHERE id = :product_id");

            $exec->BindParam(':stock_quantity', $quantity_product, PDO::PARAM_STR);
            $exec->BindParam(':value_product', $value_product, PDO::PARAM_STR);
            $exec->BindParam(':product_id', $product_id, PDO::PARAM_STR);
            $exec->execute();

            $sql->commit();

            $message_log = "Inventario realizado com sucesso";
            Panel::LogAction($user_id, 'Inventario', $message_log, $today);
            Response::send(true, 'Inventario realizado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function UpdateAccountsPayable($sql, $response_account_payable, $user_id)
    {

        $today = date('Y-m-d H:i:s');

        $id_account = filter_var($response_account_payable['id_account'], FILTER_SANITIZE_NUMBER_INT);
        $pay = 'paga';
        $name_table = 'financial_control';

        try {

            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            $exec = $sql->prepare("UPDATE $name_table SET 
            pay = :pay, date_settlement = :date_settlement
            WHERE id = :id_account AND `type` = 'contas a pagar'");

            $exec->bindParam(':pay', $pay, PDO::PARAM_STR);
            $exec->bindParam(':date_settlement', $today, PDO::PARAM_STR);
            $exec->bindParam(':id_account', $id_account, PDO::PARAM_INT);
            $exec->execute();

            $message_log = "Contas a pagar faturada com sucesso";
            Panel::LogAction($user_id, 'Contas a pagar faturada', $message_log, $today);
            Response::send(true, 'Contas a pagar faturada com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
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
    public static function UpdateUser($sql, $response_users, $user_id)
    {

        $today = date('Y-m-d H:i:s');

        $id = filter_var(base64_decode($response_users['id_user']), FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($response_users['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_users['email'], FILTER_VALIDATE_EMAIL);
        $login = filter_var($response_users['login'], FILTER_SANITIZE_STRING);
        $phone = filter_var($response_users['phone'], FILTER_SANITIZE_STRING);
        $function = filter_var($response_users['function'], FILTER_SANITIZE_STRING);
        $commission = filter_var($response_users['commission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $target_commission = filter_var($response_users['target_commission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("UPDATE users SET 
            name = :name, 
            email = :email, 
            login = :login, 
            phone = :phone, 
            function = :function, 
            commission = :commission, 
            target_commission = :target_commission
            WHERE id = :id");

            $exec->bindParam(':name', $name);
            $exec->bindParam(':email', $email);
            $exec->bindParam(':login', $login);
            $exec->bindParam(':phone', $phone);
            $exec->bindParam(':function', $function);
            $exec->bindParam(':commission', $commission);
            $exec->bindParam(':target_commission', $target_commission);
            $exec->bindParam(':id', $id, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Usuário $name Editado com sucesso";
            Panel::LogAction($user_id, 'Editar Usuário', $message_log, $today);
            Response::send(true, 'Usuário editado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function UpdateClient($sql, $response_clients, $user_id)
    {
        $today = date("Y-m-d H:i:s");
        $id_client = filter_var(base64_decode($response_clients['id_client']), FILTER_VALIDATE_INT);
        $name = isset($response_clients['name']) ? filter_var($response_clients['name'], FILTER_SANITIZE_STRING) : '';
        $email = isset($response_clients['email']) ? filter_var($response_clients['email'], FILTER_VALIDATE_EMAIL) : '';
        $social_reason = isset($response_clients['social_reason']) ? filter_var($response_clients['social_reason'], FILTER_SANITIZE_STRING) : '';
        $cpf = isset($response_clients['cpf']) ? filter_var($response_clients['cpf'], FILTER_SANITIZE_NUMBER_FLOAT) : '';
        $phone = isset($response_clients['phone']) ? filter_var($response_clients['phone'], FILTER_SANITIZE_STRING) : '';
        $address = isset($response_clients['address']) ? filter_var($response_clients['address'], FILTER_SANITIZE_STRING) : '';
        $city = isset($response_clients['city']) ? filter_var($response_clients['city'], FILTER_SANITIZE_STRING) : '';
        $cep = isset($response_clients['cep']) ? filter_var($response_clients['cep'], FILTER_VALIDATE_INT) : '';
        $neighborhood = isset($response_clients['neighborhood']) ? filter_var($response_clients['neighborhood'], FILTER_SANITIZE_STRING) : '';

        if ($name == "" || $social_reason == "" || $cpf == "") {
            Response::json(false, 'Campos invalidos', $today);
        }

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("UPDATE clients SET 
            name = :name, 
            email = :email, 
            social_reason = :social_reason, 
            cpf = :cpf, 
            phone = :phone, 
            address = :address, 
            city = :city, 
            cep = :cep, 
            neighborhood = :neighborhood
            WHERE id = :id_client");

            $exec->bindParam(':name', $name);
            $exec->bindParam(':email', $email);
            $exec->bindParam(':social_reason', $social_reason);
            $exec->bindParam(':cpf', $cpf);
            $exec->bindParam(':phone', $phone);
            $exec->bindParam(':address', $address);
            $exec->bindParam(':city', $city);
            $exec->bindParam(':cep', $cep);
            $exec->bindParam(':neighborhood', $neighborhood);
            $exec->bindParam(':id_client', $id_client, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Cliente $name editado com sucesso";
            Panel::LogAction($user_id, 'Editar Cliente', $message_log, $today);
            echo json_encode(['success' => true, 'message' => 'Cliente editado com sucesso', 'date' => $today]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function UpdateCompany($sql, $response_company, $user_id)
    {
        $today = date("Y-m-d H:i:s");
        $name_table = 'company';

        $id_company = filter_var(base64_decode($response_company['id_company']), FILTER_VALIDATE_INT);
        $name = filter_var($response_company['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($response_company['email'], FILTER_VALIDATE_EMAIL);
        $cnpj = filter_var($response_company['cnpj'], FILTER_SANITIZE_STRING);
        $state_registration = filter_var($response_company['state_registration'], FILTER_SANITIZE_NUMBER_INT);
        $address = filter_var($response_company['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $city = filter_var($response_company['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_var($response_company['phone'], FILTER_SANITIZE_NUMBER_INT);
        $state = filter_var($response_company['state'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        try {
            $sql->beginTransaction();

            $exec = $sql->prepare("UPDATE $name_table SET 
                name = :name, 
                email = :email, 
                cnpj = :cnpj, 
                state_registration = :state_registration, 
                address = :address, 
                city = :city, 
                phone = :phone, 
                state = :state 
                WHERE id = :id_company");

            $exec->bindParam(':name', $name);
            $exec->bindParam(':email', $email);
            $exec->bindParam(':cnpj', $cnpj);
            $exec->bindParam(':state_registration', $state_registration);
            $exec->bindParam(':address', $address);
            $exec->bindParam(':city', $city);
            $exec->bindParam(':phone', $phone);
            $exec->bindParam(':state', $state);
            $exec->bindParam(':id_company', $id_company, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Empresa $name editada com sucesso";
            Panel::LogAction($user_id, 'Editar Empresa', $message_log, $today);
            Response::send(true, 'Empresa editada com sucesso', $today);


        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function UpdateSupplier($sql, $response_forn, $user_id)
    {
        $today = date('Y-m-d H:i:s');

        $id_forn = filter_var(base64_decode($response_forn['id_forn']), FILTER_VALIDATE_INT);
        $name_company = filter_var($response_forn['name_company'], FILTER_SANITIZE_STRING);
        $fantasy_name = filter_var($response_forn['fantasy_name'], FILTER_SANITIZE_STRING);
        $email = filter_var($response_forn['email'], FILTER_VALIDATE_EMAIL);
        $phone = filter_var($response_forn['phone'], FILTER_SANITIZE_STRING);
        $address = filter_var($response_forn['address'], FILTER_SANITIZE_STRING);
        $city = filter_var($response_forn['city'], FILTER_SANITIZE_STRING);
        $state = filter_var($response_forn['state'], FILTER_SANITIZE_STRING);
        $cnpjcpf = filter_var($response_forn['cnpj'], FILTER_SANITIZE_STRING);

        if (empty($name_company) || empty($fantasy_name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($cnpjcpf)) {
            Response::json(false, 'Todos os campos são obrigatórios', $today);
            return;
        }

        try {
            $sql->beginTransaction();

            $exec = $sql->prepare("UPDATE suppliers SET 
                company = :name_company, 
                fantasy_name = :fantasy_name, 
                email = :email, 
                phone = :phone, 
                address = :address, 
                city = :city, 
                state = :state, 
                cnpjcpf = :cnpjcpf 
                WHERE id = :id_forn");

            $exec->bindParam(':name_company', $name_company);
            $exec->bindParam(':fantasy_name', $fantasy_name);
            $exec->bindParam(':email', $email);
            $exec->bindParam(':phone', $phone);
            $exec->bindParam(':address', $address);
            $exec->bindParam(':city', $city);
            $exec->bindParam(':state', $state);
            $exec->bindParam(':cnpjcpf', $cnpjcpf);
            $exec->bindParam(':id_forn', $id_forn, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Fornecedor $name_company editado com sucesso";
            Panel::LogAction($user_id, 'Editar Fornecedor', $message_log, $today);
            Response::send(true, 'Fornecedor editado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }

}

?>