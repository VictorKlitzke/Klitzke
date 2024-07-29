<div class="box-content">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label text-white">Numero Pix</label>
            <input type="text" id="pix" class="form-control" placeholder="Numero Pix">
            <span id="pix-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Nome do titular</label>
            <input type="text" id="name_holder" class="form-control" placeholder="Nome do titular">
            <span id="name-holder-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Cidade</label>
            <input type="text" id="city" class="form-control" placeholder="Cidade">
            <span id="city-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" onclick="RegisterAccount()" type="button">Cadastrar</button>
        </div>
    </div>
</div>