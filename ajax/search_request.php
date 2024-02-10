<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = Db::Connection();

$search_query = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';

$exec = $sql->prepare("SELECT id, name, stock_quantity FROM products WHERE name LIKE :search_query OR stock_quantity LIKE :search_query OR id LIKE :search_query");
$exec->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
$exec->execute();

if ($exec->rowCount() > 0) {
    while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
        $id = isset($row['id']) ? $row['id'] : '';
        $name = isset($row['name']) ? $row['name'] : '';
        $stock_quantity = isset($row['stock_quantity']) ? $row['stock_quantity'] : '';

        echo '<li data-id="' . $id . '" data-name="' . $name . '" data-stock_quantity="' . $stock_quantity . '">' . $name . '</li>';
    }
} else {
    echo '<li>Nenhum resultado encontrado</li>';
}


?>