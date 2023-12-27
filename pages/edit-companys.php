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
  <h2>Editar Empresa</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Nome</label>
      <input type="text" name="name" value="<?php echo $update['name']; ?>" />
    </div>
    <div class="form-group">
      <label>CNPJ</label>
      <input type="text" name="cnpj" value="<?php echo $update['cnpj']; ?>" />
    </div>
    <div class="form-group">
      <label>Contato</label>
      <input type="text" name="phone" value="<?php echo $update['phone']; ?>" />
    </div>
    <div class="form-group">
      <label>Inscrição Estadual</label>
      <input type="text" name="state_registration" value="<?php echo $update['state_registration']; ?>" />
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="text" name="email" value="<?php echo $update['email']; ?>" />
    </div>
    <div class="form-group">
      <label>Endereço</label>
      <input type="text" name="address" value="<?php echo $update['address']; ?>" />
    </div>
    <div class="form-group">
      <label>Estado</label>
      <input type="text" name="state" value="<?php echo $update['state']; ?>" />
    </div>
    <div class="form-group">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="name_table" value="company">
    </div>
    <div class="form-group">
      <input type="submit" name="action" value="Editar Empresa">
    </div>
  </form>

</div>