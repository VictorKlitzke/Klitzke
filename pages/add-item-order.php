<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('request', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<div class="add-itens-order w100">
    <h2>Adicionar mais itens no pedido</h2>
    <div class="card-itens-order">

        <?php

            $product_order_add = Controllers::Select('products');
        
        ?>

        <div class="info-itens-order">
            <div class="name-product-order">
                <h2><?php echo $product_order_add['name']; ?></h2>
            </div>
            <div class="value-product-order">
                <h5>R$ <?php echo $product_order_add['value_product']; ?></h5>
            </div>
        </div>
    </div>

</div>