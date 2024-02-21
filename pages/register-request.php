<form action="">
    <div class="card-request left w40">
        <div class="h2-request">
            <h2>Pedidos</h2>
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
            </div>
            <div class="totalizador">
                <h2>Valor</h2>
                <p id="product-value">R$ 0,00</p>
            </div>
            <button type="button" class="button-request">Adicionar pedido</button>
        </div>
    </div>

    <div class="card-request-finallize right w40">
        <div class="request-list">
            <h2>Items do pedido</h2>
        </div>
        <div class="box-content">
            <div class="list">
                <table>

                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Nome.</td>
                            <td>
                                <p>Qntd.</p>
                            </td>
                            <td>
                                <p>Valor</p>
                            </td>
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
        <button type="button" class="invoice-request right">Faturar</button>
    </div>

    <div class="box-content left w100">
        <h2>Lista de pedidos</h2>
        <div class="list">
            <table>
                <thead>
                    <tr>
                        <td></td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>