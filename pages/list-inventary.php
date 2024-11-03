<?php

$page_permission = 'list-inventary';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

$company = Controllers::SelectAll('company');

?>

<div class="container-fluid p-4 bg-light shadow-lg border rounded-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-dark fw-bold">Lista de Inventarios</h2>
    <a class="btn btn-success px-4 fw-bold" <?php SelectedMenu('stock-inventory'); ?>
      href="<?php echo INCLUDE_PATH; ?>stock-inventory">+ Novo Inventario
    </a>
  </div>
  <div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Buscar por usuário, observação, status, etc."
      onkeyup="filterInventary()">
  </div>
  <div class="row">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm"
          id="list-inventary">
          <thead class="table-dark text-light">
            <tr>
              <th escope="col">#</th>
              <th escope="col">Usuário</th>
              <th escope="col">Observação</th>
              <th escope="col">Status</th>
              <th escope="col">Criando em</th>
              <th escope="col">Ações</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>


  <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content rounded-3 shadow-lg" style="background-color: #f8f9fa;">
        <div class="modal-header border-bottom">
          <h5 class="modal-title text-primary" id="detailsModalLabel">Detalhes do Inventário</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modalContent" class="p-4">
            Carregando...
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>