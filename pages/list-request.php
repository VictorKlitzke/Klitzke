<?php
$currentPage = isset($_POST['page']) ? (int) ($_POST['page']) : 1;
$porPage = 20;

$request = Controllers::SelectAll('request', ($currentPage - 1) * $porPage, $porPage);
?>

<div class="box-content left w100">
	<div
		style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">
		<h2 style="color: #000">Lista de pedidos</h2>
		<div class="btn-ajust" style="flex-grow: 1; text-align: center; max-width: 180px;">
			<a class="btn-ajust" href="<?php echo htmlspecialchars(INCLUDE_PATH . 'gather-tables'); ?>">
				Agrupar Comandas
			</a>
		</div>
	</div>
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
			$request_dados = Controllers::SelectRequest("request");

			foreach ($request_dados as $key => $value) { ?>

				<tbody>

					<tr>
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
							<div>
								<button
									onclick="ModalFaturamento('<?php echo $value['id_table']; ?>','<?php echo $value['date_request']; ?>','<?php echo $value['total_request']; ?>','<?php echo $value['STATUS_REQUEST']; ?>')"
									class="btn-disable" type="button" id="btn-list-request-invoicing-<?php echo $key; ?>">
									Faturar
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

<div class="page">

	<?php
	$totalPage = ceil(count(Controllers::selectAll("request")) / $porPage);

	for ($i = 1; $i <= $totalPage; $i++) {
		if ($i == $currentPage) {
			echo '<a class="page-selected" href="' .
				INCLUDE_PATH .
				"list-request?page=" .
				$i .
				'">' .
				$i .
				"</a>";
		} else {
			echo '<a href="' .
				INCLUDE_PATH .
				"list-request?page=" .
				$i .
				'">' .
				$i .
				"</a>";
		}
	}
	?>

</div>

<div class="box-content left w100">
	<div class=""
		style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">
		<h2 style="color: #000">Lista de pedidos agrupados</h2>
	</div>
	<div class="list">
		<table>
			<thead>

				<tr>
					<td>Comandas principal</td>
					<td>Comandas agrupadas</td>
					<td>Status</td>
					<td>Valor Total</td>
					<td>Data</td>
				</tr>

			</thead>

			<?php
			$request_dados = Controllers::SelectrequestGathers("request_gathers");

			foreach ($request_dados as $key => $value) { ?>

				<tbody>

					<tr>
						<td>
							<?php echo htmlspecialchars(
								$value["principal_command_id"]
							); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["grouped_command_id"]
							); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["status"]
							); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["value_total"]
							); ?>
						</td>
						<td>
							<?php echo htmlspecialchars(
								$value["created_at"]
							); ?>
						</td>

						<td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
							<div>
								<form method="post" action="./ajax/ungroup_order.php">
									<input type="hidden" name="id_table_gathers"
										value="<?php echo base64_encode($value["principal_command_id"]); ?>">
									<button type="submit" class="btn-ungroup">Desagrupar</button>
								</form>
							</div>
							<div>
								<form action="" method="post">
									<input type="hidden" name="">
									<button class="btn-disable">Faturar</button>
								</form>
							</div>
						</td>
					</tr>

				</tbody>

			<?php }
			?>

		</table>
	</div>
</div>

<div class="page">

	<?php
	$totalPage = ceil(count(Controllers::selectAll("request")) / $porPage);

	for ($i = 1; $i <= $totalPage; $i++) {
		if ($i == $currentPage) {
			echo '<a class="page-selected" href="' .
				INCLUDE_PATH .
				"list-request?page=" .
				$i .
				'">' .
				$i .
				"</a>";
		} else {
			echo '<a href="' .
				INCLUDE_PATH .
				"list-request?page=" .
				$i .
				'">' .
				$i .
				"</a>";
		}
	}
	?>

</div>

<div class="overlay-invo" id="overlay-invo">
	<div class="modal-invo" id="modal-invo">
		<div class="navbar-invo">
			<h2>Fechar pedido</h2>
			<svg id="modal-invo-close" style="cursor: pointer;" fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24px"
				viewBox="0 0 24 24" width="24px" fill="#000000">
				<path d="M0 0h24v24H0z" fill="none" />
				<path
					d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
			</svg>
		</div>
		<div class="modal-content">
			<div class="info-invo">
				<span id="id-table"></span>
				<span id="total-request">Valor Total:</span>
				<span id="status-request">Status: </span>
				<span id="date-request">Data do pedido: </span>
			</div>
			<div class="button-forms-invo">
				<?php
					$forms_payments = Controllers::SelectAllFormPayment("form_payment");
					foreach ($forms_payments as $key => $value) {
				?>
					<button><?php echo $value['forms_payment']; ?></button>
				<?php } ?>
				<button class="right Invo-Fat" type="button">Faturar</button>
			</div>
		</div>
	</div>
</div>