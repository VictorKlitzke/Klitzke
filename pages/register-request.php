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
                <ul id="result-table"></ul>
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
            <h2>Items do pedido</h2>
            <button type="submit" id="invoice-request" class="invoice-request right">Gerar pedido</button>
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
                            <td>Mesa</td>
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

</form>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>
