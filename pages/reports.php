<div class="dasbord-global">
    <div class="card-global">
        <h2 class="h2-request">Relatorio de vendas</h2>
        <div class="global">
            <button onclick="ModalReports()" class="button-global">Imprimir</button>
        </div>
    </div>
</div>

<div class="overlay-details-reports" id="overlay-details-reports">
    <div id="modal-print-reports" class="modal-reports">
        <div class="modal-content-details-reports" id="modal-content-details-reports">
            <span class="close-details-reports" onclick="closeModalReports()"
                  id="close-details-reports">&times;</span>
            <h1>Venda ID: <span id="reportsID"></span></h1>
            <table id="modalTable-reports" border="1">
                <thead>
                <tr>
                    <th>Comanda</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Usuario</th>
                    <th>Forma de pagamento</th>
                    <th>Valor por forma de pag.</th>
                    <th>Status</th>
                    <th>total Pedido</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="global">
                <button onclick="ReportSales()" class="button-global">Imprimir</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/reports.js"></script>