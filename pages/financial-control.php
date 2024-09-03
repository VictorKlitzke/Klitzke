<div class="box-content">
  <h2 class="text-white">Controle Financeiro</h2>
  <!-- Cards de Resumo -->
  <div class="row mb-4">
    <div class="col-md-4 col-lg-3 mb-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Saldo Atual</h5>
          <p class="card-text" id="saldoAtual">R$ 00,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-lg-3 mb-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">Receitas do Mês</h5>
          <p class="card-text" id="receitasMes">R$ 00,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-lg-3 mb-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">Total Contas Pagas</h5>
          <p class="card-text" id="totalContasPagas">R$ 00,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Total Contas Vencidas</h5>
          <p class="card-text" id="totalContasVencidas">R$ 00,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <h5 class="card-title">Total Contas a Pagar</h5>
          <p class="card-text" id="totalContasAPagar">R$ 00,00</p>
        </div>
      </div>
    </div>
  </div>
  <!-- Filtros -->
  <div class="row mb-4">
    <div class="col-md-12">
      <input class="form-control" id="input-financial-control" type="search" placeholder="Pesquisar"
        aria-label="Pesquisar">
    </div>
  </div>

  <!-- Tabela de Transações -->
  <!-- Estrutura de Abas -->
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" style="color: #696969;" id="sales-tab" data-bs-toggle="tab"
        data-bs-target="#sales" type="button" role="tab" aria-controls="sales" aria-selected="true">Controle Contas a
        Receber</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="financial-tab" style="color: #696969;" data-bs-toggle="tab"
        data-bs-target="#financial" type="button" role="tab" aria-controls="financial" aria-selected="false">Controle
        Contas a pagar</button>
    </li>
  </ul>

  <!-- Conteúdo das Abas -->
  <div class="tab-content" id="myTabContent">
    <!-- Aba de Vendas -->
    <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover table-striped table-bordered" id="table-sales">
          <thead>
            <tr style="white-space: nowrap;">
              <th>#</th>
              <th>Clientes</th>
              <th>Formas de Pagamentos</th>
              <th>Parcelas</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="sales-result"></tbody>
        </table>
      </div>
    </div>

    <!-- Aba de Controle Financeiro -->
    <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover table-striped table-bordered" id="table-financial-control">
          <thead>
            <tr style="white-space: nowrap;">
              <th>#</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Data</th>
              <th>Tipo</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="financial-control-result"></tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="detailsModalLabel">Detalhes da Parcela</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered" id="table-financial-control-detals">
              <thead class="table-dark">
                <tr>
                  <th>Selecionar</th>
                  <th>#</th>
                  <th>Valor</th>
                  <th>Data Vencimento</th>
                  <th>Status</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody id="financial-control-result-detals">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" onclick="FinalizeAPrazo()" class="btn btn-primary">Faturar</button>
        </div>
      </div>
    </div>
  </div>

  <br>

  <!-- Formulário de Adição de Transações -->
  <div class="card mb-4">
    <div class="card-header">
      Adicionar Contas a Pagar
    </div>
    <div class="card-body">
      <form>
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Data</label>
            <input type="date" class="form-control" id="dateTransaction" required>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
          <div class="col-md-4">
            <label for="valorTransacao" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valueTransaction" placeholder="R$" required>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nome externo</label>
            <input type="text" class="form-control" id="nameExterno" placeholder="Nome" required>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
          <div class="col-md-12">
            <label class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descriptionTransaction" placeholder="Descrição da transação"
              required>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
        </div>
        <button type="button" onclick="RegisterAccountsPayable()" class="btn btn-primary">Adicionar Transação</button>
      </form>
    </div>
  </div>
</div>