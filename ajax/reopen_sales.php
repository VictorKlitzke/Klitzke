<?php 

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$id_sales = base64_decode($_POST['id_sales']);

$sql = Db::Connection();
$exec = $sql->prepare("SELECT * FROM sales WHERE id = ?");
$exec->execute([$id_sales]); 
$sales = $exec->fetch();

var_dump(updateSales($id_sales));
var_dump(updateSalesItems($id_sales));

function updateSales($id_sales){
    $sql = Db::Connection();
    $exec = $sql->prepare("UPDATE sales SET status = 1 WHERE id = ?");
    $exec->execute([$id_sales]);

    header('Location: ' . INCLUDE_PATH . 'list-sales');
}

function updateSalesItems($id_sales){
    $sql = Db::Connection();
    $exec = $sql->prepare("UPDATE sales_items SET status_item = 1 WHERE id_sales = ?");
    $exec->execute([$id_sales]);

    header('Location: ' . INCLUDE_PATH . 'list-sales');
}

?>