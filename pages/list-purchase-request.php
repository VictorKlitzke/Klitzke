<?php
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'list-purchase-request';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>

<div class="container-fluid shadow-lg border rounded-4 bg-light p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark mb-0">Lista de Solicitações</h2>
    <div class="d-flex align-items-center">
      <div class="input-group me-3">
        <input class="form-control" id="input-buy-request" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
        <button class="btn btn-success" id="button-search" type="button">Buscar</button>
      </div>
      <button onclick="ShowModalAddVariation()" class="btn btn-primary" style="white-space: nowrap;">Variação de
        valores</button>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px;">
        <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm"
          id="table-buy-request">
          <thead class="table-dark text-light">
            <tr style="white-space: nowrap;">
              <th scope="col">#</th>
              <th scope="col">Produto</th>
              <th scope="col">Fornecedor</th>
              <th scope="col">Quantidade</th>
              <th scope="col">Mensagens</th>
              <th scope="col">Data</th>
            </tr>
          </thead>
          <tbody id="buy-request-result">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<br>

<div class="container-fluid shadow-lg border rounded-4 bg-light p-4" style="display: none;" id="add-variation-forn">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark mb-0">Lista de Valores</h2>
    <div class="d-flex">
      <div class="input-group me-3">
        <input class="form-control" id="input-variation-values" type="search" placeholder="Pesquisar"
          aria-label="Pesquisar">
        <button class="btn btn-success" id="button-search-variation-values" type="button">Buscar</button>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px;">
        <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm"
          id="table-variation-values">
          <thead class="table-dark text-light">
            <tr style="white-space: nowrap;">
              <th scope="col">#</th>
              <th scope="col">Produto</th>
              <th scope="col">Fornecedor</th>
              <th scope="col">Quantidade</th>
              <th scope="col">Valor Unitário</th>
            </tr>
          </thead>
          <tbody id="variation-values-result">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>