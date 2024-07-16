<div class="box-content">
    <h2>Abrir caixa</h2>
    <form class="form">
        <div class="content-form">
            <label for="">Valor</label>
            <input type="text" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
            <span id="value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="content-form">
            <label for="">Observação</label>
            <input type="text" id="observation">
            <span id="observation-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
    </form>
    <button class="button-registers" onclick="RegisterBoxPdv()" type="button">Abrir Caixa</button>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>

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