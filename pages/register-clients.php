
<div class="box-content">
  <h2>Cadastrar Clientes</h2>
  <form class="form">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" id="name">
      <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="email" id="email">
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Nome Social</label>
      <input type="text" id="social_reason">
      <span id="social_reason-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div ref="cpf" class="content-form">
      <label for="">CPF</label>
      <input type="text" id="cpf" maxlength="14" placeholder="000.000.000-00">
      <span id="cpf-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="number" id="phone">
      <span id="phone-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Endereco</label>
      <input type="text" id="address">
      <span id="address-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">Cidade</label>
      <input type="text" id="city">
      <span id="city-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">CEP</label>
      <input type="number" id="cep">
      <span id="cep-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Bairro</label>
      <input type="text" id="neighborhood">
      <span id="neighborhood-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
  </form>
    <button class="button-registers" onclick="RegisterClients()" type="button">Cadastrar</button>
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