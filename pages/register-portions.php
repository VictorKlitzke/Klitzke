<div class="container-fluid shadow-lg border rounded-4 p-4 bg-light" id="inventory-screen">
  <div class="container-fluid bg-light">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="text-dark">Criação de Porção</h2>
      <h4 class="text-muted" id="id-portion"></h4>
    </div>
    <hr class="border-bottom">
  </div>

  <!-- Seção de Criação do Inventário -->
  <div class="card mt-4">
    <div class="card-header bg-primary text-white">
      Criar Porção
    </div>
    <div class="card-body">
      <form id="createFormPortion">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="inventaryDate" class="form-label">Nome da Porção</label>
              <input type="text" class="form-control border-dark" id="nameportion" placeholder="Nome da Porção">
              <span id="value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="inventaryDate" class="form-label">Valor da Porção</label>
              <input type="text" class="form-control border-dark" oninput="formmaterReal(this)" id="valuePortion" placeholder="Valor da Porção">
              <span id="value-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <label for="obsportion" class="form-label">Observações</label>
              <textarea class="form-control border-dark" id="obsportion" rows="3"
                placeholder="Digite observações relevantes..."></textarea>
            </div>
          </div>
          <button type="button" class="btn btn-success" onclick="CreatePortion()">Criar Porção</button>
      </form>
    </div>
  </div>
</div>

<div class="card mt-4" id="portion-products" style="display: none;">
  <div class="card-header bg-info text-white">
    Ajustar Quantidade de Produtos na Porção
  </div>
  <div class="card-body">
    <form id="adjustProductForm">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID Produto</th>
              <th>Nome do Produto</th>
              <th>Quantidade</th>
            </tr>
          </thead>
          <tbody id="product-portion-row">
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-start align-items-center gap-2">
        <button type="button" class="btn btn-warning" onclick="RegisterProductPortion()">Gerar Porção</button>
      </div>
    </form>
  </div>
</div>

<!-- Seção da Lista de Produtos do Inventário -->
<div class="card mt-4" id="list-product-portion" style="display: none;">
  <div class="card-header text-dark">
    Produtos
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
            <th>#</th>
            <th>Nome do Produto</th>
            <th>Quantidade</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody id="product-portion">
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>