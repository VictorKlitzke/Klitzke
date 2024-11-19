<?php
if (!isset($_SESSION['id'])) {
	header("Location: login.php");
	exit();
}
$page_permission = 'list-conditional';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
	header("Location: " . INCLUDE_PATH . "access-denied.php");
	exit();
}
?>

<div class="container-fluid bg-light p-4 rounded-4 border shadow-lg">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-dark">Lista de Condicionais</h2>
    <a class="btn btn-success" <?php SelectedMenu('register-conditional'); ?>
      href="<?php echo INCLUDE_PATH; ?>register-conditional">Nova Condicional
    </a>
  </div>
  <div class="table-responsive" style="max-height: 500px; overflow-y: auto; overflow-x: auto;">
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
              <button class="btn btn-info">Editar</button>
              <button class="btn btn-dark">Opções</button>
              <button class="btn btn-success">Faturar</button>
          </th>
        </tr>
      </tbody>

    </table>
  </div>
</div>