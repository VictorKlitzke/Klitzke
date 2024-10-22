<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('products', 'id=?', array($id));
} else {
  die();
}

$page_permission = 'edit-products';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Editar Produto</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-white">Nome</label>
      <input type="hidden" id="id_products" value="<?php echo base64_encode($update['id']); ?>" />
      <input type="text" class="form-control" id="product" value="<?php echo $update['name']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Quantidade</label>
      <input type="number" class="form-control" id="quantity" value="<?php echo $update['quantity']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Quantidade em Estoque</label>
      <input type="number" class="form-control" id="stock_quantity" value="<?php echo $update['stock_quantity']; ?>">
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Referencia</label>
      <input type="text" class="form-control" id="reference" value="<?php echo $update['reference']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Modelo</label>
      <input type="text" class="form-control" id="model" value="<?php echo $update['model']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Valor do Produto</label>
      <input type="text" class="form-control" id="value_product" value="<?php echo $update['value_product']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Valor de Custo</label>
      <input type="text" class="form-control" id="cost_value" value="<?php echo $update['cost_value']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Marca</label>
      <input type="text" class="form-control" id="brand" value="<?php echo $update['brand']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-12">
      <label class="text-white">Código de Barras</label>
      <input type="text" class="form-control" id="barcode" value="<?php echo $update['barcode']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditProducts()">Editar Produto</button>
    </div>
  </div>
</div>