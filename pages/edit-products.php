<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
  $update = Controllers::Select('products', 'id=?', array($id));
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<div class="box-content">
  <h2>Editar Produto</h2>
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
      <input type="hidden" name="name_table" value="products">
    </div>
    <div class="form-group">
      <input type="submit" name="action" value="Editar Produto">
    </div>
  </form>
</div>