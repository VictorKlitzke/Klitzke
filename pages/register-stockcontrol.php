
<div class="box-content">
    <h2>Controle de produtos</h2>
    <form class="form">
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
    </form>
    <button class="button-registers" onclick="RegisterProducts()" type="button">Cadastrar</button>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>

<script>
<!-- Adicione este trecho de código dentro do bloco <script> -->
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.form');

    form.addEventListener('change', function (event) {
        if (event.target.name === 'cost_value') {
            updateCalculatedPrice();
        }
    });

    function formatCurrency(value) {
        return 'R$ ' + value.toFixed(2).replace('.', ',');
    }

    function updateCalculatedPrice() {
        var costInput = form.elements['cost_value'];
        var priceInput = form.elements['value_product'];
        var costValue = parseFloat(costInput.value.replace(/[^0-9,.]/g, '').replace(',', '.')) || 0;
        var multiply = <?php echo json_encode($multiply); ?>; 
        var calculatedPrice = costValue * multiply;

        priceInput.value = formatCurrency(calculatedPrice);
    }

    updateCalculatedPrice();
});

</script>