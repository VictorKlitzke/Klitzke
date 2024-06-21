<?php

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$request = Controllers::SelectRequest('request', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content left w100">
	<div
		style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">
		<h2 style="color: #000">Lista de pedidos</h2>
<!--		<div class="btn-ajust" style="flex-grow: 1; text-align: center; max-width: 180px;">-->
<!--			<a class="btn-ajust" href="--><?php //echo htmlspecialchars(INCLUDE_PATH . 'gather-tables'); ?><!--">-->
<!--				Agrupar Comandas-->
<!--			</a>-->
<!--		</div>-->
	</div>
	<div class="list">
		<table>
			<thead>

				<tr>
                    <td>#</td>
					<td>Mesa</td>
					<td>Status</td>
					<td>Total</td>
					<td>Data</td>
				</tr>

			</thead>

			<?php

			foreach ($request as $key => $value) { ?>

				<tbody>

					<tr>
                        <td>
                            <?php echo htmlspecialchars($value["id"]); ?>
                        </td>
						<td>
							<?php echo htmlspecialchars($value["id_table"]); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["STATUS_REQUEST"]
							); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["total_request"]
							); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["date_request"]
							); ?>
						</td>

						<td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                            <?php if ($value["STATUS_REQUEST"] == "INATIVADA") {

                            ?>
                            <div>
                                <button class="btn-disable-invo">Inativado</button>
                            </div>
                            <?php } else { ?>
                            <div>
                                <button onclick="InativarInvo(this)" type="button" data-id="<?php echo $value['id']; ?>"
                                        class="btn-disable"> Inativar Pedido
                                </button>
                            </div>
                            <?php } ?>
                            <div>
                                <button onclick="DetailsOrder(this)" class="btn-delete"
                                        data-id="<?php echo $value['id']; ?>" type="button">Mais detalhes
                                </button>
                            </div>
						</td>
					</tr>
				</tbody>
			<?php }
			?>
		</table>
	</div>
</div>

<div class="overlay-details-request" id="overlay-details-request">
    <div id="modal-print-request" class="modal-request">
        <div class="modal-content-details-request" id="modal-content-details-request">
            <span class="close-details" onclick="CloseModalInfoRequest()" id="close-details-request">&times;</span>
            <h1>Venda ID: <span id="requestID"></span></h1>
            <table id="modalTable-request" border="1">
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
        </div>
    </div>
</div>

<div class="page">
    <?php
    $totalPage = ceil(count(Controllers::selectAll('request')) / $porPage);

    for ($i = 1; $i <= $totalPage; $i++) {
        if ($i == $currentPage)
            echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-request?page=' . $i . '">' . $i . '</a>';
        else
            echo '<a href="' . INCLUDE_PATH . 'list-request?page=' . $i . '">' . $i . '</a>';
    }

    ?>
</div>

<!--<div class="box-content left w100">-->
<!--	<div class=""-->
<!--		style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">-->
<!--		<h2 style="color: #000">Lista de pedidos agrupados</h2>-->
<!--	</div>-->
<!--	<div class="list">-->
<!--		<table>-->
<!--			<thead>-->
<!---->
<!--				<tr>-->
<!--					<td>Comandas principal</td>-->
<!--					<td>Comandas agrupadas</td>-->
<!--					<td>Status</td>-->
<!--					<td>Valor Total</td>-->
<!--					<td>Data</td>-->
<!--				</tr>-->
<!---->
<!--			</thead>-->
<!---->
<!--			--><?php
//			$request_dados = Controllers::SelectrequestGathers("request_gathers");
//
//			foreach ($request_dados as $key => $value) { ?>
<!---->
<!--				<tbody>-->
<!---->
<!--					<tr>-->
<!--						<td>-->
<!--							--><?php //echo htmlspecialchars(
//								$value["principal_command_id"]
//							); ?>
<!--						</td>-->
<!--						<td>-->
<!--							--><?php //echo htmlspecialchars(
//								$value["grouped_command_id"]
//							); ?>
<!--						</td>-->
<!--						<td>-->
<!--							--><?php //echo htmlspecialchars(
//								$value["status"]
//							); ?>
<!--						</td>-->
<!--						<td>-->
<!--							--><?php //echo htmlspecialchars(
//								$value["value_total"]
//							); ?>
<!--						</td>-->
<!--						<td>-->
<!--							--><?php //echo htmlspecialchars(
//								$value["created_at"]
//							); ?>
<!--						</td>-->
<!---->
<!--						<td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">-->
<!--							<div>-->
<!--								<form method="post" action="./ajax/ungroup_order.php">-->
<!--									<input type="hidden" name="id_table_gathers"-->
<!--										value="--><?php //echo base64_encode($value["principal_command_id"]); ?><!--">-->
<!--									<button type="submit" class="btn-ungroup">Desagrupar</button>-->
<!--								</form>-->
<!--							</div>-->
<!--							<div>-->
<!--								<form action="" method="post">-->
<!--									<input type="hidden" name="">-->
<!--									<button class="btn-disable">Faturar</button>-->
<!--								</form>-->
<!--							</div>-->
<!--						</td>-->
<!--					</tr>-->
<!---->
<!--				</tbody>-->
<!---->
<!--			--><?php //}
//			?>
<!---->
<!--		</table>-->
<!--	</div>-->
<!--</div>-->
<!---->
<!--<div class="page">-->
<!---->
<!--	--><?php
//	$totalPage = ceil(count(Controllers::selectAll("request")) / $porPage);
//
//	for ($i = 1; $i <= $totalPage; $i++) {
//		if ($i == $currentPage) {
//			echo '<a class="page-selected" href="' .
//				INCLUDE_PATH .
//				"list-request?page=" .
//				$i .
//				'">' .
//				$i .
//				"</a>";
//		} else {
//			echo '<a href="' .
//				INCLUDE_PATH .
//				"list-request?page=" .
//				$i .
//				'">' .
//				$i .
//				"</a>";
//		}
//	}
//	?>
<!---->
<!--</div>-->