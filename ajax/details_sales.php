<?php
include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$sql = Db::Connection();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_detals']) || empty($data['id_detals'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID da venda não fornecido.'
    ]);
    exit;
}

$id_detals = $data['id_detals'];

$exec = $sql->prepare("SELECT * FROM sales WHERE id = :id_detals");
$exec->bindValue('id_detals', $id_detals, PDO::PARAM_INT);
$exec->execute();
$request = $exec->fetch();

if ($request) {
    detailsSales($id_detals, $sql);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao abrir modal'
    ]);
}

function detailsSales($id_detals, $sql) {
    try {

        $sql->beginTransaction();

        $exec = $sql->prepare("SELECT 
                                    p.id, 
                                    p.name, 
                                    sl.amount, 
                                    sl.price_sales ,
                                    u.name users,
                                    c.name clients,
                                    fp.name form_payment,
                                    case 
                                        when s.status = 1 then 'VENDIDO'
                                        when s.status = 2 then 'CANCELADA'
                                        else 'ERRO'
                                    end status_sales
                                FROM products p 
                                    inner join sales_items sl on sl.id_product = p.id 
                                    inner join sales s on s.id = sl.id_sales
                                    inner join users u on u.id = s.id_users
                                    inner join form_payment fp on fp.id = s.id_payment_method
                                    left join clients c on c.id = s.id_client
                                WHERE sl.id_sales = :id_detals");

        $exec->bindValue(':id_detals', $id_detals, PDO::PARAM_INT);
        $exec->execute();
        $items = $exec->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'items' => $items
        ]);

        $sql->commit();

    } catch (Exception $e) {
        $sql->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    }
}

?>
