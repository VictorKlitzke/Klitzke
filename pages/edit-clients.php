<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('clients', 'id=?', array($id));
} else {
  die();
}

$page_permission = 'edit-clients';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
  <h2 class="text-dark mt-4">Editar Cliente</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-dark">Nome</label>
      <input type="hidden" id="id_client" value="<?php echo base64_encode($update['id']); ?>" />
      <input type="text" class="form-control border-dark" id="name" value="<?php echo $update['name']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">CPF</label>
      <input type="text" class="form-control border-dark" id="cpf" value="<?php echo $update['cpf']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Nome fantasia</label>
      <input type="text" class="form-control border-dark" id="social_reason" value="<?php echo $update['social_reason']; ?>">
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Email</label>
      <input type="text" class="form-control border-dark" id="email" value="<?php echo $update['email']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Contato</label>
      <input type="text" class="form-control border-dark" id="phone" value="<?php echo $update['phone']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">CEP</label>
      <input type="text" class="form-control border-dark" id="cep" value="<?php echo $update['cep']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Cidade</label>
      <input type="text" class="form-control border-dark" id="city" value="<?php echo $update['city']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Endereço</label>
      <input type="text" class="form-control border-dark" id="address" value="<?php echo $update['address']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-12">
      <label class="text-dark">Bairro</label>
      <input type="text" class="form-control border-dark" id="neighborhood" value="<?php echo $update['neighborhood']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditClients()">Editar Cliente</button>
    </div>
  </div>
</div>