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
          <th escope="col">#</th>
          <th escope="col">Cliente</th>
          <th escope="col">Vendedor</th>
          <th escope="col">Status</th>
          <th escope="col">Data</th>
          <th escope="col">Data de devolução</th>
          <th escope="col">Sub Total</th>
          <th escope="col">Desconto</th>
          <th escope="col">Total</th>
          <th escope="col">Ações</th>
        </tr>
      </thead>

      <tbody id="list-conditional" style="white-space: nowrap;">
          <!-- <th>
              <button class="btn btn-info">Editar</button>
              <button class="btn btn-dark">Opções</button>
              <button class="btn btn-success">Faturar</button>
          </th> -->
      </tbody>

    </table>
  </div>
</div>