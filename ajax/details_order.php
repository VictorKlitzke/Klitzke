<?php
include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

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

function detailsPedido($id_pedido_details, $sql) {
        try {

            $sql->beginTransaction();

            $exec = $sql->prepare("SELECT 
                                    r.id id,
                                    t.name comanda,
                                    p.id, 
                                    p.name, 
                                    ri.quantity, 
                                    ri.price_request,
                                    u.name users,
                                    fp.name form_payment,
                                    rp.value_payment pagamento_por_forma,
                                    case 
                                        when r.status = 3 then 'FATURADO'
                                        when r.status = 2 then 'INATIVADA'
                                        else 'ERRO'
                                    end status_request,
                                    r.total_request
                                FROM products p 
                                    inner join request_items ri on ri.id_products = p.id 
                                    inner join request r on r.id = ri.id_request
                                    inner join users u on u.id = r.id_users_request
                                    inner join table_requests t on  t.id = r.id_table
                                    inner join request_payments rp on rp.id_request = ri.id_request 
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