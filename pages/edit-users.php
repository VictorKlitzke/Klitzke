<?php 

  if(isset($_GET['id'])  ){
    $id = (int)base64_decode($_GET['id']);
    $update = Controllers::Select('users','id=?', array($id));
  }else{
		die();
	}

  if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
  }
  $page_permission = 'edit-users';
  if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
  }
  
?>

<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
  <h2 class="text-dark mt-4">Editar Usuário</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-dark">Nome</label>
      <input type="hidden" id="id_user" value="<?php echo base64_encode($update['id']); ?>" />
      <input type="text" class="form-control border-dark" id="name" value="<?php echo $update['name']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Email</label>
      <input type="text" class="form-control border-dark" id="email" value="<?php echo $update['email']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Login</label>
      <input type="text" class="form-control border-dark" id="login" value="<?php echo $update['name']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Contato</label>
      <input type="text" id="phone" class="form-control border-dark" value="<?php echo $update['phone']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Função</label>
      <input type="text" id="function" class="form-control border-dark" value="<?php echo $update['function']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-dark">Comissão</label>
      <input type="text" id="commission" class="form-control border-dark" value="<?php echo $update['commission']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-12">
      <label class="text-dark">Comissão por venda</label>
      <input type="text" id="target_commission" class="form-control border-dark" value="<?php echo $update['target_commission']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="button" onclick="EditUsers()">Editar Usuário</button>
    </div>
  </div>
</div>