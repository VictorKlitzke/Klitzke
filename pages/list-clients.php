<?php

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$clients = Controllers::SelectAll('clients', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
	<h2>Lista de Clientes</h2>
	<div class="list">
		<table>
			<thead>
				<tr>
					<td>Cliente</td>
					<td>Nome social</td>
					<td>Email</td>
					<td>Contato</td>
					<td>Cep</td>
					<td>Cidade</td>
					<td>Endereço</td>
					<td>CPF</td>
					<td>Bairro</td>
				</tr>
			</thead>

			<?php

			foreach ($clients as $key => $value) {

			?>
			
				<tbody>
					<tr>
						<td><?php echo htmlspecialchars($value['name']); ?></td>
						<td><?php echo htmlspecialchars($value['social_reason']); ?></td>
						<td><?php echo htmlspecialchars($value['email']); ?></td>
						<td><?php echo htmlspecialchars($value['phone']); ?></td>
						<td><?php echo htmlspecialchars($value['cep']); ?></td>
						<td><?php echo htmlspecialchars($value['city']); ?></td>
						<td><p><?php echo htmlspecialchars($value['address']); ?></p></td>
						<td><?php echo htmlspecialchars($value['cpf']); ?></td>
						<td><?php echo htmlspecialchars($value['neighborhood']); ?></td>

						<td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
							<div>
								<a class="btn-edit" href="<?php echo INCLUDE_PATH ?>edit-clients?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
							</div>
							<div>
								<a class="btn-disable" href="<?php echo INCLUDE_PATH ?>">Desativar</a>
							</div>
							<div>
								<a class="btn-delete" onclick="DeleteClients(this)" data-id="<?php echo base64_encode($value['id']); ?>">Deletar</a>
							</div>
						</td>
					</tr>
				</tbody>

			<?php } ?>

		</table>
	</div>
</div>

<div class="page">
	<?php
	$totalPage = ceil(count(Controllers::selectAll('clients')) / $porPage);

	for ($i = 1; $i <= $totalPage; $i++) {
		if ($i == $currentPage)
			echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-clients?page=' . $i . '">' . $i . '</a>';
		else
			echo '<a href="' . INCLUDE_PATH . 'list-clients?page=' . $i . '">' . $i . '</a>';
	}

	?>
</div>