<?php
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'conditional-itens';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}
?>

<div class="container-fluid p-4 shadow-lg border bg-light rounded-4">
  <div class="row">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
          <thead class="table-dark text-light">
            <tr style="white-space: nowrap;">
              <th scope="col">ID Item</th>
              <th scope="col">Produto</th>
              <th scope="col">Quantidade</th>
              <th scope="col">Preço Unitário</th>
              <th scope="col">Subtotal</th>
            </tr>
          </thead>
          <tbody id="conditional-itens" style="white-space: nowrap;"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>