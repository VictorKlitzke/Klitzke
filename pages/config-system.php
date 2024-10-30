<div class="container-fluid p-4 bg-light shadow-lg border rounded-4">
    <div class="row g-3">
        <h2 class="text-dark">Porcetagem do Cálculo por Produtos</h2>
        <div class="col-sm-12 mb-3">
            <label class="text-dark" for="multiply">Número a ser multiplicado</label>
            <input class="form-control border-dark" id="multiply" type="text"
                placeholder="Digite o número">
            <span id="multiply-error" class="text-warning mt-1" style="font-size: 0.9rem; display: none;">
                Campo está inválido, ajuste se possível.
            </span>
        </div>
        <div class="col-12 d-flex justify-content-start">
            <button class="btn btn-primary px-4 py-2 shadow-sm"
                onclick="RegisterMultiply()" type="button" style="transition: 0.3s;">
                Fazer Retirada
            </button>
        </div>
    </div>
</div>

<br>

<div class="container-fluid p-4 bg-light shadow-lg rounded-4 border">
    <div class="row g-3">
        <h2 class="text-dark mt-4">Cadastrar conta bancaria</h2>
        <div class="col-md-6">
            <label class="form-label text-dark">Numero Pix</label>
            <input type="text" id="pix" class="form-control border-dark" placeholder="Numero Pix">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-6">
            <label class="form-label text-dark">Nome do titular da Conta</label>
            <input type="text" id="name_holder" class="form-control border-dark" placeholder="Nome do titular">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Numero da Conta</label>
            <input type="text" id="account_number" class="form-control border-dark" placeholder="Nome do titular">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Banco</label>
            <input type="text" id="bank" class="form-control border-dark" placeholder="Banco">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-4">
            <label class="form-label text-dark">Agencia</label>
            <input type="text" id="agency" class="form-control border-dark" placeholder="Agencia">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-md-12 mb-3">
            <label class="text-dark" for="">Tipo da Conta Bancária</label>
            <select id="type-account" class="form-control border-dark">
                <option value="1">Conta Corrente</option>
                <option value="2">Conta Poupança</option>
            </select>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12 d-flex justify-content-start">
            <button class="btn btn-primary px-4 py-2 shadow-sm"
                onclick="RegisterAccount()" type="button">Cadastrar
            </button>
        </div>
    </div>
</div>