<?php

if (isset($_GET['delete'])) {
    $delete = intval($_GET['delete']);
    Controllers::Delete('sales', $delete);
    header('Location: ' . INCLUDE_PATH . 'list-sales');
}

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$sales = Controllers::SelectAll('sales', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
    <h2>Lista de Vendas</h2>
    <div class="list">
        <table>
            <thead>
            <tr>
                <td>Cliente</td>
                <p><td>Quantidade</td></p>
                <p><td>Codigo de barras</td></p>
                <td>Valor</td>
                <td>Valor Custo</td>
                <p><td>Quantidade em estoque</td></p>
                <td>Referencia</td>
                <p><td>Usuário cadastro</td></p>
                <td>Modelo</td>
            </tr>
            </thead>

            <?php

            foreach ($sales as $key => $value) {

                ?>

                <tbody>
                <tr>
                    <p><td><?php echo $value['name']; ?></td></p>
                    <td><?php echo $value['quantity']; ?></td>
                    <td><?php echo $value['barcode']; ?></td>
                    <td>R$ <?php echo $value['value_product']; ?>,00</td>
                    <td>R$ <?php echo $value['cost_value']; ?>,00</td>
                    <td><?php echo $value['stock_quantity']; ?></td>
                    <td><?php echo $value['reference']; ?></td>
                    <td><?php echo $value['id_users']; ?></td>
                    <td><?php echo $value['model']; ?></td>

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