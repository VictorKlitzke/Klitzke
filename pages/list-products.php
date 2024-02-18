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
                <td><p>Codigo de barras</p></td>
                <td>Valor</td>
                <td>Valor Custo</td>
                <td><p>Quantidade em estoque</p></td>
                <td>Referencia</td>
                <td><p>Usuário cadastro</p></td>
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
                    <td>R$ <?php echo htmlspecialchars($value['value_product']); ?></td>
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

<?php 
    
    $sql = Db::Connection();
    $exec = $sql->prepare("SELECT SUM(value_product) as value_product FROM products");
    $exec->execute();
    $result_value_product = $exec->fetch(PDO::FETCH_ASSOC);

?>

<?php 

    $sql = Db::Connection();
    $exec = $sql->prepare("SELECT SUM(quantity) as quantity FROM products");
    $exec->execute();
    $result_quantity_product = $exec->fetch(PDO::FETCH_ASSOC);

?>

<?php 

    $sql = Db::Connection();
    $exec = $sql->prepare("SELECT SUM(cost_value) as cost_value FROM products");
    $exec->execute();
    $result_cost_value_product = $exec->fetch(PDO::FETCH_ASSOC);

?>

<div class="totalizator">
    <div class="card-totalizator">
        <h2>Quantidade total de itens</h2>
        <h2><?php echo $result_quantity_product['quantity']; ?></h2>
    </div>
    <div class="card-totalizator">
        <h2>Total valores produtos</h2>
        <h2><?php echo $result_value_product['value_product'];?> Reais</h2>
    </div>
    <div class="card-totalizator">
        <h2>Total valores de custo</h2>
        <h2><?php echo $result_cost_value_product['cost_value'];?> Reais</h2>
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