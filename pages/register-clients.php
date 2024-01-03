<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['action'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $social_reason = $_POST['social_reason'];
  $cep = $_POST['cep'];
  $phone = $_POST['phone'];
  $city = $_POST['city'];
  $address = $_POST['address'];
  $cpf = preg_replace("/[^0-9]/", "", $_POST['cpf']);
  $neighborhood = $_POST['neighborhood'];
  if ($name == '' || $social_reason == '' || $phone == '' || $cpf == '') {
    Panel::Alert('attention', 'Os campos não podem ficar vázios!');
  } else {
    $verification = Db::Connection()->prepare("SELECT * FROM `clients` WHERE name = ? AND email = ? AND social_reason = ? 
                                              AND phone = ? AND cep = ? AND city = ? AND address = ? AND cpf = ? AND neighborhood = ?");
    $verification->execute(
      array(
        $_POST['name'],
        $_POST['email'],
        $_POST['social_reason'],
        $_POST['cep'],
        $_POST['phone'],
        $_POST['city'],
        $_POST['address'],
        $_POST['cpf'],
        $_POST['neighborhood']
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
        'name_table' => 'clients'
      ];
      Controllers::Insert($arr);
      Panel::Alert('sucess', 'O cadastro do cliente ' . $name . ' foi realizado com sucesso!');
    } else {
      Panel::Alert('attention', 'Já existe um cliente com este nome!');
    }
  }
}
?>

<div class="box-content">
  <h2>Cadastrar Clientes</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" name="name">
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="email" name="email">
    </div>
    <div class="content-form">
      <label for="">Nome Social</label>
      <input type="text" name="social_reason">
    </div>
    <div ref="cpf" class="content-form">
      <label for="">CPF</label>
      <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00">
    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="text" name="phone">
    </div>
    <div class="content-form">
      <label for="">Endereco</label>
      <input type="text" name="address">
    </div>
    <div class="content-form">
      <label for="">Cidade</label>
      <input type="text" name="city">
    </div>
    <div class="content-form">
      <label for="">CEP</label>
      <input type="text" name="cep">
    </div>
    <div class="content-form">
      <label for="">Bairro</label>
      <input type="text" name="neighborhood">
    </div>
    <div class="content-form">
      <input type="hidden" name="name_table" value="clients" />
      <input type="submit" name="action" value="Cadastrar">
    </div>
  </form>
</div>  

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('.form');

        form.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); 

                var currentInput = event.target;
                var formElements = form.elements;
                var currentIndex = Array.from(formElements).indexOf(currentInput);

                if (currentIndex < formElements.length - 1) {
                    formElements[currentIndex + 1].focus();
                }
            }
        });
    });
</script>