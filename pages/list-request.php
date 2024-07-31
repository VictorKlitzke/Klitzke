<?php

$request = Controllers::SelectRequest('request');

?>

<div class="box-content left w100">
    <h2 class="text-white mb-4">Lista de pedidos</h2>
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-dark table-hover">
                    <thead style="white-space: nowrap;">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mesa</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total</th>
                            <th scope="col">Data</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <?php

                    foreach ($request as $key => $value) { ?>

                        <tbody style="white-space: nowrap;">
                            <tr class="<?php echo $value['STATUS_REQUEST'] == 'INATIVADA' ? 'table-danger' : ''; ?>">
                                <th>
                                    <?php echo htmlspecialchars($value["id"]); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars($value["id_table"]); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars(
                                        $value["STATUS_REQUEST"]
                                    ); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars(
                                        $value["total_request"]
                                    ); ?>
                                </th>
                                <th>
                                    <?php echo htmlspecialchars(
                                        $value["date_request"]
                                    ); ?>
                                </th>

                                <th class="gap-2">
                                    <?php if ($value["STATUS_REQUEST"] == "INATIVADA") {

                                        ?>
                                        <button class="btn btn-secondary">Inativado</button>
                                    <?php } else { ?>
                                        <button onclick="InativarInvo(this)" type="button" data-id="<?php echo $value['id']; ?>"
                                            class="btn btn-light"> Inativar P
                                        </button>
                                    <?php } ?>
                                    <button onclick="DetailsOrder(this)" class="btn btn-info"
                                        data-id="<?php echo $value['id']; ?>" type="button">Mais detalhes
                                    </button>
                                </th>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-print-request">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" i>Venda <span id="requestID"></span></h5>
                <button type="button" class="btn-close" onclick="CloseModalInfoRequest()" id="close-details-request"></button>
            </div>
            <div class="modal-title" id="modal-content-details-request">
               <div class="modal-body">
                <div class="table-responsive">
                <table class="table table-bordered table-hover" id="modalTable-request" border="1">
                    <thead class="table-dark" style="white-space: nowrap;">
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
                </div>
               </div>
            </div>
        </div>
    </div>
</div>