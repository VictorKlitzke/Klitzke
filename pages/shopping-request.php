<div class="container-fluid p-4 shadow-lg border bg-light rounded-4">
    <div class="d-flex justify-content-between">
        <h2 class="text-dark">Lista de Produtos Negativados</h2>
        <button class="btn btn-primary" onclick="GoRequest()">Iniciar Solicitação</button>
    </div>
    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm"
                    id="table-product">
                    <thead class="table-dark text-light">
                        <tr style="white-space: nowrap;">
                            <th scope="col">#</th>
                            <th scope="col">Produto</th>
                            <th scope="col">Quantidade em estoque</th>
                            <th scope="col">Valor do Produto</th>
                            <th scope="col">Status</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container-fluid p-4 shadow-lg border bg-light rounded-4" id="go-request" style="display: none;">
    <div class="d-flex justify-content-between">
        <h2 class="text-dark">Solicitar Compra</h2>
        <button id="send-request-products" onclick="toggleFornModal()" class="btn btn-info">Selecionar
            Fornecedor
        </button>
    </div>
    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
                    <thead class="table-dark text-light">
                        <tr style="white-space: nowrap;">
                            <th scope="col">Produto</th>
                            <th scope="col">Quantidade a Mandar</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody id="selected-products-list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-forn" class="modal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-dark text-dark">
                <h5 class="modal-title" id="selectSupplierLabel">Selecionar Fornecedor</h5>
                <button style="background: #fff; border-radius: 20%; padding-top: 3px;;" type="button"
                    onclick="toggleFornModal()" class="btn btn-close" data-bs-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid p-4 shadow-lg border bg-light rounded-4">
                    <div class="row">
                        <div class="col">
                            <h2>Selecionar Fornecedor</h2>
                            <div class="table-responsive">
                                <table
                                    class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm"
                                    id="table-forn">
                                    <thead>
                                        <tr style="white-space: nowrap;">
                                            <th id="selected-forn" scope="col">Selecionar</th>
                                            <th scope="col">#</th>
                                            <th scope="col">Fornecedor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="forn-list">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="SendRequestEmail()"
                    id="confirmSupplierSelection">Enviar pelo e-mail</button>
            </div>
        </div>
    </div>
</div>