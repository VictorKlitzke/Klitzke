<?php

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$userFilter = isset($_POST['userFilter']) ? intval($_POST['userFilter']) : $user_id;

$sales = Controllers::SelectSales('sales', ($currentPage - 1) * $porPage, $porPage, $userFilter, $form_payment);

?>

<div class="box-content">
     <div class="filter-container">
        <div class="filter-content">
            <h2 style="color: #000">Filtros</h2>
            <div class="filter-form">
                <form method="post">
                    <select name="userFilter" id="userFilter">

                        <?php

                        $users = Controllers::SelectAll('users');

                        foreach ($users as $user) {
                            echo '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
                        }

                        ?>

                    </select>
                    <button class="filter" type="submit">Filtrar</button>
                </form>
                <form method="post">
                    <select name="form_filter" id="form_filter">

                        <?php

                        $form_payment = Controllers::SelectAll('form_payment');

                        foreach ($form_payment as $form_payments) {
                            echo '<option value="' . $form_payments['id'] . '">' . $form_payments['name'] . '</option>';
                        }

                        ?>

                    </select>
                    <button class="filter" type="submit">Filtrar</button>
                </form>
            </div>
        </div>
    </div>
    <h2 class="text-white mb-4">Lista de Vendas</h2>
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr style="white-space: nowrap;">

                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Forma de Pagamento</th>
                            <th scope="col">Status Venda</th>
                            <th scope="col">Valor Total</th>
                            <th scope="col">Data</th>

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
                                <th><?php echo htmlspecialchars($value['date_sales']); ?></th>

                                <th>
                                    <?php
                                    if ($value['status'] == 2) {
                                        ?>
                                            <button onclick="ReopenSales(this)" type="button"
                                                data-id="<?php echo $value['id'] ?>" class="btn-reopen">Reabrir venda
                                            </button>

                                    <?php } else { ?>
                                            <button onclick="CancelSales(this)" data-id="<?php echo $value['id']; ?>"
                                                type="button" class="btn-cancel">Cancelar venda
                                            </button>

                                    <?php } ?>
                                        <button onclick="PrintOut(this)" data-id="<?php echo $value['id']; ?>" type="button"
                                            class="btn-delete">Imprimir
                                        </button>

                                        <button onclick="Details(this)" data-id="<?php echo $value['id']; ?>" type="button"
                                            class="btn-details">
                                            <p>Mais detalhes</p>
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

<div class="overlay-details" id="overlay-details">
    <div id="modal-print" class="modal">
        <div class="modal-content-details" id="modal-content-details">
            <span class="close-details" onclick="CloseModalInfo()" id="close-details">&times;</span>
            <h1>Venda ID: <span id="saleId"></span></h1>
            <table id="modalTable" border="1">
                <thead>
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