<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
    <h2 class="text-dark mb-4">Abertura do Caixa</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="text-dark form-label">Valor</label>
            <input class="form-control border-dark" type="text" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
            <span id="value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-6">
            <label class="text-dark form-label">Observação</label>
            <input class="form-control border-dark" type="text" id="observation" placeholder="Observação">
            <span id="observation-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" onclick="RegisterBoxPdv()" type="button">Abrir Caixa</button>
        </div>
    </div>
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