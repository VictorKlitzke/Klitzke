<div class="container-fluid shadow-lg border rounded-4 p-4 bg-light">
  <div class="container-fluid bg-light">
    <h2 class="text-dark">Inventário de Produtos</h2>
    <hr class="border-bottom">
  </div>

  <!-- Formulário de Adição de Produtos -->
  <div class="card mt-4">
    <div class="card-header bg-primary text-white">
      Editar Quantidade
    </div>
    <div class="card-body">
      <form id="addProductForm">
        <div class="row">
          <div class="col-md-2">
            <div class="mb-3">
              <label for="productName" class="form-label">ID Produto</label>
              <input type="text" class="form-control" id="productID" placeholder="ID: 3" disabled>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="productName" class="form-label">Nome do Produto</label>
              <input type="text" class="form-control" id="productName" placeholder="Ex: Notebook" disabled>
            </div>
          </div>
          <div class="col-md-2">
            <div class="mb-3">
              <label for="productQuantity" class="form-label">Quantidade</label>
              <input type="number" class="form-control" id="productQuantity" placeholder="Ex: 10">
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="productPrice" class="form-label">Preço</label>
              <input type="text" class="form-control" id="productPrice" placeholder="Ex: R$ 2.500,00">
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-success" onclick="Inventaryquantity()">Editar Produto</button>
      </form>
    </div>
  </div>

  <!-- Tabela de Produtos -->
  <div class="card mt-4">
    <div class="card-header text-dark">
      Lista de Produtos
    </div>
    <div class="card-body">
      <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar produtos..."
          onkeyup="searchProducts()">
      </div>
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
          <thead class="table-dark text-light">
            <tr>
              <th>ID</th>
              <th>Nome do Produto</th>
              <th>Quantidade</th>
              <th>Preço</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="productTable">
            <!-- Os produtos serão inseridos aqui -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>