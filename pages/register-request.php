<?php

if (isset($_GET["delete"])) {
    $del = intval($_GET["delete"]);
    Controllers::Delete("request", $del);
    header("Location: " . INCLUDE_PATH . "register-request");
}

$currentPage = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$porPage = 20;

$request = Controllers::SelectAll(
    "request",
    ($currentPage - 1) * $porPage,
    $porPage
);
?>

<form action="">
    <div class="table-request w100">
        <div class="box-content">
            <h2>Escolha da mesa</h2>
            <div class="search-table">
                <br />
                <input type="text" id="search-table" name="search-table" placeholder="Adicionar comanda" />
                <ul id="result-table" style="display: none;"></ul>
            </div>

        </div>
    </div>
    <div class="card-request left w40">
        <div class="request-list">
            <h2>Pedidos</h2>
            <input id="number-table" class="table-number right" />
        </div>
        <div class="card-container">
            <div class="search-product-request">
                <h4>Buscar Produtos</h4>
                <input type="text" id="product-request-search" name="product-request-search"
                    placeholder="Ex: Coca-cola">
                <ul id="product-result-request"></ul>
            </div>
            <div class="caracteres">
                <h4>Características do Produto</h4>
                <div class="form-request">
                    <label for="product-id">Códg.</label>
                    <input type="text" id="product-id" name="product-id">
                </div>
                <div class="form-request">
                    <label for="product-name">Nome</label>
                    <input type="text" id="product-name" name="product-name">
                </div>
                <div class="form-request">
                    <label for="product-quantity">Qntd.</label>
                    <input type="text" id="product-stock_quantity" name="product-stock_quantity">
                </div>
                <div class="form-request">
                    <label for="product-quantity">Valor</label>
                    <input type="text" id="product-value" name="product-value">
                </div>
            </div>
            <button type="button" class="button-request">Adicionar pedido</button>
        </div>
    </div>

    <div class="card-request-finallize right w40">
        <div class="request-list">
            <h2>Itens do pedido</h2>
            <button id="add-card-item" type="button" class="btn-add-card-item right">Gerar card de pedido</button>
        </div>
        <div class="box-content">
            <div class="list">
                <table class="table table-striped-columns">

                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Nome</td>
                            <td>
                                <p>Qtd.</p>
                            </td>
                            <td>
                                <p>Valor</p>
                            </td>
                            <td>Comanda</td>
                        </tr>
                    </thead>
                    <tbody class="tbody-request">
                        <tr>

                        </tr>
                    </tbody>

                </table>
            </div>
            <h2 id="totalizador-request" class="right"></h2>
        </div>
    </div>

    <div id="error-container-request"
        style="color: black; display: none; background: #f75353; display: none; align-items: center; justify-content: center; padding: 20px; top: 50%; left: 50%; width: 100%;">
        <span id="error-message-request"></span>
    </div>

    <div id="success-container-request"
        style="color: black; display: none; background: green; display: none; align-items: center; justify-content: center; padding: 20px; top: 50%; left: 50%; width: 100%;">
        <span id="success-message-request"></span>
    </div>
    <div id="error-container-request" class="error-container-hidden"
        style="color: black; background: #f75353; display: none; align-items: center; justify-content: center; padding: 20px; top: 50%; left: 50%; width: 100%;">
        <span id="error-message-request"></span>
    </div>

    <div id="success-container-request" class="success-container-hidden"
        style="color: black; background: green; display: none; align-items: center; justify-content: center; padding: 20px; top: 50%; left: 50%; width: 100%;">
        <span id="success-message-request"></span>
    </div>

    <div class="overlay-invo" id="overlay-invo">
        <div class="modal-invo" id="modal-invo">
            <div class="navbar-invo">
                <h2>Fechar pedido</h2>
                <svg id="modal-invo-close" style="cursor: pointer;" fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24px"
                     viewBox="0 0 24 24" width="24px">
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
                        <button type="button" class="Invo-forms" data-payment-id="<?php echo $value['id']; ?>"><?php echo $value['forms_payment']; ?></button>
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
