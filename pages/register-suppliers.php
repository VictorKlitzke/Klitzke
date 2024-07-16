<div class="box-content">
  <h2>Cadastrar Fornecedor</h2>
  <form class="form">
    <div class="content-form">
      <label for="">Fornecedor</label>
      <input type="text" id="name_company">
      <span id="company-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Nome Fantasia</label>
      <input type="text" id="fantasy_name">
      <span id="fantasy_name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="email" id="email">
      <span id="email-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

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
      <label for="">Estado</label>
      <input type="text" id="state">
      <span id="state-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
    <div class="content-form">
      <label for="">CNPJ</label>
      <input type="text" id="cnpj" placeholder="00.000.000/0000-00" maxlength="14">
      <span id="cnpj-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>

    </div>
  </form>
  <button class="button-registers" onclick="RegisterForn()" type="button">Cadastrar</button>
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