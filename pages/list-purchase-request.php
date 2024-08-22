<div class="box-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0">Lista de Solicitações</h2>

        <div class="d-flex">
            <div class="input-group me-3">
                <input class="form-control" id="input-buy-request" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
                <button class="btn btn-success" id="button-search" type="button">Buscar</button>
            </div>
            <button class="btn btn-primary">Variação de valores</button>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px;">
                <table class="table table-dark table-hover" id="table-buy-request">
                    <thead>
                        <tr style="white-space: nowrap;">
                            <th scope="col">#</th>
                            <th scope="col">Produto</th>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Mensagens</th>
                            <th scope="col">Data</th>
                        </tr>
                    </thead>
                    <tbody id="buy-request-result">
                        <!-- Conteúdo dinâmico -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
