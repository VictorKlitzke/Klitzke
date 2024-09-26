<div class="box-content">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label text-white">Numero Pix</label>
            <input type="text" id="pix" class="form-control" placeholder="Numero Pix">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-6">
            <label class="form-label text-white">Nome do titular da Conta</label>
            <input type="text" id="name_holder" class="form-control" placeholder="Nome do titular">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Numero da Conta</label>
            <input type="text" id="account_number" class="form-control" placeholder="Nome do titular">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Banco</label>
            <input type="text" id="bank" class="form-control" placeholder="Banco">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-white">Agencia</label>
            <input type="text" id="agency" class="form-control" placeholder="Agencia">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-12">
            <label class="text-white" for="">Tipo da Conta Bancária</label>
            <select id="type-account" class="form-control">
                <option value="1">Conta Corrente</option>
                <option value="2">Conta Poupança</option>
            </select>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" onclick="RegisterAccount()" type="button">Cadastrar</button>
        </div>
    </div>
</div>