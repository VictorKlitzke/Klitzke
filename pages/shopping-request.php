<div class="box-content">
    <div style="display: flex; justify-content: space-between;">
        <h2>Lista de Produtos Negativados</h2>
        <button onclick="GoRequest()">Iniciar Solicitação</button>
    </div>
    <div class="list">
        <table id="table-product" border="4">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade em estoque</th>
                    <th>Valor do Produto</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody id="product-list">
            </tbody>
        </table>
    </div>
</div>

<div class="box-content" id="go-request" style="display: none;">
    <h2>Solicitar Compra</h2>
    <div class="list">
        <table border="4">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade a Mandar</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody id="selected-products-list">
            </tbody>
        </table>
        <button class="right">Enviar Solicitação</button>
    </div>
</div>