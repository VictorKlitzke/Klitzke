<?php

include_once '../config/config.php';
include_once '../services/db.php';

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

        $status_gathers = 2;

        // Obter o ID da primeira mesa selecionada para ser a comanda principal
        $principal_command_id = $selected_tables[0]['id'];

        // Iterar sobre as mesas selecionadas para agrupamento
        foreach ($selected_tables as $table) {
            $grouped_command_id = $table['id'];

            // Atualizar a mesa para a nova comanda principal e status de 'agrupada'
            $stmtUpdate = $sql->prepare("UPDATE request SET id_table = :new_table_id AND status = 3 WHERE id_table = :table_id");
            $stmtUpdate->execute(['new_table_id' => $principal_command_id, 'table_id' => $grouped_command_id]);

            // Registrar o agrupamento na tabela request_gathers
            $stmtInsertAgrupamento = $sql->prepare("INSERT INTO request_gathers (principal_command_id, grouped_command_id) VALUES (:principal_id, :gathers_id)");
            $stmtInsertAgrupamento->execute(['principal_id' => $principal_command_id, 'gathers_id' => $grouped_command_id]);
        }

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
