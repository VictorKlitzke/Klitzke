<div class="container-fluid shadow-lg border rounded-4 p-4 bg-light" id="inventory-screen">
  <div class="container-fluid bg-light">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="text-dark">Inventário de Produtos</h2>
      <h4 class="text-muted" id="idInventary"></h4>
    </div>
    <hr class="border-bottom">
  </div>

  <!-- Seção de Criação do Inventário -->
  <div class="card mt-4">
    <div class="card-header bg-primary text-white">
      Criar Novo Inventário
    </div>
    <div class="card-body">
      <form id="createInventaryForm">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="inventaryDate" class="form-label">Data do Inventário</label>
              <input type="date" class="form-control border-dark" id="inventaryDate">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="inventaryStatus" class="form-label">Status</label>
              <select class="form-control border-dark" id="inventaryStatus">
                <option value="Em Andamento">Em Andamento</option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <label for="inventaryObs" class="form-label">Observações</label>
              <textarea class="form-control border-dark" id="inventaryObs" rows="3"
                placeholder="Digite observações relevantes..."></textarea>
            </div>
          </div>
          <button type="button" class="btn btn-success" onclick="Inventaryquantity()">Criar Inventário</button>
      </form>
    </div>
  </div>
</div>

<div class="card mt-4" id="AdjustInventary" style="display: none;">
  <div class="card-header bg-info text-white">
    Ajustar Quantidade de Produtos no Inventário
  </div>
  <div class="card-body">
    <form id="adjustProductForm">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID Produto</th>
              <th>Nome do Produto</th>
              <th>Quantidade Atual</th>
              <th>Nova Quantidade</th>
            </tr>
          </thead>
          <tbody id="productRows">
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-start align-items-center gap-2">
        <button type="button" class="btn btn-warning" onclick="RegisterUpdateInventaryItens()">Atualizar
          Quantidades
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Seção da Lista de Produtos do Inventário -->
<div class="card mt-4" id="InventaryListProduct" style="display: none;">
  <div class="card-header text-dark">
    Produtos no Inventário
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
            <th>Quantidade Entrada</th>
            <th>Quantidade Saida</th>
            <th>Diferença</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody id="productTable">
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>