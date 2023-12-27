<?php

$users = Controllers::Select('users');
if ($users['id'] == $users['id']) {

    if (isset($_POST['action'])) {

        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $barcode = $_POST['barcode'];
        $value_product = $_POST['value_product'];
        $value_product = str_replace(',', '.', preg_replace("/[^0-9,.]/", "", $value_product));
        number_format($value_product);
        $cost_value = $_POST['cost_value'];
        $cost_value = str_replace(',', '.', preg_replace("/[^0-9,.]/", "", $cost_value));
        number_format($cost_value);
        $model = $_POST['model'];
        $brand = $_POST['brand'];
        $reference = $_POST['reference'];
        $stock_quantity = $_POST['stock_quantity'];
        $register_date = $_POST['register_date'];
        $id_users = $users['id'];
        $flow = $_FILES['flow'];

        $name_img = Panel::UploadsImg($flow);

        if ($name == '' || $value_product == '' || $cost_value == '') {
            Panel::Alert('attention', 'Os campos não podem ficar vázios!');
        } else {
            $verification = Db::Connection()->prepare("SELECT * FROM `products` WHERE name = ? AND id_users = ?");
            $verification->execute([$_POST['name'], $users['id']]);

            if ($verification->rowCount() > 0) {
                $updateQuery = Db::Connection()->prepare("UPDATE `products` SET stock_quantity = stock_quantity + ? WHERE name = ? AND id_users = ?");
                $updateQuery->execute([$_POST['stock_quantity'], $_POST['name'], $users['id']]);
                Panel::Alert('sucess', 'O cadastro do produto ' . $name . ' foi realizado com sucesso!');
            } else {
                $arr = [
                    'name' => $name,
                    'quantity' => $quantity,
                    'barcode' => $barcode,
                    'value_product' => $value_product,
                    'cost_value' => $cost_value,
                    'model' => $model,
                    'brand' => $brand,
                    'reference' => $reference,
                    'stock_quantity' => $stock_quantity,
                    'register_date' => $register_date,
                    'id_users' => $id_users,
                    'flow' => $name_img,
                    'name_table' => 'products'
                ];
                Controllers::Insert($arr);
                Panel::Alert('sucess', 'O cadastro do produto ' . $name . ' foi realizado com sucesso!');
            }
        }
    }
}
?>


<div class="box-content">
    <h2>Controle de produtos</h2>
    <form class="form" method="post" enctype="multipart/form-data">
        <div class="content-form">
            <label for="">Nome Produto</label>
            <input type="text" name="name">
        </div>
        <div class="content-form">
            <label for="">Quantidade</label>
            <input type="text" name="quantity">
        </div>
        <div class="content-form">
            <label for="">Quantidade no estoque</label>
            <input type="text" name="stock_quantity">
        </div>
        <div class="content-form">
            <label for="">Código de Barras</label>
            <input type="text" name="barcode">
        </div>
        <div ref="cpf" class="content-form">
            <label for="">Preço</label>
            <input type="text" name="value_product" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
        </div>
        <div class="content-form">
            <label for="">Valor de custo</label>
            <input type="text" name="cost_value" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
        </div>
        <div class="content-form">
            <label for="">Referencia</label>
            <input type="text" name="reference">
        </div>
        <div class="content-form">
            <label for="">Modelo</label>
            <input type="text" name="model">
        </div>
        <div class="content-form">
            <label for="">Marca</label>
            <input type="text" name="brand">
        </div>
        <div class="content-form">
            <label for="">Imagem do Produto</label>
            <input type="file" name="flow">
        </div>
        <div class="content-form">
            <label for="">Data de registro</label>
            <input type="date" name="register_date">
        </div>
        <div class="content-form">
            <input type="hidden" name="id_users" value=""/>
            <input type="hidden" name="name_table" value="products"/>
            <input type="submit" name="action" value="Cadastrar">
        </div>
    </form>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>