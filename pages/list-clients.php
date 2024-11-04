<?php

if (!isset($_SESSION['id'])) {
	header("Location: login.php");
	exit();
}
$page_permission = 'list-clients';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
	header("Location: " . INCLUDE_PATH . "access-denied.php");
	exit();
}

$clients = Controllers::SelectAll('clients');

?>

<div class="contaneir-fluid p-4 shadow-lg bg-light border rounded-4">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h2 class="text-dark mb-4">Lista de Clientes</h2>
		<a class="btn btn-success" <?php SelectedMenu('register-clients'); ?>
			href="<?php echo INCLUDE_PATH; ?>register-clients">+ Novo Cliente</a>
	</div>
	<div class="row">
		<div class="col">
			<div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
				<table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
					<thead class="table-dark text-light">
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
								<th class="gap-2 d-flex align-items-center justify-content-center">
									<?php if ($value['disable'] == 1) { ?>
										<a class="btn btn-info btn-sm fw-bold accessnivel"
											href="<?php echo INCLUDE_PATH ?>edit-clients?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
									<?php } else { ?>
										<span class="text-muted"></span>
									<?php } ?>
									<!-- <button class="btn btn-warning btn-sm fw-bold" onclick="InativarClients(this)"
										data-id="<?php echo base64_encode($value['id']); ?>">
										Desativar
									</button> -->
									<button class="btn btn-danger btn-sm fw-bold" onclick="deleteClients(this)"
										data-id="<?php echo base64_encode($value['id']); ?>">
										Deletar
									</button>
									<?php if ($value['disable'] == 1) { ?>
										<button class="btn btn-primary btn-sm fw-bold" onclick="MoreDetailsClient(this)"
											data-id="<?php echo base64_encode($value['id']); ?>">
											Mais Detalhes
										</button>
									<?php } else { ?>
										<span class="text-muted"></span>
									<?php } ?>

								</th>
							</tr>
						<?php } ?>
					</tbody>
					</>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="details-modal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailsModalLabel">Detalhes do Cliente</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Abas -->
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button"
							role="tab" aria-controls="sales" aria-selected="true">Vendas</button>
					</li>
					<!-- <li class="nav-item" role="presentation">
						<button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button"
							role="tab" aria-controls="orders" aria-selected="false">Contas a Pagar</button>
					</li> -->
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="budgets-tab" data-bs-toggle="tab" data-bs-target="#budgets" type="button"
							role="tab" aria-controls="budgets" aria-selected="false">Orçamentos</button>
					</li>
				</ul>

				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
						<div id="client-sales" class="row p-lg-2">
						</div>
					</div>
					<!-- <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
						<div id="client-financial-control" class="row p-4">
						</div>
					</div> -->
					<div class="tab-pane fade" id="budgets" role="tabpanel" aria-labelledby="budgets-tab">
						<div id="client-budgets" class="row p-4">
							<h1>Em desenvolvimento</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>