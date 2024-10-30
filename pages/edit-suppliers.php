<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('suppliers', 'id=?', array($id));
} else {
  die();
}

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}

$page_permission = 'edit-suppliers';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
  <h2 class="text-dark mt-4">Editar Fornecedor</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-dark">Fornecedor</label>
      <input type="hidden" id="id_forn" value="<?php echo base64_encode($update['id']); ?>">
      <input type="text" class="form-control border-dark" id="name_company" value="<?php echo $update['company']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">cnpjcpf</label>
      <input type="text" class="form-control border-dark" id="cnpjcpf" value="<?php echo $update['cnpjcpf']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Nome fantasia</label>
      <input type="text" class="form-control border-dark" id="fantasy_name" value="<?php echo $update['fantasy_name']; ?>">
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
      <label class="text-dark">Cidade</label>
      <input type="text" class="form-control border-dark" id="city" value="<?php echo $update['city']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="col-sm-6">
      <label class="text-dark">Endereço</label>
      <input type="text" class="form-control border-dark" id="address" value="<?php echo $update['address']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Estado</label>
      <input type="text" class="form-control border-dark" id="state" value="<?php echo $update['state']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditForn()">Editar Fornecedor</button>
    </div>
  </div>
</div>