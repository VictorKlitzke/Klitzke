<?php

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'list-boxpdv';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$user_filter = isset($_POST['user_filter']) ? intval($_POST['user_filter']) : $user_id;

$date_end = isset($_POST['endDate']) ? $_POST['endDate'] : null;
$date_start = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$date_start1 = !empty($date_start) ? date('Y-m-d', strtotime($date_start)) : null;
$date_end1 = !empty($date_end) ? date('Y-m-d', strtotime($date_end)) : null;

$boxpdv = Controllers::SelectBoxPdv('boxpdv', $user_filter);

?>

<div class="container-fluid p-4 bg-light border rounded-4 shadow-lg">
  <div class="card bg-light rounded">
    <div class="card-header text-dark rounded-top">
      <h2 class="mb-0">Filtros</h2>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="row">
          <div class="col-md-6 mb-4">
            <label for="startDate" class="form-label">
              <i class="fas fa-calendar-alt me-2"></i>Data Início
            </label>
            <input type="date" name="startDate" id="startDate"
              class="form-control bg-white text-dark border-secondary rounded-pill" placeholder="dd/mm/yyyy">
          </div>
          <div class="col-md-6 mb-4">
            <label for="endDate" class="form-label">
              <i class="fas fa-calendar-alt me-2"></i>Data Final
            </label>
            <input type="date" name="endDate" id="endDate"
              class="form-control bg-white text-dark border-secondary rounded-pill" placeholder="dd/mm/yyyy">
          </div>
        </div>
        <button class="btn btn-dark w-100 rounded-pill" type="submit">
          <i class="fas fa-filter me-2"></i>Filtrar
        </button>
      </form>
    </div>
  </div>
  <br>
  <h2 class="text-dark mb-4">Lista de Caixas</h2>
  <div class="row">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
          <thead class="table-dark text-light">
            <tr>
              <th>Usuario</th>
              <th>Valor</th>
              <th>Observação</th>
              <th>Data abertura</th>
              <th>Retirada</th>
              <th>Valor Caixa Atual</th>
              <th class="accessnivel">Ações</th>
            </tr>
          </thead>

          <?php
          foreach ($boxpdv as $key => $value) {
            ?>

            <tbody>
              <tr>
                <th><?php echo htmlspecialchars($value['users']); ?></th>
                <th><?php echo htmlspecialchars($value['value']); ?></th>
                <th><?php echo htmlspecialchars($value['observation']); ?></th>
                <th><?php $date = new DateTime($value['open_date']);
                echo htmlspecialchars($date->format('d/m/Y')); ?></th>
                <th><?php echo htmlspecialchars($value['Withdrawal']); ?></th>
                <th><?php echo htmlspecialchars($value['retiradatotal']); ?></th>

                <th class="gap-2 d-flex accessnivel">
                  <?php if ($value['status'] == 1) { ?>
                    <a class="btn btn-info w-100"
                      href="<?php echo INCLUDE_PATH ?>boxpdv-sangria?id=<?php echo base64_encode($value['id']); ?>">Retirar
                    </a>
                  <?php } else { ?>
                    <button class="btn btn-secondary w-100">Fechado</button>
                  <?php } ?>
                <?php if ($value['status'] =! 1) {?>
                  <button class="btn btn-light w-100 accessnivel" id="reopen-boxpdv"  data-id="<?php echo base64_encode($value['id']); ?>"
                    data-bs-toggle="modal" data-bs-target="#reopenModal" onclick="setBoxID(this)">Reabrir
                  </button>
                <?php } ?>
                </th>
              </tr>
            </tbody>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="reopenModal" tabindex="-1" aria-labelledby="reopenModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reopenModalLabel">Reabrir Caixa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="reopenReason" class="form-label">Motivo para reabrir:</label>
        <textarea id="reopenReason" name="reopenReason" placeholder="Digite o motivo" class="form-control"
          rows="4"></textarea>
        <input type="hidden" id="boxId" name="boxId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="submitReopenReason()">Confirmar</button>
      </div>
    </div>
  </div>
</div>