<div class="box-content">
  <h2>Cadastrar Fornecedor</h2>
  <form class="form">
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
      <input type="text" name="phone">
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
    <button class="button-registers" onclick="RegisterForn()" type="button">Cadastrar</button>
  </form>
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