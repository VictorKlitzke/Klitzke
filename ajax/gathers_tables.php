<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$sql = Db::Connection();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_table_order = isset($_POST['name_table']) ? explode(" ", $_POST['name_table']) : [];

    if (count($id_table_order) >= 2) {
        try {
            $sql->beginTransaction();

            $exec = $sql->prepare("INSERT INTO table_requests (name) VALUES(999)");
            $exec->execute();

            $new_table_order = $sql->lastInsertId();

            $exec = $sql->prepare("UPDATE request SET id_table = :new_table_order WHERE id_table IN (" . implode(',', $id_table_order) . ")");
            $exec->bindParam(':new_table_order', $new_table_order);
            $exec->execute();

            $sql->commit();

            echo "Mesas juntadas com sucesso!";
        } catch (PDOException $e) {
            $pdo->rollback();
            echo "Erro ao juntar as mesas: " . $e->getMessage();
        }
    } else {
        echo "Selecione pelo menos duas mesas para junção.";
    }
}

?>
