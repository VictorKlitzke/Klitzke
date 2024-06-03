<?php

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (isset($_GET['delete'])) {
    $delete = intval($_GET['delete']);
    Controllers::Delete('sales', $delete);
    header('Location: ' . INCLUDE_PATH . 'list-sales');
}

$userFilter = isset($_POST['userFilter']) ? intval($_POST['userFilter']) : $user_id;

$form_payment = isset($_POST['form_filter']) ? intval($_POST['form_filter']) : null;

$currentPage = isset($_GET['page']) ? (int) ($_GET['page']) : 1;
$porPage = 100;

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
    <h2>Lista de Vendas</h2>
    <div class="list">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Usuario</td>
                    <p><td>Cliente</td></p>
                    <td><p>Forma de Pagamento</p></td>
                    <p><td>Status Venda</td></p>
                    <p><td>Valor Total</td></p>
                    <p><td>Data</td></p>

                </tr>
            </thead>

            <?php

            foreach ($sales as $key => $value) {

            ?>

                <tbody>
                    <tr>
                        <p><td><?php echo htmlspecialchars($value['id']); ?></td></p>
                        <p><td><?php echo htmlspecialchars($value['users']); ?></td></p>
                        <!-- <td><?php // echo htmlspecialchars($value['clients']); ?></td> -->
                        <?php if(htmlspecialchars($value['clients']) == null){
                            echo '<td><p>' . 'Cliente consumidor final' . '</p></td>';
                        } else {
                            echo '<td><p>' . htmlspecialchars($value['clients']) . '</p></td>';
                        }
                        ?>
                        <td><?php echo htmlspecialchars($value['form_payment']); ?></td>
                        <td><?php echo htmlspecialchars($value['status_sales']); ?></td>
                        <td><?php echo htmlspecialchars($value['total_value']); ?></td>
                        <td><p><?php echo htmlspecialchars($value['date_sales']); ?></p></td>

                        <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                            <?php
                                if($value['status'] == 2) {
                            ?>
                            
                            <div>
                                <button onclick="ReopenSales(this)"
                                        type="button" data-id="<?php echo $value['id'] ?>" class="btn-reopen">Reabrir venda
                                </button>
                            </div>

                            <?php } else { ?>

                                    <div>
                                        <button onclick="CancelSales(this)" data-id="<?php echo $value['id']; ?>"
                                                type="button" class="btn-cancel">Cancelar venda
                                        </button>
                                    </div>

                            <?php } ?>

                            <div>
                                <button onclick="PrintOut(this)" data-id="<?php echo $value['id']; ?>"
                                        type="button" class="btn-delete">Imprimir
                                </button>
                            </div>

                            <div>
                                <button onclick="Details(this)" data-id="<?php echo $value['id']; ?>" type="button" class="btn-details">
                                    <p>Mais detalhes</p>
                                </button>
                            </div>

                        </td>
                    </tr>
                </tbody>

            <?php } ?>

        </table>
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

<div class="page">
    <?php
    $totalPage = ceil(count(Controllers::selectAll('sales')) / $porPage);

    for ($i = 1; $i <= $totalPage; $i++) {
        if ($i == $currentPage)
            echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-sales?page=' . $i . '">' . $i . '</a>';
        else
            echo '<a href="' . INCLUDE_PATH . 'list-sales?page=' . $i . '">' . $i . '</a>';
    }

    ?>
</div>