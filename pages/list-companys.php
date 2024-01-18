<?php

if (isset($_GET['delete'])) {
	$del = intval($_GET['delete']);
	Controllers::Delete('company', $del);
	header('Location: ' . INCLUDE_PATH . 'list-company');
}

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$company = Controllers::SelectAll('company', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
	<h2>Suas Empresas</h2>
	<div class="list">
		<table>
			<thead>
				<tr>
					<td>Cliente</td>
					<td>CNPJ</td>
					<td>Escrição Estadual</td>
					<td>Email</td>
					<td>Contato</td>
					<td>Cidade</td>
					<td>Endereço</td>
					<td>Estado</td>
					<td>Ações</td>
				</tr>
			</thead>

			<?php

			foreach ($company as $key => $value) {

			?>

				<tbody>
					<tr>
						<td><?php echo htmlspecialchars($value['name']); ?></td>
						<td><?php echo htmlspecialchars($value['cnpj']); ?></td>
						<td><?php echo htmlspecialchars($value['state_registration']); ?></td>
						<td><?php echo htmlspecialchars($value['email']); ?></td>
						<td><?php echo htmlspecialchars($value['phone']); ?></td>
						<td><?php echo htmlspecialchars($value['city']); ?></td>
						<td><?php echo htmlspecialchars($value['address']); ?></td>					
						<td><?php echo htmlspecialchars($value['state']); ?></td>

						<td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
							<div>
								<a class="btn-edit" href="<?php echo INCLUDE_PATH ?>edit-companys?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
							</div>
							<div>
								<a class="btn-disable" href="<?php echo INCLUDE_PATH ?>">Desativar</a>
							</div>
							<div>
								<a actionBtn="delete" class="btn-delete" href="<?php echo INCLUDE_PATH ?>list-companys?delete=<?php echo $value['id']; ?>">Deletar</a>
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
	$totalPage = ceil(count(Controllers::selectAll('company')) / $porPage);

	for ($i = 1; $i <= $totalPage; $i++) {
		if ($i == $currentPage)
			echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-company?page=' . $i . '">' . $i . '</a>';
		else
			echo '<a href="' . INCLUDE_PATH . 'list-company?page=' . $i . '">' . $i . '</a>';
	}

	?>
</div>