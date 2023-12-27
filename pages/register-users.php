<?php

if (isset($_POST['action'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $phone = $_POST['phone'];
  $function = $_POST['function'];
  $commission = $_POST['commission'];
  $target_commission = $_POST['target_commission'];
  if ($name == '' || $password == '' || $target_commission == '' || $commission == '') {
    Panel::Alert('attention', 'Os campos não podem ficar vázios!');
  } else {
    $verification = Db::Connection()->prepare("SELECT * FROM `users` WHERE name = ? AND email = ? AND password = ? AND function = ? AND phone = ? AND commission = ? AND target_commission = ?");
    $verification->execute(array($_POST['name'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['function'], $_POST['commission'], $_POST['target_commission']));
    if ($verification->rowCount() == 0) {

      $arr = ['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'function' => $function, 'commission' => $commission, 'target_commission' => $target_commission, 'name_table' => 'users'];
      Controllers::Insert($arr);
      Panel::Alert('sucess', $name . ' foi cadastrado com sucesso!');
    } else {
      Panel::Alert('error', 'Já existe uma usuario com este nome!');
    }
  }

}
?>


<div class="box-content">
  <h2>Cadastrar Usuario</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" name="name">
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="text" name="email">
    </div>
    <div class="content-form">
      <label for="">Senha</label>
      <input type="password" name="password">
    </div>
    <div class="content-form">
      <label for="">Função</label>
      <input type="text" name="function">
    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="text" name="phone">
    </div>
    <div class="content-form">
      <label for="">Comissao</label>
      <input type="number" name="commission">
    </div>
    <div class="content-form">
      <label for="">Commisao por meta</label>
      <input type="number" name="target_commission">
    </div>
    <div class="content-form">
      <input type="hidden" name="nome_tabela" value="users" />
      <input type="submit" name="action" value="Cadastrar">
    </div>
  </form>
</div>