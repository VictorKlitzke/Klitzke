<?php

$currentPage = isset($_GET['page']) ? (int) ($_GET['page']) : 1;
$porPage = 20;

$clients = Controllers::SelectAll('clients', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
	<h2 class="text-white mb-4">Lista de Clientes</h2>
	<div class="row">
		<div class="col">
			<div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
				<table class="table table-dark table-hover">
					<thead>
						<tr style="white-space: nowrap;">
							<th scope="col">Cliente</th>
							<th scope="col">Nome social</th>
							<th scope="col">Email</th>
							<th scope="col">Contato</th>
							<th scope="col">Cep</th>
							<th scope="col">Cidade</th>
							<th scope="col">Endereço</th>
							<th scope="col">CPF</th>
							<th scope="col">Bairro</th>
							<th scope="col">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($clients as $key => $value) { ?>
							<tr style="white-space: nowrap;" class="<?php echo $value['disable'] != 1 ? 'table-danger' : ''; ?>">
								<th><?php echo htmlspecialchars($value['name']); ?></th>
								<th><?php echo htmlspecialchars($value['social_reason']); ?></th>
								<th><?php echo htmlspecialchars($value['email']); ?></th>
								<th><?php echo htmlspecialchars($value['phone']); ?></th>
								<th><?php echo htmlspecialchars($value['cep']); ?></th>
								<th><?php echo htmlspecialchars($value['city']); ?></th>
								<th><?php echo htmlspecialchars($value['address']); ?></th>
								<th><?php echo htmlspecialchars($value['cpf']); ?></th>
								<th><?php echo htmlspecialchars($value['neighborhood']); ?>
								</th>
								<th class="gap-2">
									<a class="btn btn-info"
										href="<?php echo INCLUDE_PATH ?>edit-clients?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
									<button class="btn btn-warning" href="<?php echo INCLUDE_PATH ?>">Desativar</button>
									<button class="btn btn-danger" onclick="deleteClients(this)"
										data-id="<?php echo base64_encode($value['id']); ?>">Deletar</button>
								</th>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
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