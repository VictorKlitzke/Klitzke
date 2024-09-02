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
    }
}

class lists
{
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

            echo json_encode([
                'success' => true,
                'salesData' => $salesData,
                'financialcontrol' => $financialControlData
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
}

?>