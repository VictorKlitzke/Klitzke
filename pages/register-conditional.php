<!-- Container Principal -->
<div class="container-fluid p-4 shadow-lg border rounded-4">
  <div class="card mb-3">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Pedido Condicional</h5>
    </div>
    <div class="card-body">
      <form>
        <!-- Linha 1: Cliente e Datas -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Cliente <span class="text-danger">*</span></label>
            <select id="clients" class="form-select" required>
              <option value="" disabled selected>Selecione o cliente</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Data <span class="text-danger">*</span></label>
            <input type="date" id="date-now" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Prev. de Devolução <span class="text-danger">*</span></label>
            <input type="date" id="date-return" class="form-control" required>
          </div>
        </div>

        <!-- Linha 2: Vendedor -->
        <div class="row mb-3">
          <div class="col-md-12">
            <label for="cliente" class="form-label">Vendedor/Responsável <span class="text-danger">*</span></label>
            <select id="users" class="form-select" required>
              <option disabled selected>Selecione o Responsável</option>
            </select>
          </div>
        </div>

        <!-- Linha 3: Valores -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="subtotal" class="form-label">R$ Sub:</label>
            <input id="sub-total" oninput="updateSubTotal()" class="form-control" placeholder="0,00" disabled>
          </div>
          <div class="col-md-4">
            <label class="form-label">R$ Desconto:</label>
            <input type="text" id="discount" class="form-control" value="0,00" oninput="updateTotal()">
            </div>
          <div class="col-md-4">
            <label for="total" class="form-label">R$ Total:</label>
            <input id="total" oninput="updateTotal()" class="form-control" disabled placeholder="0,00"/>
          </div>
        </div>

        <!-- Linha 4: Observações -->
        <div class="mb-3">
          <label for="observacao" class="form-label">Observação</label>
          <textarea id="obs" class="form-control" rows="3" placeholder="Digite alguma observação"></textarea>
        </div>

        <!-- Linha 5: Produtos -->
        <div class="mb-3">
          <label class="form-label">Produtos/Serviço do Condicional</label>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Produto</th>
                  <th>Quantidade</th>
                  <th>Preço Unitário</th>
                  <th>Total</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="productTableBody"></tbody>
            </table>
          </div>
        </div>

        <!-- Botões -->
        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg"></i> Adicionar Produto
          </button>
          <button onclick="RegisterConditional()" type="button" class="btn btn-success"><i class="bi bi-save"></i> Salvar</button>
          <button type="reset" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addProductModalLabel">Adicionar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addProductForm">
          <div class="mb-3">
            <label class="form-label">Produto</label>
            <select id="product-conditional" class="form-select" required>
              <option value="" disabled selected>Selecione um produto</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" id="quantity" class="form-control" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Preço Unitário</label>
            <input type="number" id="price-unit" class="form-control" placeholder="0,00" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" onclick="addProductTable()" class="btn btn-primary"><i
            class="bi bi-file-earmark-plus"></i> Adicionar</button>
      </div>
    </div>
  </div>
</div>