
<div class="box-content">
  <h2>Cadastrar Usuario</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" name="name" id="name">
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="text" name="email" id="email">
    </div>
    <div class="content-form">
      <label for="">Senha</label>
      <input type="password" name="password" id="password">
    </div>
    <div class="content-form">
      <label for="">Função</label>
      <input type="text" name="function" id="function">
    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="text" name="phone" id="phone">
    </div>
    <div class="content-form">
      <label for="">Comissao</label>
      <input type="number" name="commission" id="commission">
    </div>
    <div class="content-form">
      <label for="">Commisao por meta</label>
      <input type="number" name="target_commission" id="target_commission">
    </div>
    <div class="content-form">
      <label for="">Nivel de acesso</label>
      <select name="access" id="access">
          <option value="100">Administrador</option>
          <option value="50">Moderado</option>
          <option value="10">Padrão</option>
      </select>
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