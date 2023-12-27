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
  if ($company == '' || $phone == '' || $cnpjcpf == '') {
    Panel::Alert('attention', 'Os campos não podem ficar vázios!');
  } else {
    $verification = Db::Connection()->prepare("SELECT * FROM `suppliers` WHERE company = ? AND fantasy_name = ? AND email = ? 
                                                      AND phone = ? AND address = ? AND city = ? AND state = ? AND cnpjcpf = ?");
    $verification->execute(
      array(
        $_POST['company'],
        $fantasy_name = $_POST['fantasy_name'],
        $email = $_POST['email'],
        $phone = $_POST['phone'],
        $address = $_POST['address'],
        $city = $_POST['city'],
        $state = $_POST['state'],
        $cnpjcpf = $_POST['cnpjcpf']
      )
    );
    if ($verification->rowCount() == 0) {
      $arr = [
        'company' => $company,
        'fantasy_name' => $fantasy_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'cnpjcpf' => $cnpjcpf,
        'name_table' => 'suppliers'
      ];
      Controllers::Insert($arr);
      Panel::Alert('sucess', 'O cadastro do fornecedor ' . $company . ' foi realizado com sucesso!');
    } else {
      Panel::Alert('attention', 'Já existe um fornecedor com este nome!');
    }
  }

}
?>

<div class="box-content">
  <h2>Cadastrar Fornecedor</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Fornecedor</label>
      <input type="text" name="company">
    </div>
    <div class="content-form">
      <label for="">Nome Fantasia</label>
      <input type="text" name="fantasy_name">
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="email" name="email">
    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="number" name="phone">
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
      <label for="">CNPJ</label>
      <input type="text" id="cnpj" placeholder="00.000.000/0000-00" oninput="formatarCNPJ(this)" maxlength="18" name="cnpjcpf">
    </div>
    <div class="content-form">
      <input type="hidden" name="name_table" value="suppliers" />
      <input type="submit" name="action" value="Cadastrar">
    </div>
  </form>
</div>