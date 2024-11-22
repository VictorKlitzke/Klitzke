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
      </tbody>

    </table>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="fullScreenModal" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="fullScreenModalLabel">Detalhes da Condicional</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid p-4 bg-light shadow-lg bg-light border rounded-4">
          <div class="row g-3">
            <div class="col">
              <h2>Itens da Condicional</h2>
              <div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
                <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
                  <thead class="table-dark text-light">
                    <tr style="white-space: nowrap;">
                      <th scope="col">#</th>
                      <th scope="col">Produto</th>
                      <th scope="col">Quantidade</th>
                      <th scope="col">Preço Unitário</th>
                      <th scope="col">Subtotal</th>
                      <th scope="col">Ação</th>
                    </tr>
                  </thead>
                  <tbody id="conditional-itens" style="white-space: nowrap;"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <br>
        <br>
        <div class="container-fluid p-4 bg-light shadow-lg bg-light border rounded-4">
          <div class="row">
            <div class="col">
              <h2 class="text-dark">Faturar Itens</h2>
              <div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
                <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
                  <thead class="table-dark text-light">
                    <tr style="white-space: nowrap;">
                      <th scope="col">#</th>
                      <th scope="col">Produto</th>
                      <th scope="col">Quantidade</th>
                      <th scope="col">Preço Unitário</th>
                      <th scope="col">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody id="billing-items" style="white-space: nowrap;"></tbody>
                  <tfoot class="table-light">
                    <tr>
                      <td colspan="4" class="text-end"><strong>Total:</strong></td>
                      <td id="total-value">R$ 0,00</td>
                    </tr>
                  </tfoot>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Faturar</button>
      </div>
    </div>
  </div>
</div>