<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('company', 'id=?', array($id));
} else {
  die();
}

$page_permission = 'edit-companys';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Editar Empresa</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-white">Nome</label>
      <input type="hidden" id="id_company" value="<?php echo base64_encode($update['id']); ?>" />
      <input type="text" class="form-control" id="name" value="<?php echo $update['name']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">CNPJ</label>
      <input type="text" class="form-control" id="cnpj" value="<?php echo $update['cnpj']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Contato</label>
      <input type="text" class="form-control" id="phone" value="<?php echo $update['phone']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Inscrição Estadual</label>
      <input type="text" class="form-control" id="state_registration" value="<?php echo $update['state_registration']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Email</label>
      <input type="text" class="form-control" id="email" value="<?php echo $update['email']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Endereço</label>
      <input type="text" class="form-control" id="address" value="<?php echo $update['address']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Cidade</label>
      <input type="text" class="form-control" id="city" value="<?php echo $update['city']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Estado</label>
      <input type="text" class="form-control" id="state" value="<?php echo $update['state']; ?>" />
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditCompany()">Editar Empresa</button>
    </div>
  </div>

</div>