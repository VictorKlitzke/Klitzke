<div class="box-content">
  <h2 class="text-white mb-3">Cadastrar Empresa</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label text-white">Nome</label>
      <input type="text" id="name" class="form-control" placeholder="Nome"/>
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-white">CPNJ</label>
      <input type="text" id="cnpj" placeholder="00.000.000/0000-00" maxlength="14" class="form-control" />
      <span id="cnpj-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-white">Inscrição Estadual</label>
      <input type="text" id="state_registration" class="form-control" placeholder="Inscrição Estadual"/>
      <span id="state_registration-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div ref="cpf" class="col-md-6">
      <label class="form-label text-white">Email</label>
      <input type="text" id="email" class="form-control" placeholder="Email"/>
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-white">Contato</label>
      <input type="text" id="phone" class="form-control" placeholder="Contato"/>
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-white">Endereço</label>
      <input type="text" id="address" class="form-control" placeholder="Endereço"/>
      <span id="address-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-white">Cidade</label>
      <input type="text" id="city" class="form-control" placeholder="Cidade"/>
      <span id="city-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="form-label text-white">Estado</label>
      <input type="text" id="state" class="form-control" placeholder="Estado"/>
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