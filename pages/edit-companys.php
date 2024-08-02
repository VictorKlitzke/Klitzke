<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('company', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<?php

if (isset($_POST['action'])) {

  $name = $_POST['name'];
  $cnpj = $_POST['cnpj'];
  $state_registration = $_POST['state_registration'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];

  $verification = Db::Connection()->prepare("SELECT * FROM `company` WHERE name = ? AND cnpj = ? AND state_registration = ? AND email = ? 
                                            AND phone = ? AND address = ? AND city = ? AND state = ? AND id != ?");
  $verification->execute(
    array(
      $_POST['name'],
      $_POST['cnpj'],
      $_POST['state_registration'],
      $_POST['email'],
      $_POST['phone'],
      $_POST['address'],
      $city = $_POST['city'],
      $_POST['state'],
      $id
    )
  );
  if ($verification->rowCount() == 0) {
    $arr = [
      'name' => $name,
      'cnpj' => $cnpj,
      'state_registration' => $state_registration,
      'email' => $email,
      'phone' => $phone,
      'address' => $address,
      'state' => $state,
      'id' => $id,
      'name_table' => 'company'
    ];
    Controllers::Update($arr);
    header('Location: ' . INCLUDE_PATH . 'list-companys');
  } else {
    Panel::alert('error', 'Não foi possível alterar o cliente');
  }
}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Editar Empresa</h2>
  <div class="row g-3" method="post" enctype="multipart/form-data">
    <div class="col-sm-6">
      <label class="text-white">Nome</label>
      <input type="text" class="form-control" name="name" value="<?php echo $update['name']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">CNPJ</label>
      <input type="text" class="form-control" name="cnpj" value="<?php echo $update['cnpj']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Contato</label>
      <input type="text" class="form-control" name="phone" value="<?php echo $update['phone']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Inscrição Estadual</label>
      <input type="text" class="form-control" name="state_registration" value="<?php echo $update['state_registration']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Email</label>
      <input type="text" class="form-control" name="email" value="<?php echo $update['email']; ?>" />
    </div>
    <div class="col-sm-6">
      <label class="text-white">Endereço</label>
      <input type="text" class="form-control" name="address" value="<?php echo $update['address']; ?>" />
    </div>
    <div class="col-12">
      <label class="text-white">Estado</label>
      <input type="text" class="form-control" name="state" value="<?php echo $update['state']; ?>" />
    </div>
    <div class="col-sm-6">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="name_table" value="company">
    </div>
    <div class="col-12">
      <input type="submit" class="btn btn-primary" name="action" value="Editar Empresa">
    </div>
  </div>

</div>