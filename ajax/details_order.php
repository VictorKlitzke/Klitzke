<?php
include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$sql = Db::Connection();
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_pedido_details']) || empty($data['id_pedido_details'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID do pedido não fornecido.'
    ]);
    exit;
}

$id_pedido_details = $data['id_pedido_details'];

$exec = $sql->prepare("SELECT * FROM request WHERE id = :id_pedido_details");
$exec->bindValue('id_pedido_details', $id_pedido_details, PDO::PARAM_INT);
$exec->execute();
$request = $exec->fetch();

if ($request) {
    detailsPedido($id_pedido_details, $sql);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao abrir modal'
    ]);
}

function detailsPedido($id_pedido_details, $sql)
{
    try {

        $sql->beginTransaction();

        $exec = $sql->prepare("select 
                                r.id Codigo,
                                r.id_table comanda,
                                p.name, 
                                ri.quantity, 
                                ri.price_request,
                                u.`name` users,
                                rp.value_payment pagamento_por_forma,
                                case 
                                when r.status = 3 then 'FATURADO'
                                when r.status = 2 then 'INATIVADA'
                                else 'ERRO'
                            end status_request,
                            r.total_request
                            from 
                                `request_items` ri
                                inner join `products` p on p.id = ri.`id_products`
                                inner join `request` r on r.`id` = ri.`id_request`
                                inner join `request_payments` rp on rp.`id_request` = ri.`id_request` 
                                left join users u on u.id = r.id_users_request
                                inner join form_payment fp on fp.id = rp.id_payment_method
                                WHERE ri.id_request = :id_pedido_details");

        $exec->bindValue(':id_pedido_details', $id_pedido_details, PDO::PARAM_INT);
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