
<div class="box-content">
  <h2>Cadastrar Usuario</h2>
  <form class="form">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" id="name">
      <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="text" id="email">
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Senha</label>
      <input type="password" id="password">
      <span id="password-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Função</label>
      <input type="text" id="function">
      <span id="function-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="text" id="phone">
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Comissao</label>
      <input type="number" id="commission">
      <span id="commission-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Commisao por meta</label>
      <input type="number" id="target_commission">
      <span id="target_commission-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Nivel de acesso</label>
      <select id="access">
          <option value="100">Administrador</option>
          <option value="50">Moderado</option>
          <option value="10">Padrão</option>
      </select>
      <span id="access-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
  </form>
  <button onclick="RegisterUsers()" class="button-registers" type="button">Cadastrar</button>
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