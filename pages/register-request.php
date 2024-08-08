<form action="">
    <div class="box-content">
        <h2 class="text-white">Escolha da mesa</h2>
        <div class="roe">
            <div class="col-sm-12">
                <div class="search-table">
                    <input class="form-control" type="text" id="search-table" name="search-table"
                        placeholder="Adicionar comanda" />
                    <ul id="result-table" style="display: none;"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <!-- Primeiro Card -->
        <div class="card bg-dark text-white me-3">
            <div class="card-body">
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <input id="number-table" placeholder="Número da Mesa"
                        style="background: transparent; border-color: #fff; color: #fff; border: none;" />
                </div>
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <h5 class="text-white d-flex justify-content-between align-items-center mb-3">Buscar Produtos
                        </h5>
                        <input class="form-control" type="text" id="product-request-search"
                            name="product-request-search" placeholder="Ex: Coca-cola">
                        <ul id="product-result-request" class="list-group mt-2"></ul>
                    </div>
                    <div class="col-12">
                        <h4 class="text-white">Características do Produto</h4>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-white" for="product-id">Códg.</label>
                        <input class="form-control" type="text" id="product-id" name="product-id">
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-white" for="product-name">Nome</label>
                        <input class="form-control" type="text" id="product-name" name="product-name">
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-white" for="product-stock_quantity">Qntd.</label>
                        <input class="form-control" type="text" id="product-stock_quantity"
                            name="product-stock_quantity">
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-white" for="product-value">Valor</label>
                        <input class="form-control" type="text" id="product-value" name="product-value">
                    </div>
                    <div class="col-12">
                        <button id="button-request" type="button" class="btn btn-primary w-100">Adicionar pedido</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Segundo Card -->
        <div id="card-request-finallize" class="card bg-dark text-white ms-3 flex-fill">
            <div class="card-body">
                <div class="request-list">
                    <h2>Itens do pedido</h2>
                    <button id="add-card-item" type="button" class="btn btn-outline-light mb-3">Gerar card de
                        pedido</button>
                </div>
                <div class="box-content">
                    <div class="list">
                        <table class="table table-dark table-striped-columns">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>
                                        <p>Qtd.</p>
                                    </th>
                                    <th>
                                        <p>Valor</p>
                                    </th>
                                    <th>Comanda</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-request">
                                <tr>
                                    <!-- Conteúdo -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h2 id="totalizador-request" class="right mt-3"></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay-invo" id="overlay-invo">
        <div class="modal-invo" id="modal-invo">
            <div class="navbar-invo">
                <h2>Fechar pedido</h2>
                <svg id="modal-invo-close" style="cursor: pointer;" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                    height="24px" viewBox="0 0 24 24" width="24px">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </div>
            <div class="modal-content">
                <div id="orderDetails"></div>
                <div class="button-forms-invo">
                    <?php
                    $forms_payments = Controllers::SelectAllFormPayment("form_payment");
                    foreach ($forms_payments as $key => $value) {
                        ?>
                        <button type="button" class="Invo-forms"
                            data-payment-id="<?php echo $value['id']; ?>"><?php echo $value['forms_payment']; ?></button>
                    <?php } ?>
                    <button onclick="CloseInvo()" class="right Invo-Fat" id="Invo-Fat" type="button">Faturar</button>
                </div>
            </div>
        </div>
    </div>

</form>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var searchTable = document.getElementById("search-table");
        var resultTable = document.getElementById("result-table");

        // Adicionamos um evento de entrada (input) ao campo de entrada
        searchTable.addEventListener("input", function () {
            // Verificamos se há algum valor no campo de entrada
            if (searchTable.value.trim() !== "") {
                // Exibimos o #result-table
                resultTable.style.display = "block";
            } else {
                // Ocultamos o #result-table
                resultTable.style.display = "none";
            }
        });

        // Adicionamos um evento de clique fora do campo de entrada para ocultar o #result-table
        document.addEventListener("click", function (event) {
            // Verificamos se o clique não foi dentro do #result-table ou #search-table
            if (event.target !== resultTable && event.target !== searchTable) {
                // Ocultamos o #result-table
                resultTable.style.display = "none";
            }
        });
    });


</script>