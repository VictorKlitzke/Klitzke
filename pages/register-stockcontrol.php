<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$status_product = 'Em estoque';

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (isset($_POST['action'])) {

    $multiply = Controllers::Select('config_multiply_product');

    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $barcode = $_POST['barcode'];

    $cost_value = $_POST['cost_value'];
    $cost_value = str_replace(',', '.', preg_replace("/[^0-9,.]/", "", $cost_value));
    $cost_value = floatval($cost_value);

    $default = $cost_value * $multiply['multiply'];

    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $reference = $_POST['reference'];
    $stock_quantity = $_POST['stock_quantity'];
    $register_date = $_POST['register_date'];
    $id_users = $user_id;
    $flow = $_FILES['flow'];
    $status_product = $_POST['status_product'];

    $name_img = Panel::UploadsImg($flow);

    if ($name == '' || $cost_value == '') {
        Panel::Alert('attention', 'Os campos não podem ficar vázios!');
    } else {
        $verification = Db::Connection()->prepare("SELECT * FROM `products` WHERE name = ? AND id_users = ?");
        $verification->execute([$_POST['name'], $user_id]);

        if ($verification->rowCount() > 0) {
            $updateQuery = Db::Connection()->prepare("UPDATE `products` SET stock_quantity = stock_quantity + ?, status_product = 'Em estoque' WHERE name = ? AND id_users = ?");
            $updateQuery->execute([$_POST['stock_quantity'], $_POST['status_product'], $_POST['name'], $user_id]);
            Panel::Alert('sucess', 'O cadastro do produto ' . $name . ' foi realizado com sucesso!');
        } else {
            $arr = [
                'name' => $name,
                'quantity' => $quantity,
                'barcode' => $barcode,
                'value_product' => $default,
                'cost_value' => $cost_value,
                'model' => $model,
                'brand' => $brand,
                'reference' => $reference,
                'stock_quantity' => $stock_quantity,
                'register_date' => $register_date,
                'id_users' => $id_users,
                'flow' => $name_img,
                'status_product' => 'Em estoque',
                'name_table' => 'products'
            ];
            Controllers::Insert($arr);
            Panel::Alert('sucess', 'O cadastro do produto ' . $name . ' foi realizado com sucesso!');
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
            <input type="hidden" name="id_users" />
            <input type="hidden" name="status_product" />
            <input type="hidden" name="name_table" value="products" />
            <input type="submit" name="action" value="Cadastrar">
        </div>
    </form>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>

<script>
<!-- Adicione este trecho de código dentro do bloco <script> -->
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.form');

    // Atualize o valor do campo "Preço" quando houver uma alteração no campo "Custo"
    form.addEventListener('change', function (event) {
        if (event.target.name === 'cost_value') {
            updateCalculatedPrice();
        }
    });

    // Função para formatar números como moeda (R$)
    function formatCurrency(value) {
        return 'R$ ' + value.toFixed(2).replace('.', ',');
    }

    // Função para calcular e atualizar o valor no campo "Preço"
    function updateCalculatedPrice() {
        var costInput = form.elements['cost_value'];
        var priceInput = form.elements['value_product'];

        // Remova qualquer caractere que não seja número ou ponto
        var costValue = parseFloat(costInput.value.replace(/[^0-9,.]/g, '').replace(',', '.')) || 0;

        var multiply = <?php echo json_encode($multiply); ?>; // Certifique-se de que $multiply esteja disponível no script PHP

        var calculatedPrice = costValue * multiply;

        // Atualize o campo "Preço" com o valor formatado como moeda
        priceInput.value = formatCurrency(calculatedPrice);
    }

    // Chame a função para garantir que o valor seja calculado quando a página é carregada
    updateCalculatedPrice();
});

</script>