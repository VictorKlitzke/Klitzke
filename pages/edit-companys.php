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

<div class="container-fluid bg-light p-4 rounded-4 border shadow-lg">
  <h2 class="text-dark mt-4">Editar Empresa</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-dark">Nome</label>
      <input type="hidden" id="id_company" value="<?php echo base64_encode($update['id']); ?>" />
      <input type="text" class="form-control border-dark" id="name" value="<?php echo $update['name']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">CNPJ</label>
      <input type="text" class="form-control border-dark" id="cnpj" value="<?php echo $update['cnpj']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Contato</label>
      <input type="text" class="form-control border-dark" id="phone" value="<?php echo $update['phone']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Inscrição Estadual</label>
      <input type="text" class="form-control border-dark" id="state_registration" value="<?php echo $update['state_registration']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Email</label>
      <input type="text" class="form-control border-dark" id="email" value="<?php echo $update['email']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Endereço</label>
      <input type="text" class="form-control border-dark" id="address" value="<?php echo $update['address']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Cidade</label>
      <input type="text" class="form-control border-dark" id="city" value="<?php echo $update['city']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Estado</label>
      <input type="text" class="form-control border-dark" id="state" value="<?php echo $update['state']; ?>" />
    </div>
    <div class="col-12">
      <button type="button" class="btn btn-primary" onclick="EditCompany()">Editar Empresa</button>
    </div>
  </div>

</div>