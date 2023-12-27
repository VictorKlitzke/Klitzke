<?php 

  if(isset($_GET['id'])  ){
    $id = (int)base64_decode($_GET['id']);
    $update = Controllers::Select('users','id=?', array($id));
  }else{
		Panel::alert('error','Você precisa passar o parametro ID.');
		die();
	}

?>


<?php
if (isset($_POST['action'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $login = $_POST['login'];
  $phone = $_POST['phone'];
  $function = $_POST['function'];
  $commission = $_POST['commission'];
  $target_commission = $_POST['target_commission'];

  $verification = Db::Connection()->prepare("SELECT * FROM `users` WHERE name = ? AND email = ? AND password = ? 
                                            AND login = ? AND phone = ? AND function = ? AND commission = ? AND target_commission = ? AND id != ?");
  $verification->execute(
    array(
      $_POST['name'],
      $_POST['email'],
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT),
      $login = $_POST['login'],
      $phone = $_POST['phone'],
      $function = $_POST['function'],
      $commission = $_POST['commission'],
      $target_commission = $_POST['target_commission'],
      $id
    )
  );
  if ($verification->rowCount() == 0) {
    $arr = ['name' => $name, 'email' => $email, 'password' => $password, 'function' => $function, 'id' => $id, 'name_table' => 'users'];
    Controllers::Update($arr);
    $update = Controllers::Select('users','id=?', array($id));
    header('Location: ' . INCLUDE_PATH . 'list-users');
  } else {
    Panel::alert('error', 'Não foi possível alterar o usuário');
  }
}

?>

<div class="box-content">
  <h2>Editar Usuário</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Nome</label>
      <input type="text" name="name" value="<?php echo $update['name']; ?>" />
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="text" name="email" value="<?php echo $update['email']; ?>" />
    </div>
    <div class="form-group">
      <label>Senha</label>
      <input type="password" name="password" value="">
    </div>
    <div class="form-group">
      <label>Login</label>
      <input type="text" name="login" value="<?php echo $update['login']; ?>" />
    </div>
    <div class="form-group">
      <label>Contato</label>
      <input type="text" name="phone" value="<?php echo $update['phone']; ?>" />
    </div>
    <div class="form-group">
      <label>Função</label>
      <input type="text" name="function" value="<?php echo $update['function']; ?>" />
    </div>
    <div class="form-group">
      <label>Comissão</label>
      <input type="text" name="commission" value="<?php echo $update['commission']; ?>" />
    </div>
    <div class="form-group">
      <label>Comissão por venda</label>
      <input type="text" name="target_commission" value="<?php echo $update['target_commission']; ?>" />
    </div>
    <div class="form-group">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="name_table" value="users">
    </div>
    <div class="form-group">
      <input type="submit" name="action" value="Editar Usuário">
    </div>
  </form>
</div>