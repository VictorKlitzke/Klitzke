<?php

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'register-companys';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid bg-light p-4 rounded-4 border shadow-lg">
  <h2 class="text-dark mb-3">Cadastrar Empresa</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label text-dark">Nome</label>
      <input type="text" id="name" class="form-control border-dark" placeholder="Nome" />
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">CPNJ</label>
      <input type="text" id="cnpj" placeholder="00.000.000/0000-00" maxlength="14" class="form-control border-dark" />
      <span id="cnpj-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Inscrição Estadual</label>
      <input type="text" id="state_registration" class="form-control border-dark" placeholder="Inscrição Estadual" />
      <span id="state_registration-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div ref="cpf" class="col-md-6">
      <label class="form-label text-dark">Email</label>
      <input type="text" id="email" class="form-control border-dark" placeholder="Email" />
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Contato</label>
      <input type="text" id="phone" class="form-control border-dark" placeholder="Contato" />
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Endereço</label>
      <input type="text" id="address" class="form-control border-dark" placeholder="Endereço" />
      <span id="address-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Cidade</label>
      <input type="text" id="city" class="form-control border-dark" placeholder="Cidade" />
      <span id="city-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Estado</label>
      <input type="text" id="state" class="form-control border-dark" placeholder="Estado" />
      <span id="state-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" onclick="RegisterCompany()" type="button">Cadastrar</button>
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