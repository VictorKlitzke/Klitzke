<div class="box-content">
    <h2 class="text-white mb-4">Controle de produtos</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label text-white">Nome Produto</label>
            <input class="form-control" type="text" id="name" placeholder="Nome Produto" />
            <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Quantidade</label>
            <input class="form-control" type="text" id="quantity" placeholder="Quantidade" />
            <span id="quantity-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Quantidade no estoque</label>
            <input class="form-control" type="text" id="stock_quantity" placeholder="Quantidade em estoque" />
            <span id="stock_quantity-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Código de Barras</label>
            <input class="form-control" type="text" id="barcode" placeholder="Código de Barras" />
            <span id="barcode-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Preço</label>
            <input class="form-control" type="text" id="value_product" id="value" oninput="formmaterReal(this)"
                placeholder="R$ 0,00" />
            <span id="value_product-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Valor de custo</label>
            <input class="form-control" type="text" id="cost_value" id="value" oninput="formmaterReal(this)"
                placeholder="R$ 0,00" />
            <span id="cost_value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Referência</label>
            <input class="form-control" type="text" id="reference" placeholder="Referência" />
            <span id="reference-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Modelo</label>
            <input class="form-control" type="text" id="model" placeholder="Modelo" />
            <span id="model-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Marca</label>
            <input class="form-control" type="text" id="brand" placeholder="Marca">
            <span id="brand-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-6">
            <label class="form-label text-white">Tamanho</label>
            <select id="size" class="form-select form-select-sm">
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
        <div class="col-md-6">
            <label class="form-label text-white">Imagem do Produto</label>
            <input class="form-control" type="file" id="flow">
            <span id="flow-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
        <button class="btn btn-primary" onclick="RegisterProducts()" type="button">Cadastrar</button>
        </div>
    </div>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>

</>