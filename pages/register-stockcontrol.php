<?php

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$page_permission = 'register-stockcontrol';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
}

?>
<div class="container-fluid border shadow-lg rounded-4 bg-light p-4">
    <h2 class="text-dark mb-4">Controle de produtos</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label text-dark">Nome Produto</label>
            <input class="form-control border-dark" type="text" id="name" placeholder="Nome Produto" />
            <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Quantidade</label>
            <input class="form-control border-dark" type="text" id="quantity" placeholder="Quantidade" />
            <span id="quantity-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Quantidade no estoque</label>
            <input class="form-control border-dark" type="text" id="stock_quantity"
                placeholder="Quantidade em estoque" />
            <span id="stock_quantity-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Código de Barras</label>
            <input class="form-control border-dark" type="text" id="barcode" placeholder="Código de Barras" />
            <span id="barcode-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Preço</label>
            <input class="form-control border-dark" type="text" id="value_product" id="value"
                oninput="formmaterReal(this)" placeholder="R$ 0,00" />
            <span id="value_product-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Valor de custo</label>
            <input class="form-control border-dark" type="text" id="cost_value" id="value" oninput="formmaterReal(this)"
                placeholder="R$ 0,00" />
            <span id="cost_value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Referência</label>
            <input class="form-control border-dark" type="text" id="reference" placeholder="Referência" />
            <span id="reference-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Modelo</label>
            <input class="form-control border-dark" type="text" id="model" placeholder="Modelo" />
            <span id="model-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Marca</label>
            <input class="form-control border-dark" type="text" id="brand" placeholder="Marca">
            <span id="brand-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-6">
            <label class="form-label text-dark">Tamanho</label>
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
            <label class="form-label text-dark">Unidade</label>
            <select id="units" class="form-select form-select-sm">
                <?php
                $units = Controllers::SelectAll('units');

                foreach ($units as $key => $value) {
                    ?>
                    <option <?php if ($value['id'] == @$_POST['units'])
                        echo 'selected'; ?>value="<?php echo $value['symbol'] ?>"><?php echo $value['symbol']; ?></option>
                <?php } ?>
            </select>
            <span id="units-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-12">
            <label class="form-label text-dark">Imagem do Produto</label>
            <input class="form-control border-dark" type="file" id="flow">
            <span id="flow-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" onclick="RegisterProducts()" type="button">Cadastrar</button>
        </div>
    </div>
</div>

<br>

<div class="container-fluid border shadow-lg rounded-4 bg-light p-4">
    <h2 class="text-dark mb-4">Cadastrar Produto por Nota Fiscal</h2>
    <div class="row">
        <div class="col">
            <form action="http://localhost:3000/klitzke/controllers/pdf.php" method="POST" enctype="multipart/form-data" target="_blank">
                <div class="col">
                    <label class="form-label text-dark">Nota fiscal</label>
                    <input class="form-control border-dark" name="pdf-file" type="file" accept=".pdf" required>
                    <span class="error-message">Campo está inválido, ajuste se possível.</span>
                </div>
                <br>
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>