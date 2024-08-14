<div class="box-content">
    <div class="d-flex justify-content-between">
        <h2 class="text-white">Lista de Produtos Negativados</h2>
        <button class="btn btn-primary" onclick="GoRequest()">Iniciar Solicitação</button>
    </div>
    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-dark table-hover" id="table-product">
                    <thead>
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

<div class="box-content" id="go-request" style="display: none;">
    <h2 class="text-white">Solicitar Compra</h2>
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-dark table-hover">
                    <thead>
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
        <button id="send-request-products" onclick="Selectedforns()" class="btn btn-info">Selecionar Fornecedor</button>
    </div>
</div>

<div class="overlay-forn" id="overlay-forn">
    <div id="modal-forn" class="modal" tabindex="-1" aria-labelledby="selectSupplierLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="selectSupplierLabel">Selecionar Fornecedor</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="box-content">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover" id="table-forn" border="4">
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
                    <button type="button" class="btn btn-success" onclick="SendRequestWhatsApp()">Enviar pelo WhatsApp</button>
                    <button type="button" class="btn btn-primary" id="confirmSupplierSelection">Enviar pelo e-mail</button>
                </div>
            </div>
        </div>
    </div>
</div>