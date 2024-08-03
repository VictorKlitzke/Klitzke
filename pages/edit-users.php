<?php 

  if(isset($_GET['id'])  ){
    $id = (int)base64_decode($_GET['id']);
    $update = Controllers::Select('users','id=?', array($id));
  }else{
		Panel::alert('error','Você precisa passar o parametro ID.');
		die();
	}

?>

<div class="box-content">
  <h2 class="text-white mt-4">Editar Usuário</h2>
  <div class="row g-3">
    <div class="col-sm-6">
      <label class="text-white">Nome</label>
      <input type="text" class="form-control" id="name" value="<?php echo $update['name']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Email</label>
      <input type="text" class="form-control" id="email" value="<?php echo $update['email']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Login</label>
      <input type="text" class="form-control" id="login" value="<?php echo $update['login']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Contato</label>
      <input type="text" id="phone" class="form-control" value="<?php echo $update['phone']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Função</label>
      <input type="text" id="function" class="form-control" value="<?php echo $update['function']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-6">
      <label class="text-white">Comissão</label>
      <input type="text" id="commission" class="form-control" value="<?php echo $update['commission']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-sm-12">
      <label class="text-white">Comissão por venda</label>
      <input type="text" id="target_commission" class="form-control" value="<?php echo $update['target_commission']; ?>" />
      <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="button" onclick="EditUsers()">Editar Usuário</button>
    </div>
  </div>
</div>