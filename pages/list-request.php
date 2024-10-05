<?php
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$page_permission = 'list-request';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$userFilter = isset($_POST['userFilter']) ? intval($_POST['userFilter']) : $user_id;
$table_filter = isset($_POST['table_filter']) ? intval($_POST['table_filter']) : null;

$date_end = isset($_POST['endDate']) ? $_POST['endDate'] : null;
$date_start = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$date_start1 = !empty($date_start) ? date('Y-m-d', strtotime($date_start)) : null;
$date_end1 = !empty($date_end) ? date('Y-m-d', strtotime($date_end)) : null;

$request = Controllers::SelectRequest('request', $table_filter, $userFilter, $date_start1, $date_end1);

?>

<div class="box-content left w100">
    <div class="card bg-dark text-white">
        <div class="card-header bg-secondary text-white">
            <h2>Filtros</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Filtro por Usuário -->
                <div class="col-md-4">
                    <form method="post">
                        <div class="form-group mb-3">
                            <label class="form-label">Usuário</label>
                            <select name="userFilter" id="userFilter"
                                class="form-select bg-dark text-white border-secondary">
                                <?php
                                $users = Controllers::SelectAll('users');
                                foreach ($users as $user) {
                                    echo '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button class="btn btn-secondary w-100" type="submit">Filtrar</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="post">
                        <div class="form-group mb-3">
                            <label class="form-label">Comandas</label>
                            <select name="table_filter" id="table_filter"
                                class="form-select bg-dark text-white border-secondary">
                                <?php
                                $status = 1;
                                $table_requests = Controllers::SelectAllWhere('table_requests', 'status_table = :status_table', [':status_table' => $status]);
                                foreach ($table_requests as $table_request) {
                                    echo '<option value="' . $table_request['id'] . '">' . $table_request['number'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button class="btn btn-secondary w-100" type="submit">Filtrar</button>
                    </form>
                </div>

                <!-- Filtro por Data -->
                <div class="col-md-4">
                    <form method="post">
                        <div class="form-group mb-3 row">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Data Início</label>
                                <input type="date" name="startDate" id="startDate"
                                    class="form-control bg-dark text-white border-secondary">
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">Data Final</label>
                                <input type="date" name="endDate" id="endDate"
                                    class="form-control bg-dark text-white border-secondary">
                            </div>
                        </div>
                        <button class="btn btn-secondary w-100" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <h2 class="text-white mb-4">Lista de pedidos</h2>
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-dark table-hover">
                    <thead style="white-space: nowrap;">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mesa</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total</th>
                            <th scope="col">Data</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <?php

                    foreach ($request as $key => $value) { ?>

                        <tbody style="white-space: nowrap;">
                            <tr class="<?php echo $value['STATUS_REQUEST'] == 'INATIVADA' ? 'table-danger' : ''; ?>">
                                <th>
                                    <?php echo htmlspecialchars($value["id"]); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars($value["id_table"]); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars(
                                        $value["STATUS_REQUEST"]
                                    ); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars(
                                        $value["total_request"]
                                    ); ?>
                                </th>
                                <th>
                                    <?php $date = new DateTime($value["date_request"]);
                                    echo htmlspecialchars(
                                        $date->format('d/m/Y')
                                    ); ?>
                                </th>

                                <th class="gap-2">
                                    <?php if ($value["STATUS_REQUEST"] == "INATIVADA") {

                                        ?>
                                        <button class="btn btn-secondary">Inativado</button>
                                    <?php } else { ?>
                                        <button onclick="InativarInvo(this)" type="button" data-id="<?php echo $value['id']; ?>"
                                            class="btn btn-light accessnivel"> Inativar P
                                        </button>
                                    <?php } ?>
                                    <button onclick="DetailsOrder(this)" class="btn btn-info"
                                        data-id="<?php echo $value['id']; ?>" type="button">Mais detalhes
                                    </button>
                                </th>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-print-request">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" i>Venda <span id="requestID"></span></h5>
                <button type="button" class="btn-close" onclick="CloseModalInfoRequest()"
                    id="close-details-request"></button>
            </div>
            <div class="modal-title" id="modal-content-details-request">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="modalTable-request" border="1">
                            <thead class="table-dark" style="white-space: nowrap;">
                                <tr>
                                    <th>Comanda</th>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Valor</th>
                                    <th>Usuario</th>
                                    <th>Forma de pagamento</th>
                                    <th>Valor por forma de pag.</th>
                                    <th>Status</th>
                                    <th>total Pedido</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>