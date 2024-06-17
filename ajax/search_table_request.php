<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

class TableRequest {

    private $sql;

    public function __construct($dbConnection) {
        $this->sql = $dbConnection;
    }

    public function searchTable($search_query) {
        try {
            $exec = $this->sql->prepare("SELECT id, name FROM table_requests WHERE name LIKE :search_query");
            $exec->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
            $exec->execute();

            if ($exec->rowCount() > 0) {
                while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
                    $name = htmlspecialchars($row['name']);
                    echo '<li data-number="' . $name . '">' . $name .  '</li>';
                }
            } else {
                echo '<li>Nenhum resultado encontrado</li>';
            }
        } catch (PDOException $e) {
            echo '<li>Erro na execução da consulta: ' . $e->getMessage() . '</li>';
        }
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$search_query = isset($_POST['searchQueryTable']) ? $_POST['searchQueryTable'] : '';

$sql = Db::Connection();
$tableRequest = new TableRequest($sql);
$tableRequest->searchTable($search_query);

?>
