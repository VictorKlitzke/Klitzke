<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$sql = Db::Connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_data = json_decode(file_get_contents('php://input'), true);

    $selected_tables = $request_data['tables'] ?? [];
    $total_gathers_selected = $request_data['valueTotalizadorOrderGathres'] ?? 0;

    if (count($selected_tables) < 2) {
        echo json_encode(['success' => false, 'error' => 'Selecione pelo menos duas mesas para junção.']);
        exit;
    }

    try {
        $sql->beginTransaction();

        $status_request = 4;

        $principal_command_id = $selected_tables[0]['id']; 
        $grouped_command_id = $selected_tables[1]['id']; 

        $stmtCheck = $sql->prepare("SELECT COUNT(*) FROM request_gathers WHERE principal_command_id = :principal_id AND grouped_command_id = :grouped_id");

        foreach ($selected_tables as $table) {

            $stmtCheck->execute(['principal_id' => $principal_command_id, 'grouped_id' => $grouped_command_id]);
            $count = $stmtCheck->fetchColumn();

            if ($count == 0) {
                $stmtInsertAgrupamento = $sql->prepare("INSERT INTO request_gathers (principal_command_id, grouped_command_id, value_total) VALUES (:principal_id, :grouped_id, :value_total)");
                $stmtInsertAgrupamento->execute(['principal_id' => $principal_command_id, 'grouped_id' => $grouped_command_id, 'value_total' => $total_gathers_selected]);
            }

            $stmtUpdate = $sql->prepare("UPDATE request SET id_table = :new_table_id WHERE id_table = :table_id");
            $stmtUpdate->execute(['new_table_id' => $principal_command_id, 'table_id' => $grouped_command_id]);
        }

        $stmtUpdateStatus = $sql->prepare("UPDATE request SET status = :status WHERE id_table = :table_id");
        $stmtUpdateStatus->bindValue(':status', $status_request, PDO::PARAM_INT);
        $stmtUpdateStatus->bindValue(':table_id', $principal_command_id, PDO::PARAM_INT);
        $stmtUpdateStatus->execute();

        $sql->commit();
        echo json_encode(['success' => true, 'message' => 'Comandas agrupadas com sucesso!']);
    } catch (PDOException $e) {
        $sql->rollback();
        echo json_encode(['success' => false, 'error' => 'Erro ao juntar as mesas: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
}

?>