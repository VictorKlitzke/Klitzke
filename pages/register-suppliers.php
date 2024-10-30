<?php

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'register-suppliers';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
  <h2 class="text-dark mb-4">Cadastrar Fornecedor</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label text-dark">Fornecedor</label>
      <input class="form-control border-dark" type="text" id="name_company" placeholder="Fornecedor">
      <span id="company-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Nome Fantasia</label>
      <input class="form-control border-dark" type="text" id="fantasy_name" placeholder="Nome Fantasia">
      <span id="fantasy_name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Email</label>
      <input class="form-control border-dark" type="email" id="email" placeholder="Email">
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Telefone</label>
      <input class="form-control border-dark text-dark" type="text" id="phone" placeholder="Telefone" />
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Endereco</label>
      <input class="form-control border-dark" type="text" id="address" placeholder="Endereço">
      <span id="address-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Cidade</label>
      <input class="form-control border-dark" type="text" id="city" placeholder="Cidade">
      <span id="city-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Estado</label>
      <input class="form-control border-dark" type="text" id="state" placeholder="Estado">
      <span id="state-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">CNPJ</label>
      <input class="form-control border-dark text-dark" type="text" id="cnpj" placeholder="00.000.000/0000-00" maxlength="14">
      <span id="cnpj-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" onclick="RegisterForn()" type="button">Cadastrar</button>
    </div>
  </div>
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