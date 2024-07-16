<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<div class="box-content">
  <h2>Fazer retirada do caixa</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Valor</label>
      <input type="text" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
      <span id="value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Observação</label>
      <input type="text" id="observation">
      <span id="observation-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
  </form>
  <button class="button-registers" onclick="RegisterSangria()" type="button">Fazer retirada</button>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>