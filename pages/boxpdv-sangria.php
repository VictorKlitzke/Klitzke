<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
} else {
  die();
}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Fazer retirada do caixa</h2>
  <div class="row g-3" method="post" enctype="multipart/form-data">
    <div class="col-sm-6">
      <label class="text-white">Valor</label>
      <input type="text" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00" class="form-control" />
      <span id="value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Observação</label>
      <input type="text" id="observation" class="form-control" />
      <span id="observation-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" onclick="RegisterSangria()" type="button">Fazer retirada</button>
    </div>
  </div>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>