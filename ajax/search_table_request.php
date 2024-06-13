<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$sql = Db::Connection();
$search_query = isset($_POST['searchQueryTable']) ? $_POST['searchQueryTable'] : '';

class TableRequest {

    public static function SearchTable($search_query, $sql) {
        try {
            $exec = $sql->prepare("SELECT id,name FROM table_requests WHERE name LIKE :search_query");
            $exec->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
            $exec->execute();
            $result = $exec->fetch(PDO::FETCH_ASSOC);

            if ($result->rowCount() <= 0) {
                echo '<li>Nenhuma comanda cadastrada</li>';
            }

            if ($exec->rowCount() > 0) {
                while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
                    $name = htmlspecialchars($row['name']);

                    echo '<li " data-number="' . $name . '">' . $name .  '</li>';
                }
            } else {
                echo '<li>Nenhum resultado encontrado</li>';
            }
        } catch (PDOException $e) {
            echo '<li>Erro na execução da consulta: ' . $e->getMessage() . '</li>';
        }
    }

    public static function SearchProductOrder($search_query, $sql) {
        try {

        } catch (Exception $e) {
            throw new error;
        }
    }
}

try {
    $exec = $sql->prepare("SELECT id,name FROM table_requests WHERE name LIKE :search_query");
    $exec->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
    $exec->execute();
    $result = $exec->fetch(PDO::FETCH_ASSOC);

    if ($result->rowCount() <= 0) {
        echo '<li>Nenhuma comanda cadastrada</li>';
    }

    if ($exec->rowCount() > 0) {
        while ($row = $exec->fetch(PDO::FETCH_ASSOC)) {
            $name = htmlspecialchars($row['name']);

            echo '<li " data-number="' . $name . '">' . $name .  '</li>';
        }
    } else {
        echo '<li>Nenhum resultado encontrado</li>';
    }
} catch (PDOException $e) {
    echo '<li>Erro na execução da consulta: ' . $e->getMessage() . '</li>';
}

?>
