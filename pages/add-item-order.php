<?php

if (isset($_GET['id'])) {
    $id = (int) base64_decode($_GET['id']);
    $update = Controllers::SelectRequestItensOrder('request', 'r.id=?', array($id));
} else {
    Panel::alert('error', 'Você precisa passar o parametro ID.');
    die();
}

?>

<h2 class="h2-global">Adicionar mais itens no pedido</h2>
<div class="box-content">
    <div class="card-itens-order">

        <?php

            $product_order_add = Controllers::SelectAll('products');

            foreach ($product_order_add as $key => $value) {

        ?>

            <div class="info-itens-order" id="info-itens-order" onclick="AddProductOrder(<?php echo $key; ?>, '<?php echo $value['id'] ?>', '<?php echo $value['name'] ?>', '<?php echo $value['stock_quantity'] ?>', '<?php echo $value['value_product'] ?>')">
                <div class="name-product-order">
                    <h2 class="h2-order">
                        <p>
                            <?php echo $value['name']; ?>
                        </p>
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

<h2 class="h2-global">Lista de itens</h2>
<form action="">
    <div class="order-list">
        <div class="order">
            <div class="order-header">
                <h2>Comanda:
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
                            echo '<thead><tr class="thead-order"><td>Produto</td><th>Quantidade</td><td>Valor</td></tr></thead>';
                            echo '<tbody id="items-list-order">';
                            $hasProducts = true;
                        }

                        echo '<tr class="tr-order" id="tr-order">';
                        echo '<td>' . $product['product_request'] . '</td>';
                        echo '<td>' . $product['quantity'] . '</td>';
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
            <button class="button-order">Adiconar itens</button>
        </div>
    </div>
</form>


<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>