<?php

if (isset($_GET['id'])) {
    $id = (int) base64_decode($_GET['id']);
    $update = Controllers::SelectRequestItensOrder('request', 'r.id=?', array($id));
} else {
    Panel::alert('error', 'Você precisa passar o parametro ID.');
    die();
}

?>

<h2 class="h2-global">Comanda: <?php $update['table_request']; ?></h2><br>

<div class="box-content w50 left">
    <div class="card-itens-order">

        <?php

        $product_order_add = Controllers::SelectAll('products');

        foreach ($product_order_add as $key => $value) {

            ?>

            <div class="info-itens-order" id="info-itens-order"
                onclick="AddProductOrder('<?php echo htmlspecialchars($value['id']); ?>', '<?php echo htmlspecialchars($value['name']); ?>', '<?php echo htmlspecialchars($value['stock_quantity']); ?>', '<?php echo htmlspecialchars($value['value_product']); ?>')">
                <div class="name-product-order">
                    <h2 class="h2-order">
                        <i class="fas fa-shopping-cart"></i> <?php echo $value['name']; ?>
                    </h2>
                </div>
                <div class="value-product-order">
                    <h5>R$
                        <?php echo $value['value_product']; ?>
                    </h5>
                </div>
            </div>

        <?php } ?>

    </div>
</div>

<form action="" class="right w50">
    <div class="order-list">
        <div class="order">
            <div class="order-header">
                <h2>Items
                    <?php echo $update['table_request'] ?>
                </h2>
            </div>
            <ul class="items-list-order">
                <?php
                $userDisplayed = false;
                $dateDisplayed = false;
                $hasProducts = false;

                foreach ($update as $product) {

                    if (!$hasProducts) {
                        echo '<table class="table-order">';
                        echo '<thead><tr class="thead-order"><td>#</td><td>Produto</td><th>Quantidade</td><td>Valor</td><td>Acoes</td></tr></thead>';
                        echo '<tbody id="items-list-order">';
                        $hasProducts = true;
                    }

                    echo '<tr class="tr-order">';
                    echo '<td class="product-id-order" id="product-id-order">' . $product['id'] . '</td>';
                    echo '<td id="product-order-name">' . $product['product_request'] . '</td>';
                    echo '<td id="product-quantity-order">' . $product['quantity'] . '</td>';
                    echo '<td>R$' . $product['price_request'] . '</td>';
                    echo '</tr>';
                }

                if ($hasProducts) {
                    echo '</tbody></table>';
                }

                if (!$userDisplayed) {
                    echo '<li class="item-order">Usuario: ' . $product['user_request'] . '</li>';
                    $userDisplayed = true;
                }

                if (!$dateDisplayed) {
                    echo '<li class="item-order">Data do pedido: ' . $product['date_request'] . '</li>';
                    $dateDisplayed = true;
                }
                ?>
            </ul>
        </div>
    </div>
</form>
<button class="button-order" onclick="AddProductItems()">
    <i class="fas fa-plus-circle"></i> Adicionar Itens
</button>

<div class="erro-global">
    <h2 id="erro-global-h2"></h2>
</div>

<div class="sucess-global">
    <h2 id="sucess-global-h2"></h2>
</div>


<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>