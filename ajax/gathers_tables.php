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
    $request_data = json_decode(file_get_contents('php://input'), true);

    $selected_tables = $request_data['tableSelected'] ?? [];

    if (count($selected_tables) >= 2) {
        try {
            $sql->beginTransaction();

            $id_users_request = isset($_SESSION['id']) ? $_SESSION['id'] : null;

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
