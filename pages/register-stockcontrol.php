<div class="box-content">
    <h2>Controle de produtos</h2>
    <form class="form">
        <div class="content-form">
            <label for="">Nome Produto</label>
            <input type="text" id="name">
            <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Quantidade</label>
            <input type="text" id="quantity">
            <span id="quantity-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Quantidade no estoque</label>
            <input type="text" id="stock_quantity">
            <span id="stock_quantity-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Código de Barras</label>
            <input type="text" id="barcode">
            <span id="barcode-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div ref="cpf" class="content-form">
            <label for="">Preço</label>
            <input type="text" id="value_product" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
            <span id="value_product-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Valor de custo</label>
            <input type="text" id="cost_value" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
            <span id="cost_value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Referencia</label>
            <input type="text" id="reference">
            <span id="reference-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Modelo</label>
            <input type="text" id="model">
            <span id="model-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Marca</label>
            <input type="text" id="brand">
            <span id="brand-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Tamanho</label>
            <select id="size">
                <?php
                $id_size = Controllers::SizeClothes('clothes_size');

                foreach ($id_size as $key => $value) {
                    ?>
                    <option <?php if ($value['id'] == @$_POST['id_size'])
                        echo 'selected'; ?>value="<?php echo $value['id'] ?>"><?php echo $value['size']; ?></option>
                <?php } ?>
            </select>
            <span id="size-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Imagem do Produto</label>
            <input type="file" id="flow">
            <span id="flow-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
    </form>
    <button class="button-registers" onclick="RegisterProducts()" type="button">Cadastrar</button>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>

</>