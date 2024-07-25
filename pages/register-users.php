<div class="box-content">
  <h2 class="text-white mb-4">Cadastrar Usuario</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="text-white" for="">Nome</label>
      <div class="input-group">
        <div class="input-group-text">@</div>
        <input type="text" id="name" id="specificSizeInputGroupUsername" class="form-control">
      </div>
      <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="text-white" for="">Email</label>
      <input type="text" id="email" class="form-control">
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-12">
      <label class="text-white" for="">Senha</label>
      <input type="password" id="password" class="form-control">
      <span id="password-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-6">
      <label class="text-white" for="">Função</label>
      <input type="text" id="function" class="form-control">
      <span id="function-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="col-md-6">
      <label class="text-white" for="">Telefone</label>
      <input type="text" id="phone" class="form-control">
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-4">
      <label class="text-white" for="">Comissao</label>
      <input type="number" id="commission" class="form-control">
      <span id="commission-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-4">
      <label class="text-white" for="">Commisao por meta</label>
      <input type="number" id="target_commission" class="form-control">
      <span id="target_commission-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="col-md-4">
      <label class="text-white" for="">Nivel de acesso</label>
      <select id="access" class="form-select form-select-sm">
        <option value="100">Administrador</option>
        <option value="50">Moderado</option>
        <option value="10">Padrão</option>
      </select>
      <span id="access-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="co-12">
      <button onclick="RegisterUsers()" class="btn btn-primary" type="button">Cadastrar</button>
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