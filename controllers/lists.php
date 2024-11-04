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

$input = json_decode(file_get_contents('php://input'), true);
$type = isset($input['type']) ? $input['type'] : null;
$today = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type === 'listproduct') {
        Lists::ListProducts($sql);
    } else if ($type === 'listforn') {
        Lists::ListForn($sql);
    } else if ($type === 'listbuyrequest') {
        Lists::ListBuyRequest($sql, $input);
    } else if ($type === 'listvariationvalues') {
        lists::ListVariationValues($sql, $input);
    } else if ($type === 'listFinancialControl') {
        lists::ListFinancialControl($sql, $input);
    } else if ($type === 'listFinancialControlDetals') {
        lists::ListFinancialControlDetals($sql, $input);
    } else if ($type === 'sumcontrolfinancial') {
        lists::SumFinancialControl($sql);
    } else if ($type === 'sumUsersSales') {
        lists::UsersSumSales($sql);
    } else if ($type === 'listinventary') {
        lists::ListInventary($sql);
    } else if ($type === 'inventaryitens') {
        lists::ListInventaryItens($sql, $input);
    } else if ($type === 'sumclosingbox') {
        lists::SumBoxClosing($sql);
    }
}

class lists
{
    public static function SumBoxClosing($sql)
    {
        try {
            $exec = $sql->prepare("SELECT 
                                    bp.`open_date`,
                                    bp.value,
                                    u.name,
                                    bc.* 
                                FROM 
                                    boxpdv bp 
                                    INNER JOIN box_closing bc ON bc.`id_boxpdv` = bp.id
                                    INNER JOIN users u ON u.id = bp.`id_users`");
            $exec->execute();
            $result_box_closing = $exec->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => 'true',
                'result_box_closing' => $result_box_closing
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro no banco de dados: ' . $e->getMessage()
            ]);
        }
    }
    public static function ListInventaryItens($sql, $input)
    {
        $idInventary = isset($input['idInventary']) ? $input['idInventary'] : null;

        try {

            $exec = $sql->prepare("SELECT 
                                    i.id, 
                                    i.status, 
                                    i.observation, 
                                    i.created_at,
                                    p.name product,
                                    ii.`counted_quantity`,
                                    ii.`system_quantity`,
                                    ii.`status`
                                    
                                FROM 
                                    inventary i 
                                    INNER JOIN `inventary_items` ii ON ii.`inventary_id` = i.id
                                    INNER JOIN products p ON p.id = ii.`product_id`
                                WHERE i.id = :id");
            $exec->bindParam(':id', $idInventary, PDO::PARAM_INT);
            $exec->execute();
            $result_itens = $exec->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => 'true',
                'result_itens' => $result_itens
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function ListInventary($sql)
    {
        try {

            $exec = $sql->prepare("SELECT i.id, i.status, i.observation, i.created_at, u.name user FROM inventary i INNER JOIN users u ON u.id = i.user_id");
            $exec->execute();
            $result_inventary = $exec->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'result_inventary' => $result_inventary
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function ListProducts($sql)
    {

        try {

            $exec = $sql->prepare("select id,name,stock_quantity,value_product,status_product from `products` where status_product = 'negativado'");
            $exec->execute();
            $products = $exec->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'products' => $products
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function ListForn($sql)
    {

        try {

            $exec = $sql->prepare("select id,company from `suppliers`");
            $exec->execute();
            $products = $exec->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'forn' => $products
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function ListBuyRequest($sql, $input)
    {
        $searchTerm = isset($input['searchTerm']) ? $input['searchTerm'] : null;

        try {
            $query = "SELECT 
                    rbp.id,
                    p.`name` AS product,
                    su.`company` AS company,
                    rbp.quantity,
                    rbp.`message`,
                    rbp.`date_request`
                  FROM 
                    `request_buy_product` rbp
                    JOIN `suppliers` su ON su.id = rbp.`forn_id`
                    JOIN `products` p ON p.id = rbp.`product_id`";

            if (!empty($searchTerm)) {
                $query .= " WHERE p.`name` LIKE :search_term OR su.`company` LIKE :search_term";
            }

            $exec = $sql->prepare($query);

            if (!empty($searchTerm)) {
                $exec->bindValue(':search_term', '%' . $searchTerm . '%', PDO::PARAM_STR);
            }

            $exec->execute();
            $buyrequest = $exec->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'buyrequest' => $buyrequest,
            ]);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    public static function ListVariationValues($sql, $input)
    {

        $searchTermVariation = isset($input['searchTermVariation']) ? $input['searchTermVariation'] : null;

        try {
            $query = "SELECT 
                            rbp.id,
                            p.name AS product,
                            su.company AS company,
                            rbp.quantity
                          FROM 
                            request_buy_product rbp
                          JOIN 
                            suppliers su ON su.id = rbp.forn_id
                          JOIN 
                            products p ON p.id = rbp.product_id";

            if (!empty($searchTermVariation)) {
                $query .= " WHERE p.name LIKE :search_term OR su.company LIKE :search_term";
            }

            $stmt = $sql->prepare($query);

            if (!empty($searchTermVariation)) {
                $stmt->bindValue(':search_term', '%' . $searchTermVariation . '%', PDO::PARAM_STR);
            }

            $stmt->execute();
            $variationvalues = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'variationvalues' => $variationvalues]);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }
    public static function ListFinancialControl($sql, $input)
    {
        $searchTermFinancialControl = isset($input['searchTermFinancialControl']) ? $input['searchTermFinancialControl'] : null;

        try {
            $salesData = [];
            $financialControlData = [];
            $AllSales = [];
            $EntryData = [];

            $querySales = "SELECT 
                            s.*,
                            c.name AS client,
                            fp.name AS formpagament,
                            (SELECT COUNT(*) FROM `sales_aprazo` sp WHERE sp.`sale_id` = s.`id`) AS portion_aprazo,
                            CASE 
                                WHEN COUNT(CASE WHEN sp.status = 'paga' THEN 1 END) = COUNT(sp.id) THEN 'paga'
                                WHEN COUNT(CASE WHEN sp.status = 'paga' THEN 1 END) > 0 THEN 'em andamento'
                                ELSE 'nenhum pagamento'
                            END AS status_aprazo
                        FROM 
                            `sales` s
                        INNER JOIN `clients` c ON c.`id` = s.`id_client`
                        INNER JOIN `form_payment` fp ON fp.`id` = s.`id_payment_method`
                        LEFT JOIN `sales_aprazo` sp ON sp.`sale_id` = s.`id`
                        WHERE 
                            fp.id = 5 ";

            if ($searchTermFinancialControl) {
                $querySales .= " AND (c.name LIKE :search_term OR fp.name LIKE :search_term)";
            }

            $querySales .= " GROUP BY s.id, c.name, fp.name";

            $stmtSales = $sql->prepare($querySales);

            if ($searchTermFinancialControl) {
                $stmtSales->bindValue(':search_term', '%' . $searchTermFinancialControl . '%', PDO::PARAM_STR);
            }

            $stmtSales->execute();
            $salesData = $stmtSales->fetchAll(PDO::FETCH_ASSOC);

            $queryFinancialControl = "SELECT * FROM `financial_control` WHERE `type` = 'contas a pagar'";
            $stmtFinancial = $sql->prepare($queryFinancialControl);
            $stmtFinancial->execute();
            $financialControlData = $stmtFinancial->fetchAll(PDO::FETCH_ASSOC);

            $queryentry = "SELECT * FROM `financial_control` WHERE `type` = 'contas a receber'";
            $entry = $sql->prepare($queryentry);
            $entry->execute();
            $EntryData = $entry->fetchAll(PDO::FETCH_ASSOC);

            $querySales = "SELECT 
                                clients.name clients,
                                `form_payment`.`name` form_payment,
                                sales.total_value,
                                sales.date_sales,
                                sales.id
                            FROM 
                                `sales` 
                                LEFT JOIN clients on clients.id = sales.`id_client`
                                INNER JOIN `form_payment` on `form_payment`.id = sales.`id_payment_method`
                            WHERE sales.`id_payment_method` in (1,2,3,4) ";
            $stmtSales = $sql->prepare($querySales);
            $stmtSales->execute();
            $AllSales = $stmtSales->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'salesData' => $salesData,
                'financialcontrol' => $financialControlData,
                'AllSales' => $AllSales,
                'EntryData' => $EntryData
            ]);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro no banco de dados: ' . $e->getMessage()
            ]);
        }
    }
    public static function ListFinancialControlDetals($sql, $input)
    {
        $id_detals = isset($input['id_detals']) ? $input['id_detals'] : null;

        try {

            $query = "select 
                sp.*,
                s.`total_value`,
                c.`name` clients
            from 
                `sales_aprazo` sp
                inner join `sales` s on s.`id` = sp.`sale_id`
                inner join `clients` c on c.`id` = s.`id_client`";

            if (isset($id_detals)) {
                $query .= "where sp.sale_id = :id_detals";
            }

            $stmt = $sql->prepare($query);

            if (!empty($id_detals)) {
                $stmt->bindValue(':id_detals', $id_detals, PDO::PARAM_INT);
            }

            $stmt->execute();
            $financialcontroldetals = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'financialcontroldetals' => $financialcontroldetals]);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }

    }
    public static function SumFinancialControl($sql)
    {

        try {
            $query = "select SUM(value) TotalContasPagar from `financial_control` where type = 'contas a pagar'";
            $exec = $sql->prepare($query);
            $exec->execute();
            $result_payable = $exec->fetchAll(PDO::FETCH_ASSOC);

            $query1 = "select SUM(value) TotalContasReceber from `financial_control`";
            $exec1 = $sql->prepare($query1);
            $exec1->execute();
            $result_control = $exec1->fetchAll(PDO::FETCH_ASSOC);

            $query2 = "select SUM(value_aprazo) TotalContasNaoRecebidas from `sales_aprazo` where status = 'pendente'";
            $exec = $sql->prepare($query2);
            $exec->execute();
            $result_aprazo = $exec->fetchAll(PDO::FETCH_ASSOC);

            $query3 = "select SUM(value) TotalSaldo from `financial_control`";
            $exec3 = $sql->prepare($query3);
            $exec3->execute();
            $total_sal = $exec3->fetchAll(PDO::FETCH_ASSOC);

            $query4 = "select fp.*, sp.* from `financial_control` fp left join sales_aprazo sp on sp.id = fp.sales_aprazo_id";
            $exec4 = $sql->prepare($query4);
            $exec4->execute();
            $sumfinancial = $exec4->fetchAll(PDO::FETCH_ASSOC);

            $query5 = "select SUM(total_value) TotalTodasVendas from `sales` WHERE sales.`id_payment_method` in (1,2,3,4)";
            $exec5 = $sql->prepare($query5);
            $exec5->execute();
            $result_salesAll = $exec5->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'result_payable' => $result_payable,
                'result_control' => $result_control,
                'result_aprazo' => $result_aprazo,
                'total_sal' => $total_sal,
                'sumfinancial' => $sumfinancial,
                'result_salesAll' => $result_salesAll
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro no banco de dados: ' . $e->getMessage()
            ]);
        }

    }
    public static function UsersSumSales($sql)
    {
        try {
            $query = "SELECT COUNT(`id_users`) AS total_sales, u.name AS users_name
                    FROM sales s
                    INNER JOIN users u ON u.id = s.id_users
                    GROUP BY u.name";
            $exec = $sql->prepare($query);
            $exec->execute();
            $result_sales = $exec->fetchAll(PDO::FETCH_ASSOC);

            $query1 = "SELECT 
                    DATE_FORMAT(s.`date_sales`, '%Y-%m') AS month, 
                    COUNT(*) AS total_sales,
                    sum(s.`total_value`) total_value
                    FROM sales s
                    GROUP BY DATE_FORMAT(s.date_sales, '%Y-%m')
                    ORDER BY DATE_FORMAT(s.date_sales, '%Y-%m')";
            $exec1 = $sql->prepare($query1);
            $exec1->execute();
            $date_sales = $exec1->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $result_sales,
                'date_sales' => $date_sales
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro no banco de dados: ' . $e->getMessage()
            ]);
        }
    }
}
?>