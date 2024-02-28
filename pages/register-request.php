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
            <button type="submit" id="invoice-request" class="invoice-request right">Gerar pedido</button>
        </div>
        <div class="box-content">
            <div class="list">
                <table>

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

    <div class="box-content left w100">
        <h2>Lista de pedidos</h2>
        <div class="list">
            <table>
                <thead>

                    <tr>
                        <td>Mesa</td>
                        <td>Status</td>
                        <td>Total</td>
                        <td>Data</td>
                    </tr>

                </thead>

                <?php

                $request_dados = Controllers::SelectRequest('request');

                foreach ($request_dados as $key => $value) {

                    ?>

                    <tbody>

                        <tr>
                            <td>
                                <?php echo htmlspecialchars($value['id_table']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($value['STATUS_REQUEST']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($value['total_request']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($value['date_request']); ?>
                            </td>

                            <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                                <div>
                                    <a class="btn-edit"
                                        href="<?php echo INCLUDE_PATH ?>edit-clients?id=<?php echo base64_encode($value['id']); ?>">Adicionar mais items</a>
                                </div>
                                <div>
                                    <a class="btn-disable" href="<?php echo INCLUDE_PATH ?>">Faturar</a>
                                </div>
                                <div>
                                    <a actionBtn="delete" class="btn-delete"
                                        href="<?php echo INCLUDE_PATH ?>list-clients?delete=<?php echo $value['id']; ?>">Detalhes</a>
                                </div>
                            </td>
                        </tr>

                    </tbody>

                <?php } ?>

            </table>
        </div>
    </div>
</form>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>