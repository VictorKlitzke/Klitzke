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
    $response_table = $data;
    $response_account = $data;
    $response_company = $data;
    $response_clients = $data;
    $response_products = $data;
    $response_forn = $data;
    $response_boxpdv = $data;
    $response_sangria = $data;
    $response_multiply = $data;
    $response_whatsapp = $data;
    $response_email = $data;
    $response_variation = $data;
    $response_financial_control = $data;
    $response_accounts_payable = $data;

    if (isset($data['type'])) {
        if ($data['type'] == 'users') {
            Register::RegisterUsers($sql, $response_users, $user_id);
        } else if ($data['type'] == 'table_request') {
            Register::RegisterTableRequest($sql, $response_table, $user_id);
        } else if ($data['type'] == 'account') {
            Register::RegisterAccount($sql, $response_account, $user_id);
        } else if ($data['type'] == 'forn') {
            Register::RegisterForn($sql, $response_forn, $user_id);
        } else if ($data['type'] == 'clients') {
            Register::RegisterClient($sql, $response_clients, $user_id);
        } else if ($data['type'] == 'products') {
            Register::RegisterProducts($sql, $response_products, $user_id);
        } else if ($data['type'] == 'company') {
            Register::RegisterCompany($sql, $response_company, $user_id);
        } else if ($data['type'] == 'boxpdv') {
            Register::RegisterBoxPdv($sql, $response_boxpdv, $user_id);
        } else if ($data['type'] == 'sangriapdv') {
            Register::RegisterSangria($sql, $response_sangria, $user_id);
        } else if ($data['type'] == 'multiply') {
            Register::RegisterMultiply($sql, $response_multiply, $user_id);
        } else if ($data['type'] == 'RequestPurchase') {
            Register::SendRequestWhatsApp($sql, $response_whatsapp, $user_id);
        } else if ($data['type'] == 'RequestEmail') {
            Register::SendRequestEmail($sql, $response_email, $user_id);
        } else if ($data['type'] == 'variation') {
            Register::SendAddVariationValues($sql, $response_variation, $user_id);
        } else if ($data['type'] == 'registerAccountsReceivable') {
            Register::WriteAccountsReceivable($sql, $response_financial_control, $user_id, $today);
        } else if ($data['type'] == 'AccountsPayable') {
            Register::RegisterAccountsPayable($sql, $response_accounts_payable, $user_id, $today);
        }
    } else {
        Response::json(false, 'Tipo type não encontrado', $today);
    }
}

class Register
{

    public static function RegisterAccountsPayable($sql, $response_accounts_payable, $user_id, $today)
    {
        $dateTransaction = filter_var($response_accounts_payable['dateTransaction'], FILTER_SANITIZE_STRING);
        $valueTransaction = filter_var($response_accounts_payable['valueTransaction'], FILTER_SANITIZE_STRING);
        $descriptionTransaction = filter_var($response_accounts_payable['descriptionTransaction'], FILTER_SANITIZE_STRING);
        $nameExterno = filter_var($response_accounts_payable['nameExterno'], FILTER_SANITIZE_STRING);
        $numberdoc = filter_var($response_accounts_payable['numberdoc'], FILTER_SANITIZE_STRING);

        if (!$dateTransaction || !$descriptionTransaction || !$valueTransaction) {
            Response::json(false, 'Campo inválido', $today);
            return;
        }

        if (self::UserAccess($sql, $user_id) < 50) {
            Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
            return;
        }

        try {

            $sql->BeginTransaction();

            $name_table = 'financial_control';
            $status_aprazo = 'Despesa';
            $type = 'contas a pagar';

            $exec = $sql->prepare("INSERT INTO $name_table (value, transaction_date, status_aprazo, type, 
                                created_at, name_externo, description, number_doc) 
                            VALUES(:value_aprazo, :dateVenciment, :status_aprazo, :type, NOW(), :nameExterno,
                                :description, :numberdoc)");
            $exec->bindValue(':value_aprazo', $valueTransaction, PDO::PARAM_STR);
            $exec->bindValue(':dateVenciment', $dateTransaction, PDO::PARAM_STR);
            $exec->bindValue(':status_aprazo', $status_aprazo, PDO::PARAM_STR);
            $exec->bindValue(':nameExterno', $nameExterno, PDO::PARAM_STR);
            $exec->bindValue(':description', $descriptionTransaction, PDO::PARAM_STR);
            $exec->bindValue(':type', $type, PDO::PARAM_STR);
            $exec->bindValue(':numberdoc', $numberdoc, PDO::PARAM_STR);
            $exec->execute();

            $sql->commit();

            $message_log = "Baixa no contas a pagar com sucesso";
            Panel::LogAction($user_id, 'Baixa no contas a pagar com sucesso', $message_log, $today);
            Response::send(true, 'Baixa no contas a pagar com sucesso', $today);

        } catch (Exception $e) {
            if ($sql->inTransaction()) {
                $sql->rollBack();
            }
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function WriteAccountsReceivable($sql, $response_financial_control, $user_id, $today)
    {

        try {

            $sql->BeginTransaction();

            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            foreach ($response_financial_control['selectedFinacialControl'] as $sales_prazoID) {
                foreach ($response_financial_control['selectedPagamentalControl'] as $financial_control) {

                    $check_sales_prazoid = $sql->prepare("SELECT COUNT(*) FROM financial_control where sales_aprazo_id = :sales_aprazo_id");
                    $check_sales_prazoid->BindParam(':sales_aprazo_id', $sales_prazoID, PDO::PARAM_INT);
                    $check_sales_prazoid->execute();
                    $count = $check_sales_prazoid->fetchColumn();

                    if ($count > 0) {
                        Response::json(false, 'Essa parcela já foi feita a baixa', $today);
                        return;
                    }

                    $name_table = 'financial_control';
                    $status_aprazo = 'Receita';
                    $type = 'contas a receber';
                    $date_venciment = $financial_control['dateVenciment'];
                    $date = DateTime::createFromFormat('d/m/Y', $date_venciment);
                    $date_venciment_SQL = $date->format('Y-m-d');

                    $exec = $sql->prepare("INSERT INTO $name_table (value, transaction_date, sales_aprazo_id, status_aprazo, type, created_at) 
                                    VALUES(:value_aprazo, :dateVenciment, :selectedFinacialControlID, :status_aprazo, :type, NOW())");
                    $exec->bindValue(':value_aprazo', $financial_control['value_aprazo'], PDO::PARAM_STR);
                    $exec->bindValue(':dateVenciment', $date_venciment_SQL, PDO::PARAM_STR);
                    $exec->bindValue(':selectedFinacialControlID', $sales_prazoID, PDO::PARAM_INT);
                    $exec->bindValue(':status_aprazo', $status_aprazo, PDO::PARAM_STR);
                    $exec->bindValue(':type', $type, PDO::PARAM_STR);
                    $exec->execute();

                    $prazo_status = 'paga';
                    $update_salesprazo = $sql->prepare("UPDATE sales_aprazo SET status = :prazo_status WHERE id = :sales_prazoID");
                    $update_salesprazo->BindParam('prazo_status', $prazo_status, PDO::PARAM_STR);
                    $update_salesprazo->BindParam('sales_prazoID', $sales_prazoID, PDO::PARAM_INT);
                    $update_salesprazo->execute();
                }
            }

            $message_log = "Baixa no contas a receber com sucesso";
            Panel::LogAction($user_id, 'Baixa no contas a receber com sucesso ' . $financial_control['value_aprazo'], $message_log, $today);
            Response::send(true, 'Baixa no contas a receber com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function UserAccess($sql, $user_id)
    {

        try {

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
    public static function SendAddVariationValues($sql, $response_variation, $user_id)
    {

        $today = date("Y-m-d H:i:s");
        $new_values_variation = [];

        try {

            $sql->BeginTransaction();

            foreach ($response_variation['AddVariation'] as $variation) {

                $check_id = $sql->prepare("SELECT COUNT(*) FROM variation_values WHERE id_buy_request = :idBuyRequest");
                $check_id->BindParam('idBuyRequest', $variation['idBuyRequest']);
                $check_id->execute();

                if ($check_id->fetchColumn() == 0) {
                    $exec = $sql->prepare("
                    INSERT INTO variation_values (
                        id_buy_request, 
                        `values`
                    ) VALUES (
                        :id_buy_request, 
                        :values
                    )
                ");

                    $exec->bindParam(':id_buy_request', $variation['idBuyRequest']);
                    $exec->bindParam(':values', $variation['value']);
                    $exec->execute();
                }
            }

            $new_values_variation[] = [
                'idBuyRequest' => $variation['idBuyRequest'],
                'value' => $variation['value']
            ];

            $sql->commit();

            // $message_log = "Variação de valores cadastrada com sucesso";
            // Panel::LogAction($user_id, 'Variação de valores cadastrada com sucesso', $message_log, $today);
            echo json_encode(['success' => true, 'new_values_variation' => $new_values_variation]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function SendRequestWhatsApp($sql, $response_whatsapp, $user_id)
    {

        $today = date("Y-m-d H:i:s");
        $status = 2;
        $message = 'Enviar Solicitação via whatsapp!';

        try {

            foreach ($response_whatsapp['selectedForn'] as $forn_id) {
                foreach ($response_whatsapp['SendSelectedProduct'] as $product) {

                    $exec = $sql->prepare("
                            INSERT INTO request_buy_product (
                                forn_id, 
                                product_id, 
                                quantity, 
                                date_request, 
                                status, 
                                message
                            ) VALUES (
                                :forn_id, 
                                :product_id, 
                                :quantity, 
                                :date_request, 
                                :status, 
                                :message
                            )
                        ");

                    $exec->bindParam(':forn_id', $forn_id);
                    $exec->bindParam(':product_id', $product['id']);
                    $exec->bindParam(':quantity', $product['quantity']);
                    $exec->bindParam(':date_request', $today);
                    $exec->bindParam(':status', $status);
                    $exec->bindParam(':message', $message);
                    $exec->execute();

                }
            }

            $message_log = "Solicitação cadastrada via Whatsapp";
            Panel::LogAction($user_id, 'Solicitação cadastrada via Whatsapp', $message_log, $today);
            Response::send(true, 'Solicitação cadastrada via Whatsapp', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function SendRequestEmail($sql, $response_email, $user_id)
    {
        $today = date('Y-m-d H:i:s');
        $status = 3;
        $message = 'Enviar solicitação via email';

        try {

            foreach ($response_email['selectedForn'] as $forn_id) {
                foreach ($response_email['SendSelectedProduct'] as $product) {

                    $exec = $sql->prepare("
                        INSERT INTO request_buy_product (
                            forn_id, 
                            product_id, 
                            quantity, 
                            date_request, 
                            status, 
                            message
                        ) VALUES (
                            :forn_id, 
                            :product_id, 
                            :quantity, 
                            :date_request, 
                            :status, 
                            :message
                        )
                    ");

                    $exec->bindParam(':forn_id', $forn_id);
                    $exec->bindParam(':product_id', $product['id']);
                    $exec->bindParam(':quantity', $product['quantity']);
                    $exec->bindParam(':date_request', $today);
                    $exec->bindParam(':status', $status);
                    $exec->bindParam(':message', $message);
                    $exec->execute();

                }
            }

            $message_log = "Solicitação cadastrada via e-mail";
            Panel::LogAction($user_id, 'Solicitação cadastrada via e-mail', $message_log, $today);
            Response::send(true, 'Solicitação cadastrada via e-mail', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
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
        $type_users = filter_var($response_users['typeUsers'], FILTER_SANITIZE_NUMBER_INT);

        $menu_register_user = filter_var($response_users['registerusers'], FILTER_SANITIZE_STRING);
        $menu_register_clients = filter_var($response_users['registerclients'], FILTER_SANITIZE_STRING);
        $menu_register_forn = filter_var($response_users['registerforn'], FILTER_SANITIZE_STRING);

        $menu_sales = filter_var($response_users['sales'], FILTER_SANITIZE_STRING);
        $menu_list_sales = filter_var($response_users['listSales'], FILTER_SANITIZE_STRING);

        $menu_orders = filter_var($response_users['orders'], FILTER_SANITIZE_STRING);
        $menu_list_orders = filter_var($response_users['listOrders'], FILTER_SANITIZE_STRING);
        $menu_register_tables = filter_var($response_users['registerTables'], FILTER_SANITIZE_STRING);

        $menu_register_boxpdv = filter_var($response_users['registerBoxPdv'], FILTER_SANITIZE_STRING);
        $menu_list_boxpdv = filter_var($response_users['listBoxPdv'], FILTER_SANITIZE_STRING);
        // $menu_reports_boxpdv = filter_var($response_users['reportsBoxPdv'], FILTER_SANITIZE_STRING);

        $menu_request_purchase = filter_var($response_users['requestPurchase'], FILTER_SANITIZE_STRING);
        $menu_list_request_purchase = filter_var($response_users['listrequestPurchase'], FILTER_SANITIZE_STRING);

        $menu_list_products = filter_var($response_users['listProducts'], FILTER_SANITIZE_STRING);
        $menu_register_products = filter_var($response_users['registerProducts'], FILTER_SANITIZE_STRING);
        $menu_register_Inventory = filter_var($response_users['registerInventory'], FILTER_SANITIZE_STRING);

        $menu_dashboard = filter_var($response_users['listProducts'], FILTER_SANITIZE_STRING);

        $menu_my_company = filter_var($response_users['registerProducts'], FILTER_SANITIZE_STRING);

        $menu_financial_control = filter_var($response_users['financialControl'], FILTER_SANITIZE_STRING);

        $menu_access = [
            'list-users' => ($menu_register_user === 'sim') ? 1 : 0,
            'register-users' => ($menu_register_user === 'sim') ? 1 : 0,
            'edit-users' => ($menu_register_user === 'sim') ? 1 : 0,

            'list-clients' => ($menu_register_clients === 'sim') ? 1 : 0,
            'register-clients' => ($menu_register_clients === 'sim') ? 1 : 0,
            'edit-clients' => ($menu_register_clients === 'sim') ? 1 : 0,

            'list-suppliers' => ($menu_register_forn === 'sim') ? 1 : 0,
            'register-suppliers' => ($menu_register_forn === 'sim') ? 1 : 0,
            'edit-suppliers' => ($menu_register_forn === 'sim') ? 1 : 0,

            'register-sales' => ($menu_sales === 'sim') ? 1 : 0,
            'list-sales' => ($menu_list_sales === 'sim') ? 1 : 0,

            'register-request' => ($menu_orders === 'sim') ? 1 : 0,
            'list-request' => ($menu_list_orders === 'sim') ? 1 : 0,
            'register-table' => ($menu_register_tables === 'sim') ? 1 : 0,

            'register-boxpdv' => ($menu_register_boxpdv === 'sim') ? 1 : 0,
            'list-boxpdv' => ($menu_list_boxpdv === 'sim') ? 1 : 0,
            // 'register-table' => ($menu_reports_boxpdv === 'sim') ? 1 : 0,

            'shopping-request' => ($menu_request_purchase === 'sim') ? 1 : 0,
            'list-purchase-request' => ($menu_list_request_purchase === 'sim') ? 1 : 0,

            'list-products' => ($menu_list_products === 'sim') ? 1 : 0,
            'register-stockcontrol' => ($menu_register_products === 'sim') ? 1 : 0,
            'stock-inventory' => ($menu_register_Inventory === 'sim') ? 1 : 0,

            'dashboard' => ($menu_dashboard === 'sim') ? 1 : 0,

            'list-companys' => ($menu_my_company === 'sim') ? 1 : 0,

            'financial-control' => ($menu_financial_control === 'sim') ? 1 : 0,
        ];

        if (!$access || !$name || !$password || !$function || !$phone) {
            Response::json(false, 'Campos invalidos', $today);
        }

        try {

            $sql->BeginTransaction();

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $exec = $sql->prepare("INSERT INTO users (name, email, password, login, phone, function, 
                                commission, target_commission, access, disable, type_users)
                                VALUES (:name, :email, :password, :login, :phone, :function, :commission, :target_commission, :access, :disable, :type_users)");
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
            $exec->bindValue(':type_users', $type_users, PDO::PARAM_STR);
            $exec->execute();

            $user_id_menu_access = $sql->lastInsertId();

            foreach ($menu_access as $menu => $released) {
                if ($released == 1) {
                    $exec_menu = $sql->prepare("INSERT INTO menu_access (user_id, menu, creation_date, released) 
                                                VALUES (:user_id, :menu, NOW(), :released)");

                    $exec_menu->bindParam(':user_id', $user_id_menu_access, PDO::PARAM_INT);
                    $exec_menu->bindParam(':menu', $menu, PDO::PARAM_STR);
                    $exec_menu->bindParam(':released', $released, PDO::PARAM_INT);

                    $exec_menu->execute();
                }
            }

            $sql->commit();

            $message_log = "Usuário $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Usuário', $message_log, $today);
            Response::send(true, 'Usuário cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function RegisterClient($sql, $response_clients, $user_id)
    {

        $today = date("Y-m-d H:i:s");

        $name_table = 'clients';
        $disable = 1;

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

            $check = $sql->prepare("SELECT COUNT(*) FROM $name_table WHERE cpf = :cpf");
            $check->bindValue(':cpf', $cpf, PDO::PARAM_STR);
            $check->execute();
            $exists = $check->fetchColumn();

            if ($exists > 0) {
                Response::json(false, 'CPF já cadastrado no banco de dados', $today);
                return;
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("
            INSERT INTO $name_table 
            (name, email, social_reason, cpf, phone, address, city, cep, neighborhood, disable) 
            VALUES 
            (:name, :email, :social_reason, :cpf, :phone, :address, :city, :cep, :neighborhood, :disable)
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
            $exec->bindValue(':disable', $disable, PDO::PARAM_STR);
            $exec->execute();

            $sql->commit();

            $message_log = "Cliente $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Cliente', $message_log, $today);
            Response::send(true, 'Cliente cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function RegisterCompany($sql, $response_company, $user_id)
    {

        $today = date("Y-m-d H:i:s");
        $name_table = 'company';

        $name = filter_var($response_company['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($response_company['email'], FILTER_VALIDATE_EMAIL);
        $cnpj = filter_var($response_company['cnpj'], FILTER_SANITIZE_STRING);
        $state_registration = filter_var($response_company['state_registration'], FILTER_SANITIZE_NUMBER_INT);
        $address = filter_var($response_company['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $city = filter_var($response_company['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_var($response_company['phone'], FILTER_SANITIZE_NUMBER_INT);
        $state = filter_var($response_company['state'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("
            INSERT INTO $name_table 
            (name, email, cnpj, state_registration, address, city, phone, state) 
            VALUES 
            (:name, :email, :cnpj, :state_registration, :address, :city, :phone, :state)
        ");
            $exec->bindValue(':name', $name, PDO::PARAM_STR);
            $exec->bindValue(':email', $email, PDO::PARAM_STR);
            $exec->bindValue(':cnpj', $cnpj, PDO::PARAM_STR);
            $exec->bindValue(':state_registration', $state_registration, PDO::PARAM_STR);
            $exec->bindValue(':address', $address, PDO::PARAM_STR);
            $exec->bindValue(':city', $city, PDO::PARAM_STR);
            $exec->bindValue(':phone', $phone, PDO::PARAM_STR);
            $exec->bindValue(':state', $state, PDO::PARAM_STR);
            $exec->execute();

            $sql->commit();

            $message_log = "Empresa $name cadastrada com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Empresa', $message_log, $today);
            Response::send(true, 'Empresa cadastrada com sucesso', $today);


        } catch (Exception $e) {
            $sql->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function RegisterTableRequest($sql, $response_table, $user_id)
    {

        $status = 0;
        $today = date('Y-m-d H:i:s');
        $number = filter_var($response_table['name'], FILTER_SANITIZE_STRING);
        $name_table = "table_requests";

        if (!$number) {
            Response::json(false, 'Campo invalido', $today);
        }

        try {

            $check = $sql->prepare("SELECT COUNT(*) FROM $name_table WHERE number = :number");
            $check->bindValue(':number', $number, PDO::PARAM_STR);
            $check->execute();
            $exists = $check->fetchColumn();

            if ($exists > 0) {
                Response::json(false, 'Número da mesa já cadastrado', $today);
                return;
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO $name_table (number, status_table) VALUES (:number, :status_table)");
            $exec->bindValue(':number', $number, PDO::PARAM_STR);
            $exec->bindValue(':status_table', $status, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Mesa $number cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Mesa', $message_log, $today);
            Response::send(true, 'Mesa cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function RegisterAccount($sql, $response_account, $user_id)
    {
        $today = date('Y-m-d H:i:s');
        $id_company = Controllers::Select('company');
        if (!$id_company) {
            Response::json(false, 'Erro ao buscar dados da empresa', $today);
            return;
        }
        $company = $id_company['id'];

        $name_holder = filter_var($response_account['name_holder'], FILTER_SANITIZE_STRING);
        $bank = filter_var($response_account['bank'], FILTER_SANITIZE_STRING);
        $pix = filter_var($response_account['pix'], FILTER_SANITIZE_STRING);
        $agency = filter_var($response_account['agency'], FILTER_SANITIZE_STRING);
        $account_type = filter_var($response_account['typeAccount'], FILTER_SANITIZE_NUMBER_INT);
        $account_number = filter_var($response_account['account_number'], FILTER_SANITIZE_STRING);

        $name_table = "bank_account";

        if (!$name_holder || !$pix || !$bank) {
            Response::json(false, 'Campo invalido', $today);
            return;
        }

        try {

            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            $exec_verification = $sql->prepare("select * from $name_table where pix = :pix");
            $exec_verification->BindParam(':pix', $pix, PDO::PARAM_STR);
            $exec_verification->execute();
            $result_verification = $exec_verification->fetch(PDO::FETCH_ASSOC);

            if ($result_verification['pix']) {
                Response::json(false, 'Esse PIX já está cadastrado!', $today);
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO $name_table (pix, account_name, bank, agency, account_type, account_number, id_company) 
                                VALUES (:pix, :account_name, :bank, :agency, :account_type, :account_number, :id_company)");
            $exec->bindValue(':pix', $pix, PDO::PARAM_STR);
            $exec->bindValue(':account_name', $name_holder, PDO::PARAM_STR);
            $exec->bindValue(':bank', $bank, PDO::PARAM_STR);
            $exec->bindValue(':agency', $agency, PDO::PARAM_STR);
            $exec->bindValue(':account_type', $account_type, PDO::PARAM_STR);
            $exec->bindValue(':account_number', $account_number, PDO::PARAM_STR);
            $exec->bindValue(':id_company', $company, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Conta $pix cadastrada com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Conta', $message_log, $today);
            echo json_encode(['success' => true, 'message' => 'Conta cadastrada com sucesso', 'date' => $today]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function RegisterProducts($sql, $response_products, $user_id)
    {
        $today = date('Y-m-d H:i:s');
        $show_on_page = 0;

        $name = filter_var($response_products['name'], FILTER_SANITIZE_STRING);
        $quantity = filter_var($response_products['quantity'], FILTER_SANITIZE_STRING);
        $stock_quantity = filter_var($response_products['stock_quantity'], FILTER_SANITIZE_STRING);
        $barcode = filter_var($response_products['barcode'], FILTER_SANITIZE_STRING);
        $value_product = filter_var($response_products['value_product'], FILTER_SANITIZE_STRING);
        $cost_value = filter_var($response_products['cost_value'], FILTER_SANITIZE_STRING);
        $reference = filter_var($response_products['reference'], FILTER_SANITIZE_STRING);
        $model = filter_var($response_products['model'], FILTER_SANITIZE_STRING);
        $brand = filter_var($response_products['brand'], FILTER_SANITIZE_STRING);
        $size = filter_var($response_products['size'], FILTER_SANITIZE_NUMBER_INT);

        if (!$name || !$quantity || !$value_product || !$cost_value || !$stock_quantity) {
            Response::json(false, 'Campos Invalidos', $today);
        }

        $flow = $response_products['flow'];

        try {

            $validate = new self;
            if (!$validate->ValidateImg($flow)) {
                Response::json(false, 'Formato da imagem incompativel o esperado é PNG ou JPEG/JPG.', $today);
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO products (name, quantity, stock_quantity, barcode, value_product, cost_value, reference, model, brand, flow, show_on_page, size) 
                            VALUES (:name, :quantity, :stock_quantity, :barcode, :value_product, :cost_value, :reference, :model, :brand, :flow, :show_on_page, :size)");

            $exec->bindParam(':name', $name);
            $exec->bindParam(':quantity', $quantity);
            $exec->bindParam(':stock_quantity', $stock_quantity);
            $exec->bindParam(':barcode', $barcode);
            $exec->bindParam(':value_product', $value_product);
            $exec->bindParam(':cost_value', $cost_value);
            $exec->bindParam(':reference', $reference);
            $exec->bindParam(':model', $model);
            $exec->bindParam(':brand', $brand);
            $exec->bindParam(':flow', $flow);
            $exec->bindParam(':show_on_page', $show_on_page);
            $exec->bindParam(':size', $size);
            $exec->execute();

            $sql->commit();

            $message_log = "Produto $name cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Produto', $message_log, $today);
            Response::send(true, 'Produto cadastrado com sucesso', $today);

        } catch (Exception $e) {
            $sql->rollback();
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    private function ValidateImg($flow)
    {

        $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $imageData = base64_decode($flow);
        $tempFilePath = tempnam(sys_get_temp_dir(), 'temp_image_');
        file_put_contents($tempFilePath, $imageData);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($tempFilePath);
        unlink($tempFilePath);
        return in_array($mime_type, $allowedMimeTypes);
    }
    public static function RegisterForn($sql, $response_forn, $user_id)
    {
        $today = date('Y-m-d H:i:s');
        $name_table = 'suppliers';

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

            $sql->BeginTransaction();

            $check = $sql->prepare("SELECT COUNT(*) FROM $name_table WHERE cnpjcpf = :cnpjcpf");
            $check->bindValue(':cnpjcpf', $cnpj, PDO::PARAM_STR);
            $check->execute();
            $exists = $check->fetchColumn();

            if ($exists > 0) {
                Response::json(false, 'cnpj já cadastrado no banco de dados', $today);
                return;
            }

            $exec = $sql->prepare("INSERT INTO $name_table (company, fantasy_name, email, phone, address, city, state, cnpjcpf) 
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
            Panel::LogAction($user_id, 'Cadastrar Fornecedor', $message_log, $today);
            Response::send(true, 'Fornecedor cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function RegisterBoxPdv($sql, $response_boxpdv, $user_id)
    {

        $today = date('Y-m-d H:i:s');
        $status = 1;

        $value = filter_var($response_boxpdv['value'], FILTER_SANITIZE_STRING);
        $observation = filter_var($response_boxpdv['observation'], FILTER_SANITIZE_STRING);

        if (!$value || !$observation) {
            Response::json(false, 'Campos estão inválidos', $today);
        }

        try {

            $exec = $sql->prepare("SELECT COUNT(*) FROM boxpdv WHERE id_users = :user_id AND status = :status");
            $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->execute();
            $exist = $exec->fetchColumn();

            if ($exist > 0) {
                Response::json(false, 'Já existe caixa aberto com esse usuário', $today);
            }

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO boxpdv (id_users, value, observation, status, open_date) 
                                   VALUES (:user_id, :value, :observation, :status, :open_date)");
            $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $exec->bindParam(':value', $value);
            $exec->bindParam(':observation', $observation);
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->bindParam(':open_date', $today);
            $exec->execute();
            $sql->commit();

            $_SESSION['value'] = $value;
            $_SESSION['open_date'] = $today;

            $message_log = "Caixa $value aberto com sucesso";
            Panel::LogAction($user_id, 'Abertura de caixa', $message_log, $today);
            Response::send(true, 'Caixa aberto com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function RegisterSangria($sql, $response_sangria, $user_id)
    {

        $today = date('Y-m-d H:i:s');
        $status = 1;

        $value = str_replace('R$', '', $response_sangria['value']);
        $value = floatval($value);
        $observation = filter_var($response_sangria['observation'], FILTER_SANITIZE_STRING);

        if ($value == "" || $observation == "") {
            Response::json(false, 'Campos estão inválidos', $today);
        }

        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("SELECT id, value FROM boxpdv WHERE status = :status");
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->execute();
            $id_boxpdv = $exec->fetch(PDO::FETCH_ASSOC);

            if (!$id_boxpdv['id']) {
                Response::json(false, 'Nenhuma caixa aberta encontrada', $today);
            }

            if ($value > $id_boxpdv['value']) {
                Response::json(false, 'Valor da retirada não pode ser mair que valor do caixa', $today);
            }

            $exec = $sql->prepare("INSERT INTO sangria_boxpdv (id_users, id_boxpdv, value, observation, withdrawa_date) 
                                   VALUES (:user_id, :id_boxpdv, :value, :observation, :withdrawa_date)");
            $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $exec->bindParam(':id_boxpdv', $id_boxpdv['id'], PDO::PARAM_INT);
            $exec->bindParam(':value', $value);
            $exec->bindParam(':observation', $observation);
            $exec->bindParam(':withdrawa_date', $today);
            $exec->execute();
            $sql->commit();

            $message_log = "Retirada realizada com sucesso no valor de $value";
            Panel::LogAction($user_id, 'Retirada do Caixa', $message_log, $today);
            Response::send(true, 'Retirada realizada com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
    public static function RegisterMultiply($sql, $response_multiply, $user_id)
    {

        $today = date('Y-m-d H:i:s');
        $status = 1;
        $multiply = filter_var($response_multiply['multiply'], FILTER_SANITIZE_STRING);
        $name_table = "config_multiply_product";

        if (!$multiply) {
            Response::json(false, 'Campo invalido', $today);
        }
        try {

            $sql->BeginTransaction();

            $exec = $sql->prepare("INSERT INTO $name_table (multiply, status) VALUES(:multiply, :status)");
            $exec->bindValue(':multiply', $multiply, PDO::PARAM_STR);
            $exec->bindValue(':status', $status, PDO::PARAM_INT);
            $exec->execute();

            $sql->commit();

            $message_log = "Esse produto vai ser multiplicado por $multiply cadastrado com sucesso";
            Panel::LogAction($user_id, 'Cadastrar Multiplicador', $message_log, $today);
            Response::send(true, 'Multiplicador cadastrado com sucesso', $today);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }

    }
}

?>