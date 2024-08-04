<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('clients', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Editar Cliente</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-white">Nome</label>
      <input type="hidden" id="id_client" value="<?php echo base64_encode($update['id']); ?>" />
      <input type="text" class="form-control" id="name" value="<?php echo $update['name']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">CPF</label>
      <input type="text" class="form-control" id="cpf" value="<?php echo $update['cpf']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Nome fantasia</label>
      <input type="text" class="form-control" id="social_reason" value="<?php echo $update['social_reason']; ?>">
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
      <label class="text-white">CEP</label>
      <input type="text" class="form-control" id="cep" value="<?php echo $update['cep']; ?>" />
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
    <div class="col-sm-12">
      <label class="text-white">Bairro</label>
      <input type="text" class="form-control" id="neighborhood" value="<?php echo $update['neighborhood']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditClients()">Editar Cliente</button>
    </div>
  </div>
</div>