<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('suppliers', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Editar Fornecedor</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-white">Fornecedor</label>
      <input type="hidden" id="id_forn" value="<?php echo base64_encode($update['id']); ?>">
      <input type="text" class="form-control" id="name_company" value="<?php echo $update['company']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">cnpjcpf</label>
      <input type="text" class="form-control" id="cnpjcpf" value="<?php echo $update['cnpjcpf']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Nome fantasia</label>
      <input type="text" class="form-control" id="fantasy_name" value="<?php echo $update['fantasy_id']; ?>">
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Email</label>
      <input type="text" class="form-control" id="email" value="<?php echo $update['email']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Contato</label>
      <input type="text" class="form-control" id="phone" value="<?php echo $update['phone']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Cidade</label>
      <input type="text" class="form-control" id="city" value="<?php echo $update['city']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="col-sm-6">
      <label class="text-white">Endereço</label>
      <input type="text" class="form-control" id="address" value="<?php echo $update['address']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Estado</label>
      <input type="text" class="form-control" id="state" value="<?php echo $update['state']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditForn()">Editar Fornecedor</button>
    </div>
  </div>
</div>