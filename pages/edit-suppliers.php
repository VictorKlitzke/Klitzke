<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('suppliers', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<?php

if (isset($_POST['action'])) {

  $company = $_POST['company'];
  $fantasy_name = $_POST['fantasy_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $cnpjcpf = $_POST['cnpjcpf'];

  $verification = Db::Connection()->prepare("SELECT * FROM `suppliers` WHERE company = ? AND fantasy_name = ? AND email = ?
                                            AND phone = ? AND city = ? AND address = ? AND cnpjcpf = ? AND state = ? AND id != ?");
  $verification->execute(
    array(
      $_POST['company'],
      $_POST['email'],
      $_POST['fantasy_name'],
      $_POST['phone'],
      $_POST['address'],
      $_POST['city'],
      $_POST['state'],
      $_POST['cnpjcpf'],
      $id
    )
  );
  if ($verification->rowCount() == 0) {
    $arr = [
      'company' => $company,
      'fantasy_name' => $fantasy_name,
      'email' => $email,
      'phone' => $phone,
      'cep' => $cep,
      'address' => $address,
      'city' => $city,
      'state' => $state,
      'cnpjcpf' => $cnpjcpf,
      'id' => $id,
      'name_table' => 'suppliers'
    ];
    Controllers::Update($arr);
    header('Location: ' . INCLUDE_PATH . 'list-suppliers');
  } else {
    Panel::alert('error', 'Não foi possível alterar o fornecedor');
  }
}

?>

<div class="box-content">
  <h2>Editar Fornecedor</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Fornecedor</label>
      <input type="text" name="company" value="<?php echo $update['company']; ?>" />
    </div>
    <div class="form-group">
      <label>cnpjcpf</label>
      <input type="text" name="cnpjcpf" value="<?php echo $update['cnpjcpf']; ?>" />
    </div>
    <div class="form-group">
      <label>Nome fantasia</label>
      <input type="text" name="fantasy_name" value="<?php echo $update['fantasy_name']; ?>">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="text" name="email" value="<?php echo $update['email']; ?>" />
    </div>
    <div class="form-group">
      <label>Contato</label>
      <input type="text" name="phone" value="<?php echo $update['phone']; ?>" />
    </div>
    <div class="form-group">
      <label>Cidade</label>
      <input type="text" name="city" value="<?php echo $update['city']; ?>" />
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
      <input type="hidden" name="name_table" value="suppliers">
    </div>
    <div class="form-group">
      <input type="submit" name="action" value="Editar Cliente">
    </div>
  </form>
</div>