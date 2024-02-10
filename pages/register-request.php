<form action="">
    <div class="card-request left w40">
        <div class="h2-request">
            <h2>Pedidos</h2>
        </div>
        <div class="card-container">
            <div class="search-product-request">
                <h4>Buscar Produtos</h4>
                <input type="text" id="product-search" name="product_search" placeholder="Ex: Coca-cola">
                <ul id="product-result"></ul>
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
            </div>
            <div class="totalizador">
                <h2>Total</h2>
                <p>1200</p>
            </div>
            <button class="button-request">Adicionar pedido</button>
        </div>
    </div>

    <div class="card-request-finallize right w40">
        <div class="request-list">
            <h2>Lista de pedidos</h2>
        </div>
        <div class="box-content">
            <div class="list">
                <table>
                    <thead>
                        <tr>
                            <td>Cod.</td>
                            <td>Nome.</td>
                            <td>Quantidade</td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="box-prepare left w100">
    </div>
</form>

<script  src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>