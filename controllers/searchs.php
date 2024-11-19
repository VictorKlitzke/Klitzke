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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$search_query = isset($_POST['searchQueryTable']) ? $_POST['searchQueryTable'] : '';
$search_query_request = isset($_POST['search_query_request']) ? $_POST['search_query_request'] : '';
$product_search = isset($_POST['product_search']) ? $_POST['product_search'] : '';
$client_search = isset($_POST['client_search']) ? $_POST['client_search'] : '';

$sql = Db::Connection();

if (!empty($search_query)) {
    Searchs::searchTable($search_query, $sql);
}

if (!empty($search_query_request)) {
    Searchs::searchRequest($search_query_request, $sql);
}

if (!empty($product_search)) {
    Searchs::searchProductSales($product_search, $sql);
}

if (!empty($client_search)) {
    Searchs::searchClientsSales($client_search, $sql);
}

if (isset($_POST['product_list']) && $_POST['product_list'] === 'true') {
    Searchs::searchProduct($sql);
}
if (isset($_POST['client_list']) && $_POST['client_list'] === 'true') {
    Searchs::searchClients($sql);
}
if (isset($_POST['users_list']) && $_POST['users_list'] === 'true') {
    Searchs::searchUsers($sql);
}


class Searchs
{
    public static function searchUsers($sql)
    {
        try {

            $exec = $sql->prepare("SELECT name FROM users");
            $exec->execute();
            $users = $exec->fetchAll();

            if ($users) {
                echo json_encode($users);
            }

        } catch (Exception $e) {
            echo '<p>Erro na execução da consulta: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        }
    }
    public static function searchClients($sql)
    {
        try {

            $exec = $sql->prepare("SELECT name FROM clients");
            $exec->execute();
            $clients = $exec->fetchAll();

            if ($clients) {
                echo json_encode($clients);
            }

        } catch (Exception $e) {
            echo '<p>Erro na execução da consulta: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        }
    }
    public static function searchProduct($sql)
    {
        try {
            $exec = $sql->prepare("SELECT id, name, stock_quantity, value_product FROM products");
            $exec->execute();
            $product = $exec->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($product);

        } catch (Exception $e) {
            echo '<p>Erro na execução da consulta: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        }
    }
    public static function searchClientsSales($client_search, $sql)
    {
        try {
            $exec = $sql->prepare("SELECT id, name FROM clients WHERE id LIKE :client_search OR name LIKE :client_search");
            $likeName = '%' . $client_search . '%';
            $exec->bindParam(':client_search', $likeName, PDO::PARAM_STR);
            $exec->execute();

            if ($exec->rowCount() > 0) {
                echo '<ul class="list-group">';
                while ($client = $exec->fetch(PDO::FETCH_ASSOC)) {
                    echo '<li class="list-group-item bi-cursor" onclick="addClientSales(\'' . htmlspecialchars($client['id'], ENT_QUOTES) . '\', \' ' . htmlspecialchars($client['name'], ENT_QUOTES) . '\')">';
                    echo '<i class="bi bi-cursor" style="margin-right: 8px;"></i> ' . htmlspecialchars($client['id'], ENT_QUOTES) . ' - ' . htmlspecialchars($client['name'], ENT_QUOTES);
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Nenhum cliente encontrado.</p>';
            }
        } catch (Exception $e) {
            echo '<p>Erro na execução da consulta: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        }
    }
    public static function searchProductSales($product_search, $sql)
    {
        try {
            $exec = $sql->prepare("SELECT name, id, value_product FROM products WHERE name LIKE :product_search OR id LIKE :product_search");
            $likeName = '%' . $product_search . '%';
            $exec->bindParam(':product_search', $likeName, PDO::PARAM_STR);
            $exec->execute();

            if ($exec->rowCount() > 0) {
                echo '<ul class="list-group">';
                while ($product = $exec->fetch(PDO::FETCH_ASSOC)) {
                    echo '<li class="list-group-item bi-cursor" onclick="addProductToTable(\'' . htmlspecialchars($product['id'], ENT_QUOTES) . '\', \'' . htmlspecialchars($product['name'], ENT_QUOTES) . '\', ' . $product['value_product'] . ')">'
                        . '<i class="bi bi-cursor" style="margin-right: 8px;"></i>'
                        . htmlspecialchars($product['name'], ENT_QUOTES) . ' - R$ ' . number_format($product['value_product'], 2, ',', '.') . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }

        } catch (Exception $e) {
            echo '<p>Erro na execução da consulta: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        }
    }
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