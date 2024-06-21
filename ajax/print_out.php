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

if (!isset($data['id_print_out']) || empty($data['id_print_out'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID da venda não fornecido.'
    ]);
    exit;
}

$id_print_out = $data['id_print_out'];

$exec = $sql->prepare("SELECT * FROM sales WHERE id = :id_print_out");
$exec->bindValue('id_print_out', $id_print_out, PDO::PARAM_INT);
$exec->execute();
$request = $exec->fetch();

if ($request) {
    PrintOut($id_print_out, $sql);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao imprimir venda'
    ]);
}

function PrintOut($id_print_out, $sql) {
    try {

        $sql->beginTransaction();

        $exec = $sql->prepare("SELECT p.id, p.name, sl.amount, sl.price_sales FROM products p inner join sales_items sl on sl.id_product = p.id WHERE sl.id_sales = :id_print_out");
        $exec->bindValue(':id_print_out', $id_print_out, PDO::PARAM_INT);
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
