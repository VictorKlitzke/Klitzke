<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('clients', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<?php

if (isset($_POST['action'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $social_reason = $_POST['social_reason'];
  $phone = $_POST['phone'];
  $cep = $_POST['cep'];
  $city = $_POST['city'];
  $address = $_POST['address'];
  $cpf = $_POST['cpf'];
  $neighborhood = $_POST['neighborhood'];

  $verification = Db::Connection()->prepare("SELECT * FROM `clients` WHERE name = ? AND email = ? AND social_reason = ? 
                                            AND phone = ? AND cep = ? AND city = ? AND address = ? AND cpf = ? AND neighborhood = ? AND id != ?");
  $verification->execute(
    array(
      $_POST['name'],
      $_POST['email'],
      $_POST['social_reason'],
      $_POST['phone'],
      $_POST['cep'],
      $_POST['city'],
      $_POST['address'],
      $_POST['cpf'],
      $_POST['neighborhood'],
      $id
    )
  );
  if ($verification->rowCount() == 0) {
    $arr = [
      'name' => $name,
      'email' => $email,
      'social_reason' => $social_reason,
      'phone' => $phone,
      'cep' => $cep,
      'city' => $city,
      'address' => $address,
      'cpf' => $cpf,
      'neighborhood' => $neighborhood,
      'id' => $id,
      'name_table' => 'clients'
    ];
    Controllers::Update($arr);
    header('Location: ' . INCLUDE_PATH . 'list-clients');
  } else {
    Panel::alert('error', 'Não foi possível alterar o cliente');
  }
}

?>

<div class="box-content">
  <h2>Editar Cliente</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Nome</label>
      <input type="text" name="name" value="<?php echo $update['name']; ?>" />
    </div>
    <div class="form-group">
      <label>CPF</label>
      <input type="text" name="cpf" value="<?php echo $update['cpf']; ?>" />
    </div>
    <div class="form-group">
      <label>Nome fantasia</label>
      <input type="text" name="social_reason" value="<?php echo $update['social_reason']; ?>">
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
      <label>CEP</label>
      <input type="text" name="cep" value="<?php echo $update['cep']; ?>" />
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
      <label>Bairro</label>
      <input type="text" name="neighborhood" value="<?php echo $update['neighborhood']; ?>" />
    </div>
    <div class="form-group">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="name_table" value="clients">
    </div>
    <div class="form-group">
      <input type="submit" name="action" value="Editar Cliente">
    </div>
  </form>
</div>