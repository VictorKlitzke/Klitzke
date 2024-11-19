<?php
if (!isset($_SESSION['id'])) {
	header("Location: login.php");
	exit();
}
$page_permission = 'list-companys';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
	header("Location: " . INCLUDE_PATH . "access-denied.php");
	exit();
}

$company = Controllers::SelectAll('company');

?>

<div class="container-fluid bg-light p-4 rounded-4 border shadow-lg">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h2 class="text-dark mt-4">Suas Empresas</h2>
		<a class="btn btn-success" <?php SelectedMenu('register-companys'); ?>
			href="<?php echo INCLUDE_PATH; ?>register-companys">Empresa Filial
		</a>
	</div>
	<div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
		<table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
			<thead class="table-dark text-light">
				<tr>
					<th escope="col">Cliente</th>
					<th escope="col">CNPJ</th>
					<th escope="col">Escrição Estadual</th>
					<th escope="col">Email</th>
					<th escope="col">Contato</th>
					<th escope="col">Cidade</th>
					<th escope="col">Endereço</th>
					<th escope="col">Estado</th>
					<th escope="col">Ações</th>
				</tr>
			</thead>

			<?php

			foreach ($company as $key => $value) {

				?>

				<tbody style="white-space: nowrap;">
					<tr>
						<th><?php echo htmlspecialchars($value['name']); ?></th>
						<th><?php echo htmlspecialchars($value['cnpj']); ?></th>
						<th><?php echo htmlspecialchars($value['state_registration']); ?></th>
						<th><?php echo htmlspecialchars($value['email']); ?></th>
						<th><?php echo htmlspecialchars($value['phone']); ?></th>
						<th><?php echo htmlspecialchars($value['city']); ?></th>
						<th><?php echo htmlspecialchars($value['address']); ?></th>
						<th><?php echo htmlspecialchars($value['state']); ?></th>

						<th>

							<a class="btn btn-info"
								href="<?php echo INCLUDE_PATH ?>edit-companys?id=<?php echo base64_encode($value['id']); ?>">Editar</a>

							<!-- <a class="btn btn-light" href="<?php echo INCLUDE_PATH ?>">Desativar</a> -->
						</th>
					</tr>
				</tbody>
			<?php } ?>

		</table>
	</div>
</div>