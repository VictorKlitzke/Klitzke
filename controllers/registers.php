<?php

// require 'libs/PHPMailer/src/PHPMailer.php';
include_once '../libs/PHPMailer/src/PHPMailer.php';
include_once '../libs/PHPMailer/src/SMTP.php';
include_once '../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $response_add_access_menu = $data;
    $response_inventary = $data;
    $response_intentary_itens = $data;
    $response_reopen_boxpdv = $data;
    $response_portion = $data;
    $response_portion_product = $data;
    $response_invoice = $data;
    $response_cond = $data;

    $condicions = [
        'users' => fn() => Register::RegisterUsers($sql, $response_users, $user_id),
        'table_request' => fn() => Register::RegisterTableRequest($sql, $response_table, $user_id),
        'account' => fn() => Register::RegisterAccount($sql, $response_account, $user_id),
        'forn' => fn() => Register::RegisterForn($sql, $response_forn, $user_id),
        'clients' => fn() => Register::RegisterClient($sql, $response_clients, $user_id),
        'products' => fn() => Register::RegisterProducts($sql, $response_products, $user_id),
        'company' => fn() => Register::RegisterCompany($sql, $response_company, $user_id),
        'boxpdv' => fn() => Register::RegisterBoxPdv($sql, $response_boxpdv, $user_id),
        'sangriapdv' => fn() => Register::RegisterSangria($sql, $response_sangria, $user_id),
        'multiply' => fn() => Register::RegisterMultiply($sql, $response_multiply, $user_id),
        'RequestEmail' => fn() => Register::SendRequestEmail($sql, $response_email, $user_id),
        'variation' => fn() => Register::SendAddVariationValues($sql, $response_variation, $user_id),
        'registerAccountsReceivable' => fn() => Register::WriteAccountsReceivable($sql, $response_financial_control, $user_id, $today),
        'AccountsPayable' => fn() => Register::RegisterAccountsPayable($sql, $response_accounts_payable, $user_id, $today),
        'addaccessmenu' => fn() => Register::AddMenuAccess($response_add_access_menu, $user_id, $sql),
        'createinventary' => fn() => Register::RegisterCreateInventary($response_inventary, $sql, $user_id, $today),
        'createinventaryitens' => fn() => Register::RegisterUpdateInventary($response_intentary_itens, $sql, $user_id, $today),
        'submitReaopenBoxPdv' => fn() => Register::RegisterReopenBox($response_reopen_boxpdv, $sql, $user_id, $today),
        'createportion' => fn() => Register::RegisterPortion($response_portion, $sql, $user_id, $today),
        'createproductsportion' => fn() => Register::RegisterPortionProduct($response_portion_product, $sql, $user_id, $today),
        'invoice' => fn() => Register::RegisterDisplayInvoice($response_invoice, $sql, $user_id, $today),
        'registerconditional' => fn() => Register::RegisterConditional($response_cond, $user_id, $sql, $today),
    ];

    $matched = false;
    foreach ($condicions as $type => $value) {
        if ($data['type'] === $type) {
            $value();
            $matched = true;
            break;
        }
    }

    if (!$matched) {
        Response::json(false, 'Tipo type não encontrado', $today);
    }
}

class Register
{

    public static function RegisterConditional($response_cond, $user_id, $sql, $today) {
        try {
            $sql->beginTransaction();

            $status = 'Em Aberto';
    
            $stmtHeader = $sql->prepare("
                INSERT INTO conditional (creation_date, date_return, total, discount, final_total, note, user_id, client_id, status) 
                VALUES (:date_now, :date_return, :subtotal, :discount, :total, :obs, :user_id, :client_id, :status)
            ");
    
            $stmtHeader->bindValue(':date_now', $response_cond['dateNow']);
            $stmtHeader->bindValue(':date_return', $response_cond['dateReturn']);
            $stmtHeader->bindValue(':subtotal', $response_cond['subTotal']);
            $stmtHeader->bindValue(':discount', $response_cond['discount']);
            $stmtHeader->bindValue(':total', $response_cond['total']);
            $stmtHeader->bindValue(':obs', $response_cond['obs']);
            $stmtHeader->bindValue(':user_id', $response_cond['UserId']);
            $stmtHeader->bindValue(':client_id', $response_cond['ClientId']);
            $stmtHeader->bindValue(':status', $status);
    
            $stmtHeader->execute();

            $purchaseNoteId = $sql->lastInsertId();
    
            $stmtItems = $sql->prepare("
                INSERT INTO conditional_item (conditional_id, product_id, quantity, unit_price, subtotal) 
                VALUES (:conditional_id, :product_id, :quantity, :unit_price, :subtotal)
            ");
    
            foreach ($response_cond['SelectedProducts'] as $product) {
                $stmtItems->bindValue(':conditional_id', $purchaseNoteId);
                $stmtItems->bindValue(':product_id', $product['ProductId']);
                $stmtItems->bindValue(':quantity', $product['ProductQuantity']);
                $stmtItems->bindValue(':unit_price', $product['ProductPrice']);
                $stmtItems->bindValue(':subtotal', $product['ProductQuantity'] * $product['ProductPrice']);
    
                $stmtItems->execute();
            }
    
            $sql->commit();
    
            $message_log = "Nota de compra inserida com sucesso";
            Panel::LogAction($user_id, 'Nota de compra inserida', $message_log, $today);
            Response::send(true, 'Nota de compra inserida com sucesso', $today);
    
        } catch (Exception $e) {
            $sql->rollBack();
            echo 'Erro ao registrar a nota de compra: ' . $e->getMessage();
        }
    }
    
    public static function RegisterDisplayInvoice($response_invoice, $sql, $user_id, $today)
    {
        try {
            $sql->beginTransaction();

            foreach ($response_invoice['products'] as $item) {
                $cod_product = strval($item['cod_product']);

                $query = "SELECT id, stock_quantity FROM products WHERE id = :cod_product";
                $stmt = $sql->prepare($query);
                $stmt->bindParam(':cod_product', $cod_product);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $product_id = $product['id'];
                    $new_quantity = $product['stock_quantity'] + $item['quantity_product'];

                    $query = "
                        UPDATE products
                        SET stock_quantity = :stock_quantity
                        WHERE id = :product_id
                    ";
                    $stmt = $sql->prepare($query);
                    $stmt->bindParam(':stock_quantity', $new_quantity);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->execute();

                    $type = 'Entrada';

                    $query = "
                    INSERT INTO product_movements (product_id, type, value, quantity, date)
                    VALUES (:product_id, :type, :value, :quantity, NOW())
                ";
                    $stmt = $sql->prepare($query);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':type', $type);
                    $stmt->bindParam(':value', $item['value_product']);
                    $stmt->bindParam(':quantity', $item['quantity_product']);
                    $stmt->execute();

                    $sql->commit();

                    $message_log = "Quantidade da Nota de compra atualizada com sucesso";
                    Panel::LogAction($user_id, 'Quantidade da Nota de compra atualizada', $message_log, $today);
                    Response::send(true, 'Quantidade da Nota de compra atualizada com sucesso', $today);

                } else {
                    $value_product = str_replace(',', '.', $item['value_product']);
                    $show_on_page = 0;
                    $invoice = 'Nota Fiscal';
                    $query = "
                    INSERT INTO products (id, name, quantity, stock_quantity, value_product, unit, invoice, show_on_page)
                    VALUES (:id, :name, :quantity, :stock_quantity, :value_product, :unit, :invoice, :show_on_page)
                ";
                    $stmt = $sql->prepare($query);
                    $stmt->bindParam(':id', $cod_product);
                    $stmt->bindParam(':name', $item['name_product']);
                    $stmt->bindParam(':quantity', $item['quantity_product']);
                    $stmt->bindParam(':stock_quantity', $item['quantity_product']);
                    $stmt->bindParam(':value_product', $value_product);
                    $stmt->bindParam(':unit', $item['unit_product']);
                    $stmt->bindParam(':invoice', $invoice);
                    $stmt->bindParam(':show_on_page', $show_on_page);
                    $stmt->execute();

                    $product_id = $sql->lastInsertId();

                    $type = 'Entrada';
                    $query = "
                    INSERT INTO product_movements (product_id, type, value, quantity, date)
                    VALUES (:product_id, :type, :value, :quantity, NOW())
                ";
                    $stmt = $sql->prepare($query);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':type', $type);
                    $stmt->bindParam(':value', $item['value_product']);
                    $stmt->bindParam(':quantity', $item['quantity_product']);
                    $stmt->execute();
                }
            }

            $sql->commit();

            $message_log = "Nota de compra inserida com sucesso";
            Panel::LogAction($user_id, 'Nota de compra inserida', $message_log, $today);
            Response::send(true, 'Nota de compra inserida com sucesso', $today);

        } catch (Exception $e) {
            $sql->rollBack();
            echo 'Erro ao registrar a nota de compra: ' . $e->getMessage();
        }
    }
    public static function RegisterPortionProduct($response_portion_product, $sql, $user_id, $today)
    {

        $PortionID = filter_var($response_portion_product['PortionID'], FILTER_SANITIZE_NUMBER_INT);
        $type = 'saida';

        if (empty($PortionID)) {
            Response::json(false, 'Porção com id invalido', $today);
            return;
        }

        try {
            $sql->beginTransaction();

            if (isset($response_portion_product['selectedProductPortions']) && is_array($response_portion_product['selectedProductPortions'])) {
                foreach ($response_portion_product['selectedProductPortions'] as $productItens) {
                    $product_id = $productItens['id'];
                    $counted_quantity = $productItens['productQuantity'];

                    $exec = $sql->prepare("INSERT INTO portion_itens (portion_id, product_id, quantity, created_at)
                                            VALUES (:portion_id, :product_id, :counted_quantity, NOW())");

                    $exec->bindParam(':portion_id', $PortionID, PDO::PARAM_INT);
                    $exec->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $exec->bindParam(':counted_quantity', $counted_quantity, PDO::PARAM_STR);
                    $exec->execute();

                    $exec1 = $sql->prepare("INSERT INTO product_movements (product_id, quantity, date, type) 
                                            VALUES(:product_id, :quantity, NOW(), :type)");
                    $exec1->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $exec1->bindParam(':quantity', $counted_quantity, PDO::PARAM_STR);
                    $exec1->bindParam(':type', $type, PDO::PARAM_STR);
                    $exec1->execute();
                }
            } else {
                Response::json(false, 'Nenhum produto selecionado para essa porção.', $today);
                return;
            }

            $sql->commit();

            $message_log = "Produtos da porção criado com sucesso";
            Panel::LogAction($user_id, 'Produtos da porção criado Acesso', $message_log, $today);
            Response::send(true, 'Produtos da porção criado com sucesso', [
                'id' => $PortionID,
                'date' => $today
            ]);

        } catch (Exception $e) {
            $sql->rollBack();
            Response::json(false, 'Erro ao adicionar Inventário: ' . $e->getMessage(), $today);
        }
    }
    public static function RegisterPortion($response_portion, $sql, $user_id, $today)
    {

        $namePortion = filter_var($response_portion['namePortion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $valuePortion = (float) filter_var($response_portion['valuePortion'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $obsportion = filter_var($response_portion['obsportion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantityPortion = filter_var($response_portion['quantityPortion'], FILTER_SANITIZE_NUMBER_INT);

        try {

            // if (self::UserAccess($sql, $user_id) < 50) {
            //     Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
            //     return;
            // }

            $sql->beginTransaction();

            $status = 1;
            $exec = $sql->prepare("INSERT INTO portion (name_portion, obs_portion, created_at, status, value, quantity) 
                        VALUES (:name_portion, :obs_portion, NOW(), :status, :value, :quantity)");
            $exec->bindParam(':name_portion', $namePortion, PDO::PARAM_STR);
            $exec->bindParam(':obs_portion', $obsportion, PDO::PARAM_STR);
            $exec->bindValue(':status', $status, PDO::PARAM_INT);
            $exec->bindValue(':value', $valuePortion, PDO::PARAM_STR);
            $exec->bindValue(':quantity', $quantityPortion, PDO::PARAM_INT);
            $exec->execute();

            $id_portion = $sql->lastInsertId();

            $invoice = 'manual';
            $status_product = 'Em Estoque';
            $show_on_page = 0;
            $exec1 = $sql->prepare("INSERT INTO products (name, value_product, quantity, stock_quantity, status_product, invoice, show_on_page) 
                        VALUES (:name_portion, :value_product, :quantity, :stock_quantity, :status_product, :invoice, :show_on_page)");
            $exec1->bindParam(':name_portion', $namePortion, PDO::PARAM_STR);
            $exec1->bindValue(':value_product', $valuePortion, PDO::PARAM_STR);
            $exec1->bindValue(':quantity', $quantityPortion, PDO::PARAM_STR);
            $exec1->bindValue(':stock_quantity', $quantityPortion, PDO::PARAM_STR);
            $exec1->bindValue(':status_product', $status_product, PDO::PARAM_STR);
            $exec1->bindValue(':invoice', $invoice, PDO::PARAM_STR);
            $exec1->bindValue(':show_on_page', $show_on_page, PDO::PARAM_INT);
            $exec1->execute();
            $id_product = $sql->lastInsertId();

            $type = 'Entrada';
            $exec2 = $sql->prepare("INSERT INTO product_movements (product_id, quantity, value, date, type) 
                                            VALUES(:product_id, :quantity, :value, NOW(), :type)");
            $exec2->bindParam(':product_id', $id_product, PDO::PARAM_INT);
            $exec2->bindParam(':quantity', $quantityPortion, PDO::PARAM_INT);
            $exec2->bindParam(':value', $valuePortion, PDO::PARAM_STR);
            $exec2->bindParam(':type', $type, PDO::PARAM_STR);
            $exec2->execute();

            $sql->commit();

            $message_log = "Porção criada com sucesso";
            Panel::LogAction($user_id, 'Porção criada Acesso', $message_log, $today);
            Response::send(true, 'Porção criada com sucesso', [
                'id' => $id_portion,
                'date' => $today
            ]);

        } catch (Exception $e) {
            $sql->rollBack();
            Response::json(false, 'Erro ao executar transação: ' . $e->getMessage(), $today);
        }
    }
    public static function RegisterReopenBox($response_reopen_boxpdv, $sql, $user_id, $today)
    {

        $reason = filter_var($response_reopen_boxpdv['reason'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $boxId1 = base64_decode($response_reopen_boxpdv['boxId']);
        $boxId = filter_var($boxId1, FILTER_SANITIZE_NUMBER_INT);

        $status = 1;
        $status_closing = 'Reativado';

        if (!$boxId) {
            Response::json(false, 'Não foi encontrado o ID do caixa', $today);
            return;
        }

        try {

            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            $query = $sql->prepare("SELECT * FROM boxpdv WHERE id = :boxId");
            $query->bindParam(':boxId', $boxId, PDO::PARAM_INT);
            $query->execute();
            $result_query = $query->fetch(PDO::FETCH_ASSOC);

            if ($result_query && $result_query['status'] == 1) {
                Response::json(false, 'Caixa não foi fechado', $today);
                return;
            }

            $sql->beginTransaction();

            $exec = $sql->prepare("UPDATE boxpdv SET status = :status WHERE id = :boxId");
            $exec->bindParam(':status', $status, PDO::PARAM_INT);
            $exec->bindParam(':boxId', $boxId, PDO::PARAM_INT);
            $exec->execute();

            $exec1 = $sql->prepare("INSERT INTO boxpdv_reopen (boxpdv_id, reason, created_at) VALUES (:boxpdv_id, :reason, :created_at)");
            $exec1->bindParam(':boxpdv_id', $boxId, PDO::PARAM_INT);
            $exec1->bindParam(':reason', $reason, PDO::PARAM_STR);
            $exec1->bindParam(':created_at', $today, PDO::PARAM_STR);
            $exec1->execute();

            $exec2 = $sql->prepare("UPDATE box_closing SET status = :status WHERE id_boxpdv = :id_boxpdv");
            $exec2->bindParam(':id_boxpdv', $boxId, PDO::PARAM_INT);
            $exec2->bindParam(':status', $status_closing, PDO::PARAM_STR);
            $exec2->execute();

            $sql->commit();

            $message_log = "Caixa reativado com sucesso";
            Panel::LogAction($user_id, 'Caixa reativado', $message_log, $today);
            Response::send(true, 'Caixa reativado com sucesso', $today);

        } catch (PDOException $e) {
            $sql->rollBack();
            Response::json(false, 'Erro ao executar transação: ' . $e->getMessage(), $today);
        }

    }
    public static function RegisterCreateInventary($response_inventary, $sql, $user_id, $today)
    {

        $inventaryDate = filter_var($response_inventary['inventaryDate'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $inventaryStatus = filter_var($response_inventary['inventaryStatus'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $inventaryObs = filter_var($response_inventary['inventaryObs'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        try {

            $sql->BeginTransaction();

            if (!empty($inventaryDate) || !empty($inventaryStatus) || !empty($inventaryObs)) {
                $exec = $sql->prepare("INSERT INTO inventary (date, user_id, status, observation, created_at) 
            VALUES(NOW(), :user_id, :status, :inventaryObs, :created_at)");
                $exec->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $exec->bindValue(':status', $inventaryStatus, PDO::PARAM_STR);
                $exec->bindValue(':inventaryObs', $inventaryObs, PDO::PARAM_STR);
                $exec->bindValue(':created_at', $inventaryDate, PDO::PARAM_STR);
                $exec->execute();
            }

            $id_inventary = $sql->lastInsertId();
            $sql->commit();

            $message_log = "Inventario criado com sucesso";
            Panel::LogAction($user_id, 'Inventario criado Acesso', $message_log, $today);
            Response::send(true, 'Inventario criado com sucesso', [
                'id' => $id_inventary,
                'date' => $today
            ]);

        } catch (Exception $e) {
            $sql->rollBack();
            http_response_code(500);
            Response::json(false, 'Erro ao adicionar Inventario: ' . $e->getMessage(), $today);
        }
    }
    public static function RegisterUpdateInventary($response_intentary_itens, $sql, $user_id, $today)
    {
        $id_inventary = filter_var($response_intentary_itens['id_inventary'], FILTER_SANITIZE_NUMBER_INT);
        $status_itens_invantary = 'Ajustado';
        $status_inventary = 'Concluido';

        try {
            $sql->BeginTransaction();

            if (!empty($response_intentary_itens['SelectedProductsRows']) && is_array($response_intentary_itens['SelectedProductsRows'])) {
                foreach ($response_intentary_itens['SelectedProductsRows'] as $productItens) {
                    $product_id = $productItens['product_id'];
                    $counted_quantity = $productItens['quantity_updated'];
                    $stock_difference = $productItens['stock_difference'];

                    $exec = $sql->prepare("INSERT INTO inventary_items (inventary_id, product_id, counted_quantity, system_quantity, status, created_at)
                                            VALUES (:inventary_id, :product_id, :counted_quantity, :system_quantity, :status1, NOW())");

                    $exec->bindParam(':inventary_id', $id_inventary, PDO::PARAM_INT);
                    $exec->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $exec->bindParam(':counted_quantity', $counted_quantity, PDO::PARAM_STR);
                    $exec->bindParam(':system_quantity', $stock_difference, PDO::PARAM_STR);
                    $exec->bindParam(':status1', $status_itens_invantary, PDO::PARAM_STR);
                    $exec->execute();

                    $new_quantity = $counted_quantity;
                    $update_product = $sql->prepare("UPDATE products SET stock_quantity = :new_quantity WHERE id = :product_id");
                    $update_product->bindParam(':new_quantity', $new_quantity, PDO::PARAM_INT);
                    $update_product->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $update_product->execute();

                    $product_movement_type = 'inventário';
                    $quantity_abs = abs($stock_difference);
                    $exec_mov = $sql->prepare("INSERT INTO product_movements (product_id, type, quantity, date, description, quantity_inventary)
                            VALUES (:product_id, :movement_type, :quantity, NOW(), :description, :quantity_inventary)");

                    $exec_mov->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $exec_mov->bindParam(':movement_type', $product_movement_type, PDO::PARAM_STR);
                    $exec_mov->bindParam(':quantity', $quantity_abs, PDO::PARAM_INT);
                    $description = "Ajuste de estoque realizado no inventário ID: $id_inventary";
                    $exec_mov->bindParam(':description', $description, PDO::PARAM_STR);
                    $exec_mov->bindParam(':quantity_inventary', $counted_quantity, PDO::PARAM_INT);
                    $exec_mov->execute();
                }
            }

            $exec1 = $sql->prepare("UPDATE inventary SET status = :status WHERE id = :id");
            $exec1->bindParam('id', $id_inventary, PDO::PARAM_INT);
            $exec1->bindParam('status', $status_inventary, PDO::PARAM_STR);
            $exec1->execute();

            $sql->commit();

            $message_log = "Itens do inventário criado com sucesso";
            Panel::LogAction($user_id, 'Itens do inventário criado Acesso', $message_log, $today);
            Response::send(true, 'Itens do inventário criado com sucesso', $today);

        } catch (Exception $e) {
            $sql->rollBack();
            http_response_code(500);
            Response::json(false, 'Erro ao adicionar Inventário: ' . $e->getMessage(), $today);
        }
    }
    public static function AddMenuAccess($response_add_access_menu, $user_id, $sql)
    {
        $today = date('Y-m-d H:i:s');

        if (empty($response_add_access_menu['userID'])) {
            Response::json(false, 'userID não encontrado', $today);
            return;
        }

        try {
            $sql->beginTransaction();

            foreach ($response_add_access_menu['menus'] as $menu) {
                $check_menu = $sql->prepare("SELECT COUNT(*) FROM menu_access 
                                        WHERE user_id = :user_id AND menu = :menu");
                $check_menu->bindParam(':user_id', $response_add_access_menu['userID'], PDO::PARAM_INT);
                $check_menu->bindParam(':menu', $menu, PDO::PARAM_STR);
                $check_menu->execute();

                $menu_exists = $check_menu->fetchColumn();

                if ($menu_exists == 0) {
                    $exec_menu = $sql->prepare("INSERT INTO menu_access (user_id, menu, creation_date, released) 
                                            VALUES (:user_id, :menu, NOW(), :released)");
                    $exec_menu->bindParam(':user_id', $response_add_access_menu['userID'], PDO::PARAM_INT);
                    $exec_menu->bindParam(':menu', $menu, PDO::PARAM_STR);
                    $released = 1;
                    $exec_menu->bindParam(':released', $released, PDO::PARAM_INT);
                    $exec_menu->execute();
                } else {
                    Response::json(false, 'Usuário já tem acesso a esse menu', $today);
                    return;
                }
            }

            $sql->commit();

            // Log de sucesso
            $message_log = "Menus adicionados com sucesso ao usuário {$response_add_access_menu['userID']}";
            Panel::LogAction($user_id, 'Adicionar Menu Acesso', $message_log, $today);
            Response::send(true, 'Menus adicionados com sucesso', $today);

        } catch (Exception $e) {
            $sql->rollBack();
            http_response_code(500);
            Response::json(false, 'Erro ao adicionar menus: ' . $e->getMessage(), $today);
        }
    }
    public static function RegisterAccountsPayable($sql, $response_accounts_payable, $user_id, $today)
    {
        $dateTransaction = filter_var($response_accounts_payable['dateTransaction'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $valueTransaction = filter_var($response_accounts_payable['valueTransaction'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descriptionTransaction = filter_var($response_accounts_payable['descriptionTransaction'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nameExterno = filter_var($response_accounts_payable['nameExterno'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $numberdoc = filter_var($response_accounts_payable['numberdoc'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transactionType = filter_var($response_accounts_payable['transactionType'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $status_aprazo = filter_var($response_accounts_payable['incomeExpense'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$dateTransaction || !$descriptionTransaction || !$valueTransaction) {
            Response::json(false, 'Campo inválido', $today);
            return;
        }

        try {
            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            $sql->beginTransaction();

            $name_table = 'financial_control';

            $exec = $sql->prepare("INSERT INTO $name_table (value, transaction_date, status_aprazo, 
                                    created_at, name_externo, description, number_doc, type) 
                                VALUES(:value_aprazo, :dateVenciment, :status_aprazo, NOW(), :nameExterno,
                                    :description, :numberdoc, :transactionType)");
            $exec->bindValue(':value_aprazo', $valueTransaction, PDO::PARAM_STR);
            $exec->bindValue(':dateVenciment', $dateTransaction, PDO::PARAM_STR);
            $exec->bindValue(':status_aprazo', $status_aprazo, PDO::PARAM_STR);
            $exec->bindValue(':nameExterno', $nameExterno, PDO::PARAM_STR);
            $exec->bindValue(':description', $descriptionTransaction, PDO::PARAM_STR);
            $exec->bindValue(':numberdoc', $numberdoc, PDO::PARAM_STR);
            $exec->bindValue(':transactionType', $transactionType, PDO::PARAM_STR);
            $exec->execute();

            $sql->commit();

            $message_log = "Adicionado contas com sucesso";
            Panel::LogAction($user_id, 'Adicionado contas com sucesso', $message_log, $today);
            Response::send(true, 'Adicionado contas com sucesso', $today);

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

            if (self::UserAccess($sql, $user_id) < 50) {
                Response::json(false, 'Usuário não tem permissão para executar essa atividade', $today);
                return;
            }

            $sql->beginTransaction();

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

            $sql->commit();

            $message_log = "Baixa no contas a receber com sucesso";
            Panel::LogAction($user_id, 'Baixa no contas a receber com sucesso ' . $financial_control['value_aprazo'], $message_log, $today);
            Response::send(true, 'Baixa no contas a receber com sucesso', $today);

        } catch (Exception $e) {
            $sql->rollBack();
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
            if ($sql->inTransaction()) {
                $sql->rollBack();
            }
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
    public static function SendRequestEmail($sql, $response_email, $user_id)
    {
        $today = date('Y-m-d H:i:s');
        $status = 3;
        $message = 'Enviar solicitação via email';

        try {
            $companyStmt = $sql->prepare("SELECT email FROM company LIMIT 1");
            $companyStmt->execute();
            $company = $companyStmt->fetch(PDO::FETCH_ASSOC);

            if (!$company || empty($company['email'])) {
                Response::json(false, "E-mail da empresa não encontrado.", $today);
                return;
            }

            $companyEmail = $company['email'];

            foreach ($response_email['selectedForn'] as $forn_id) {

                $stmt = $sql->prepare("SELECT email FROM suppliers WHERE id = :forn_id");
                $stmt->bindParam(':forn_id', $forn_id);
                $stmt->execute();
                $suppliers = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$suppliers || empty($suppliers['email'])) {
                    Response::json(false, 'E-mail do fornecedor não encontrado', $today);
                    continue;
                }

                $suppliers_email = $suppliers['email'];
                $products = $response_email['SendSelectedProduct'];

                $csvFilePath = dirname(__DIR__) . '/temp/solicitacao_' . $forn_id . '_' . time() . '.csv';
                $csvFile = fopen($csvFilePath, 'w');

                foreach ($products as $product) {

                    $productStmt = $sql->prepare("SELECT name FROM products WHERE id = :product_id");
                    $productStmt->bindParam(':product_id', $product['id']);
                    $productStmt->execute();
                    $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

                    if (!$productData) {
                        Response::json(false, "Produto com ID " . $product['id'] . " não encontrado.", $today);
                        continue;
                    }

                    $productName = $productData['name'];

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

                    fputcsv($csvFile, [
                        $product['id'],
                        $productName,
                        $product['quantity'],
                        $today
                    ]);
                }
                fclose($csvFile);
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.titan.email';
                    $mail->Port = 465;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->SMTPAuth = true;
                    $mail->Username = 'victor.klitzke@klitzkesoftware.com.br';
                    $mail->Password = 'klitzke1235500!';

                    $mail->setFrom($companyEmail, 'Nome da Empresa');
                    $mail->addAddress($suppliers_email);

                    $mail->isHTML(false);
                    $mail->Subject = "Solicitação de Produtos - " . $today;
                    $mail->Body = "Prezado fornecedor,\n\nSegue em anexo a lista de produtos solicitados.\n\nAtenciosamente,\nSua Empresa";

                    $mail->addAttachment($csvFilePath, 'solicitacao.csv');

                    $mail->send();
                    unlink($csvFilePath);
                } catch (Exception $e) {
                    Response::json(false, "Erro ao enviar e-mail: {$mail->ErrorInfo}", $today);
                    return;
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

        $name = filter_var($response_users['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($response_users['email'], FILTER_VALIDATE_EMAIL);
        $password = $response_users['password'];
        $login = filter_var($response_users['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_var($response_users['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $function = filter_var($response_users['userFunction'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $commission = filter_var($response_users['commission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $target_commission = filter_var($response_users['targetCommission'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $access = filter_var($response_users['access'], FILTER_SANITIZE_NUMBER_INT);
        $type_users = filter_var($response_users['typeUsers'], FILTER_SANITIZE_NUMBER_INT);

        $menu_register_user = filter_var($response_users['registerusers'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_register_clients = filter_var($response_users['registerclients'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_register_forn = filter_var($response_users['registerforn'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_sales = filter_var($response_users['sales'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_list_sales = filter_var($response_users['listSales'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_orders = filter_var($response_users['orders'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_list_orders = filter_var($response_users['listOrders'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_register_tables = filter_var($response_users['registerTables'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_register_boxpdv = filter_var($response_users['registerBoxPdv'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_list_boxpdv = filter_var($response_users['listBoxPdv'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // $menu_reports_boxpdv = filter_var($response_users['reportsBoxPdv'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_request_purchase = filter_var($response_users['requestPurchase'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_list_request_purchase = filter_var($response_users['listrequestPurchase'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_list_products = filter_var($response_users['listProducts'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_register_products = filter_var($response_users['registerProducts'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_register_Inventory = filter_var($response_users['registerInventory'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $menu_register_portion = filter_var($response_users['registerPortion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_dashboard = filter_var($response_users['dashboardADM'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_my_company = filter_var($response_users['myCompany'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $menu_financial_control = filter_var($response_users['financialControl'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
            'list-inventary' => ($menu_register_Inventory === 'sim') ? 1 : 0,
            'register-portions' => ($menu_register_portion === 'sim') ? 1 : 0,

            'dashboard' => ($menu_dashboard === 'sim') ? 1 : 0,

            'list-companys' => ($menu_my_company === 'sim') ? 1 : 0,
            'register-companys' => ($menu_my_company === 'sim') ? 1 : 0,
            'edit-companys' => ($menu_my_company === 'sim') ? 1 : 0,

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

        $name = isset($response_clients['name']) ? filter_var($response_clients['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $email = isset($response_clients['email']) ? filter_var($response_clients['email'], FILTER_VALIDATE_EMAIL) : '';
        $social_reason = isset($response_clients['social_reason']) ? filter_var($response_clients['social_reason'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $cpf = isset($response_clients['cpf']) ? filter_var($response_clients['cpf'], FILTER_SANITIZE_NUMBER_FLOAT) : '';
        $phone = isset($response_clients['phone']) ? filter_var($response_clients['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $address = isset($response_clients['address']) ? filter_var($response_clients['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $city = isset($response_clients['city']) ? filter_var($response_clients['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $cep = isset($response_clients['cep']) ? filter_var($response_clients['cep'], FILTER_VALIDATE_INT) : '';
        $neighborhood = isset($response_clients['neighborhood']) ? filter_var($response_clients['neighborhood'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

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
        $cnpj = filter_var($response_company['cnpj'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
        $number = filter_var($response_table['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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

        $stsm = $sql->prepare("SELECT id FROM company");
        $stsm->execute();
        $id_company = $stsm->fetchColumn();

        if (empty($id_company)) {
            Response::json(false, 'Erro ao buscar dados da empresa', $today);
            return;
        }

        $company = $id_company;

        $name_holder = filter_var($response_account['name_holder'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bank = filter_var($response_account['bank'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pix = filter_var($response_account['pix'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $agency = filter_var($response_account['agency'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $account_type = filter_var($response_account['typeAccount'], FILTER_SANITIZE_NUMBER_INT);
        $account_number = filter_var($response_account['account_number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $name_table = "bank_account";

        if (!$name_holder || !$pix || !$bank) {
            Response::json(false, 'Campos invalido', $today);
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

            if ($result_verification && $result_verification['pix']) {
                Response::json(false, 'Esse PIX já está cadastrado!', $today);
                return;
            }

            $sql->beginTransaction();

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
        $invoice = 'manual';
        $type_movements = 'Entrada';

        $name = filter_var($response_products['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantity = filter_var($response_products['quantity'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $stock_quantity = filter_var($response_products['stock_quantity'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $barcode = filter_var($response_products['barcode'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $value_product = filter_var($response_products['value_product'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cost_value = filter_var($response_products['cost_value'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reference = filter_var($response_products['reference'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $model = filter_var($response_products['model'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $brand = filter_var($response_products['brand'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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

            $exec = $sql->prepare("INSERT INTO products (name, quantity, stock_quantity, barcode, value_product, 
                                    cost_value, reference, model, brand, flow, show_on_page, size, invoice) 
                            VALUES (:name, :quantity, :stock_quantity, :barcode, :value_product, :cost_value, 
                            :reference, :model, :brand, :flow, :show_on_page, :size, :invoice)");

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
            $exec->bindParam(':invoice', $invoice);
            $exec->execute();

            $lastSaleId = $sql->lastInsertId();


            $exec1 = $sql->prepare("INSERT INTO product_movements (product_id, quantity, type, value, date)
                                    VALUES (:product_id, :quantity, :type, :value, NOW())");
            $exec1->BindParam(':product_id', $lastSaleId);
            $exec1->BindParam(':quantity', $stock_quantity);
            $exec1->BindParam(':type', $type_movements);
            $exec1->BindParam(':value', $value_product);
            $exec1->execute();

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

        $name_company = filter_var($response_forn['name_company'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fantasy_name = filter_var($response_forn['fantasy_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($response_forn['email'], FILTER_VALIDATE_EMAIL);
        $phone = filter_var($response_forn['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $address = filter_var($response_forn['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $city = filter_var($response_forn['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $state = filter_var($response_forn['state'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cnpj = filter_var($response_forn['cnpj'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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

        $value = filter_var($response_boxpdv['value'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $observation = filter_var($response_boxpdv['observation'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
        $observation = filter_var($response_sangria['observation'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
                Response::json(false, 'Nenhuma caixa aberto encontrado', $today);
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

            $type_withdrawal = 'contas a pagar';
            $status_withdrawal = 'Despesa';
            $withdrawal = 1;
            $pay = 'paga';

            $exec1 = $sql->prepare("INSERT INTO financial_control (type, value, transaction_date, status_aprazo, description, withdrawal, pay, date_settlement)
                                    VALUES (:type, :value, :transaction_date, :status_aprazo, :description, :withdrawal, :pay, :date_settlement)");
            $exec1->bindParam(':type', $type_withdrawal, PDO::PARAM_STR);
            $exec1->bindParam(':value', $value, PDO::PARAM_STR);
            $exec1->bindParam(':transaction_date', $today, PDO::PARAM_STR);
            $exec1->bindParam(':status_aprazo', $status_withdrawal, PDO::PARAM_STR);
            $exec1->bindParam(':description', $observation, PDO::PARAM_STR);
            $exec1->bindParam(':withdrawal', $withdrawal, PDO::PARAM_INT);
            $exec1->bindParam(':pay', $pay, PDO::PARAM_STR);
            $exec1->bindParam(':date_settlement', $today, PDO::PARAM_STR);
            $exec1->execute();

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
        $multiply = filter_var($response_multiply['multiply'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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