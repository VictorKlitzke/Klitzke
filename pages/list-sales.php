<?php

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$page_permission = 'list-sales';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$userFilter = isset($_POST['userFilter']) ? intval($_POST['userFilter']) : $user_id;
$form_payment = isset($_POST['form_filter']) ? intval($_POST['form_filter']) : null;

$date_end = isset($_POST['endDate']) ? $_POST['endDate'] : null;
$date_start = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$date_start1 = !empty($date_start) ? date('Y-m-d', strtotime($date_start)) : null;
$date_end1 = !empty($date_end) ? date('Y-m-d', strtotime($date_end)) : null;

$sales = Controllers::SelectSales('sales', $userFilter, $form_payment, $date_start1, $date_end1);
?>

<div class="container-fluid bg-light p-4 rounded-4 border shadow-lg">
    <div class="card text-dark">
        <div class="card-header text-dark">
            <h2>Filtros</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Filtro por Usuário -->
                <div class="col-md-4">
                    <form method="post">
                        <div class="form-group mb-3">
                            <label for="userFilter" class="form-label">Usuário</label>
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
                <!-- Filtro por Forma de Pagamento -->
                <div class="col-md-4">
                    <form method="post">
                        <div class="form-group mb-3">
                            <label for="form_filter" class="form-label">Forma de Pagamento</label>
                            <select name="form_filter" id="form_filter"
                                class="form-select bg-dark text-white border-secondary">
                                <?php
                                $payment = Controllers::SelectAll('form_payment');
                                foreach ($payment as $form_payments) {
                                    echo '<option value="' . $form_payments['id'] . '">' . $form_payments['name'] . '</option>';
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
                            <!-- Data Início -->
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Data Início</label>
                                <input type="date" name="startDate" id="startDate"
                                    class="form-control bg-dark text-white border-secondary">
                            </div>
                            <!-- Data Final -->
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
    <h2 class="text-dark mb-4">Lista de Vendas</h2>
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
                    <thead class="table-dark text-light">
                        <tr style="white-space: nowrap;">

                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Forma de Pagamento</th>
                            <th scope="col">Status Venda</th>
                            <th scope="col">Valor Total</th>
                            <th scope="col">Data</th>
                            <th scope="col">Ações</th>

                        </tr>
                    </thead>

                    <?php

                    foreach ($sales as $key => $value) {

                        ?>

                        <tbody>
                            <tr style="white-space: nowrap;">
                                <th><?php echo htmlspecialchars($value['id']); ?></th>
                                <th><?php echo htmlspecialchars($value['users']); ?></th>
                                <!-- <th><?php // echo htmlspecialchars($value['clients']); ?></th> -->
                                <?php if (htmlspecialchars($value['clients']) == null) {
                                    echo '<th><p>' . 'Cliente consumidor final' . '</p></th>';
                                } else {
                                    echo '<th><p>' . htmlspecialchars($value['clients']) . '</p></th>';
                                }
                                ?>
                                <th><?php echo htmlspecialchars($value['form_payment']); ?></th>
                                <th><?php echo htmlspecialchars($value['status_sales']); ?></th>
                                <th><?php echo htmlspecialchars($value['total_value']); ?></th>
                                <th><?php $date = new DateTime($value['date_sales']);
                                echo htmlspecialchars($date->format('d/m/Y')); ?></th>

                                <th>
                                    <?php
                                    if ($value['status'] == 2) {
                                        ?>
                                        <button onclick="ReopenSales(this)" type="button" data-id="<?php echo $value['id'] ?>"
                                            class="btn btn-info">Reabrir venda
                                        </button>

                                    <?php } else { ?>
                                        <button onclick="CancelSales(this)" data-id="<?php echo $value['id']; ?>" type="button"
                                            class="btn btn-danger accessnivel">Cancelar venda
                                        </button>

                                    <?php } ?>
                                    <button onclick="PrintOut(this)" data-id="<?php echo $value['id']; ?>" type="button"
                                        class="btn btn-primary">Imprimir
                                    </button>

                                    <button onclick="Details(this)" data-id="<?php echo $value['id']; ?>" type="button"
                                        class="btn btn-light">
                                        Mais detalhes
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


<div id="modal-print" class="modal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Venda ID: <span id="saleId"></span></h5>
                <button class="btn-close" onclick="CloseModalInfo()" id="close-details"></button>
            </div>
            <div class="modal-title">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="modalTable" border="1">
                            <thead class="table-dark" style="white-space: nowrap;">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Status</th>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Valor</th>
                                    <th>Forma de pagamento</th>
                                    <th>Usuario</th>
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