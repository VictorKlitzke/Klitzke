<?php

if (isset($_GET['delete'])) {
    $delete = intval($_GET['delete']);
    Controllers::Delete('sales', $delete);
    header('Location: ' . INCLUDE_PATH . 'list-sales');
}

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$sales = Controllers::SelectSales('sales', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
    <h2>Lista de Vendas</h2>
    <div class="list">
        <table>
            <thead>
            <tr>
                <td>Usuario</td>
                <p><td>Cliente</td></p>
                <p><td>Forma de Pagamento</td></p>
                <td>Produto</td>
                <p><td>Quantidade</td></p>
                <p><td>Valor</td></p>
 
            </tr>
            </thead>

            <?php

            foreach ($sales as $key => $value) {

                ?>

                <tbody>
                <tr>
                    <p><td><?php echo $value['users']; ?></td></p>
                    <td><?php echo $value['clients']; ?></td>
                    <td><?php echo $value['form_payment']; ?></td>
                    <td><?php echo $value['products']; ?></td>
                    <td><?php echo $value['quantity']; ?>,00</td>
                    <td><?php echo $value['value']; ?></td>

                    <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                        <div>
                            <a class="btn-edit" href="<?php echo INCLUDE_PATH ?>edit-sales?id=<?php echo base64_encode($value['id']); ?>">Reabrir venda</a>
                        </div>
                        <div>
                            <a class="btn-edit" href="<?php echo INCLUDE_PATH ?>edit-sales?id=<?php echo base64_encode($value['id']); ?>">Cancelar venda</a>
                        </div>
                        <div>
                            <a class="btn-delete" href="<?php echo INCLUDE_PATH ?>list-sales?delete=<?php echo base64_encode($value['id']); ?>">Imprimir</a>
                        </div>
                    </td>
                </tr>
                </tbody>

            <?php } ?>

        </table>
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