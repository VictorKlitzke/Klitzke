<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$search_query = isset($_POST['searchQueryTable']) ? $_POST['searchQueryTable'] : '';
$search_query_request = isset($_POST['search_query_request']) ? $_POST['search_query_request'] : '';

$sql = Db::Connection();
if (isset($search_query_request)) {
    Searchs::searchRequest($search_query_request, $sql);
} 
if (isset($search_query)) {
    Searchs::searchTable($search_query, $sql);
}
class Searchs
{

    public static function searchTable($search_query, $sql)
    {
        try {
            $exec = $sql->prepare("SELECT id, number FROM table_requests WHERE number LIKE :search_query");
            $exec->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
            $exec->execute();

            if ($exec->rowCount() > 0) {
                while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
                    $number = htmlspecialchars($row['number']);
                    echo '<li data-number="' . $number . '">' . $number . '</li>';
                }
            } else {
                echo '<li>Nenhum resultado encontrado</li>';
            }
        } catch (PDOException $e) {
            echo '<li>Erro na execução da consulta: ' . $e->getMessage() . '</li>';
        }
    }

    public static function searchRequest($search_query_request, $sql)
    {

        try {
            $exec = $sql->prepare("SELECT id, name, stock_quantity, value_product FROM products 
                                   WHERE name LIKE :search_query_request 
                                   OR stock_quantity LIKE :search_query_request 
                                   OR id LIKE :search_query_request 
                                   OR value_product LIKE :search_query_request");
            $exec->bindValue(':search_query_request', '%' . $search_query_request . '%', PDO::PARAM_STR);
            $exec->execute();

            if ($exec->rowCount() > 0) {
                while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
                    $id = htmlspecialchars($row['id']);
                    $name = htmlspecialchars($row['name']);
                    $stock_quantity = htmlspecialchars($row['stock_quantity']);
                    $value_product = htmlspecialchars($row['value_product']);

                    echo '<li data-id="' . $id . '" data-name="' . $name . '" data-stock_quantity="' . $stock_quantity . '" data-value_product="' . $value_product . '">' . $name . '</li>';
                }
            } else {
                echo '<li>Nenhum resultado encontrado</li>';
            }
        } catch (PDOException $e) {
            echo '<li>Erro na execução da consulta: ' . $e->getMessage() . '</li>';
        }
    }
}

?>