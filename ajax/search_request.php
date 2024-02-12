<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = Db::Connection();

$search_query = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';

$exec = $sql->prepare("SELECT id, name, stock_quantity, value_product FROM products WHERE name LIKE :search_query OR stock_quantity LIKE :search_query OR id LIKE :search_query OR value_product LIKE :search_query");
$exec->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
$exec->execute();

if ($exec->rowCount() > 0) {
    while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
        $id = isset($row['id']) ? $row['id'] : '';
        $name = isset($row['name']) ? $row['name'] : '';
        $stock_quantity = isset($row['stock_quantity']) ? $row['stock_quantity'] : '';
        $value_product = isset($row['value_product']) ? $row['value_product'] : '';

        echo '<li data-id="' . $id . '" data-name="' . $name . '" data-stock_quantity="' . $stock_quantity . '" data-value_product="' . $value_product . '">' . $name . '</li>';
    }
} else {
    echo '<li>Nenhum resultado encontrado</li>';
}


?>