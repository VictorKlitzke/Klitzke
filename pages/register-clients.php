<?php

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'register-clients';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid shadow-lg p-4 border rounded-4 bg-light">
  <h2 class="text-dark mb-4">Cadastrar Clientes</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label text-dark">Nome</label>
      <input type="text" id="name" class="form-control border-dark" placeholder="Nome" />
      <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Email</label>
      <input type="email" id="email" class="form-control border-dark" placeholder="Email" />
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Nome Social</label>
      <input type="text" id="social_reason" class="form-control border-dark" placeholder="Nome Social" />
      <span id="social_reason-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div ref="cpf" class="col-md-6">
      <label class="form-label text-dark">CPF</label>
      <input type="text" id="cpf" maxlength="14" placeholder="000.000.000-00" class="form-control border-dark" />
      <span id="cpf-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Telefone</label>
      <input type="text" id="phone" class="form-control border-dark" placeholder="Telefone" />
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-dark">Endereco</label>
      <input type="text" id="address" class="form-control border-dark" placeholder="Endereço" />
      <span id="address-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-4">
      <label class="form-label text-dark">Cidade</label>
      <input type="text" id="city" class="form-control border-dark" placeholder="Cidade" />
      <span id="city-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-4">
      <label class="form-label text-dark">CEP</label>
      <input type="text" id="cep" class="form-control border-dark" placeholder="CEP" />
      <span id="cep-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-4">
      <label class="form-label text-dark">Bairro</label>
      <input type="text" id="neighborhood" class="form-control border-dark" placeholder="Bairro" />
      <span id="neighborhood-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" onclick="RegisterClients()" type="button">Cadastrar</button>
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