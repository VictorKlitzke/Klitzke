<div class="box-content">
  <h2>Cadastrar Empresa</h2>
  <form class="form">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" id="name">
    </div>
    <div class="content-form">
      <label for="">CPNJ</label>
      <input type="text" id="cnpj" placeholder="00.000.000/0000-00" oninput="formatarCNPJ(this)" maxlength="18" name="cnpj">
    </div>
    <div class="content-form">
      <label for="">Inscrição Estadual</label>
      <input type="text" id="state_registration">
    </div>
    <div ref="cpf" class="content-form">
      <label for="">Email</label>
      <input type="text" id="email">
    </div>
    <div class="content-form">
      <label for="">Contato</label>
      <input type="text" id="phone">
    </div>
    <div class="content-form">
      <label for="">Endereco</label>
      <input type="text" id="address">
    </div>
    <div class="content-form">
      <label for="">Cidade</label>
      <input type="text" id="city">
    </div>
    <div class="content-form">
      <label for="">Estado</label>
      <input type="text" id="state">
    </div>
  </form>
  <button class="button-registers" onclick="RegisterCompany()" type="button">Cadastrar</button>
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