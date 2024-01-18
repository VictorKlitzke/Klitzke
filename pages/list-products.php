<?php

if (isset($_GET['delete'])) {
    $del = intval($_GET['delete']);
    Controllers::Delete('products', $del);
    header('Location: ' . INCLUDE_PATH . 'list-products');
}

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 100;

$products = Controllers::SelectAll('products', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
    <h2>Lista de Produtos</h2>
    <div class="list">
        <table>
            <thead>
            <tr>
                <td>Produtos</td>
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

            foreach ($products as $key => $value) {

                ?>

                <tbody>
                <tr>
                    <p><td><?php echo htmlspecialchars($value['name']); ?></td></p>
                    <td><?php echo htmlspecialchars($value['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($value['barcode']); ?></td>
                    <td>R$ <?php echo htmlspecialchars($value['total_value']); ?></td>
                    <td>R$ <?php echo htmlspecialchars($value['cost_value']); ?></td>
                    <td><?php echo htmlspecialchars($value['stock_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($value['reference']); ?></td>
                    <td><?php echo htmlspecialchars($value['id_users']); ?></td>
                    <td><?php echo htmlspecialchars($value['model']); ?></td>

                    <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                        <!-- <div>
                            <a class="btn-edit" href="<?php //echo INCLUDE_PATH ?>edit-products?id=<?php //echo base64_encode($value['id']); ?>">Editar</a>
                        </div> -->
                        <div>
                            <a actionBtn="delete" class="btn-delete" href="<?php echo INCLUDE_PATH ?>list-products?delete=<?php echo $value['id']; ?>">Deletar</a>
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
    $totalPage = ceil(count(Controllers::selectAll('products')) / $porPage);

    for ($i = 1; $i <= $totalPage; $i++) {
        if ($i == $currentPage)
            echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-products?page=' . $i . '">' . $i . '</a>';
        else
            echo '<a href="' . INCLUDE_PATH . 'list-products?page=' . $i . '">' . $i . '</a>';
    }

    ?>
</div>