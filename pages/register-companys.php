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
  if ($name == '' || $state_registration == '' || $phone == '' || $cnpj == '') {
    Panel::Alert('attention', 'Os campos não podem ficar vázios!');
  } else {
    $verification = Db::Connection()->prepare("SELECT * FROM `company` WHERE name = ? AND cnpj = ? AND state_registration = ? AND email = ?
                                                      AND phone = ? AND address = ? AND city = ? AND state = ?");
    $verification->execute(
      array(
        $name = $_POST['name'],
        $_POST['cnpj'],
        $_POST['state_registration'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['city'],
        $_POST['state']
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
        'city' => $city,
        'state' => $state,
        'name_table' => 'company'
      ];
      Controllers::Insert($arr);
      Panel::Alert('sucess', 'O cadastro da empresa ' . $name . ' foi realizado com sucesso!');
    } else {
      Panel::Alert('error', 'Já existe uma empresa com este nome!');
    }
  }
}
?>

<div class="box-content">
  <h2>Cadastrar Empresa</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" name="name">
    </div>
    <div class="content-form">
      <label for="">CPNJ</label>
      <input type="text" id="cnpj" placeholder="00.000.000/0000-00" oninput="formatarCNPJ(this)" maxlength="18" name="cnpj">
    </div>
    <div class="content-form">
      <label for="">Inscrição Estadual</label>
      <input type="text" name="state_registration">
    </div>
    <div ref="cpf" class="content-form">
      <label for="">Email</label>
      <input type="text" name="email">
    </div>
    <div class="content-form">
      <label for="">Contato</label>
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
      <label for="">Estado</label>
      <input type="text" name="state">
    </div>
    <div class="content-form">
      <input type="hidden" name="name_table" value="company" />
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