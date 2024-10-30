<?php

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'financial-control';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

?>
<div class="container-fluid bg-light p-4 shadow-lg rounded-4 border-">
  <h2 class="text-dark">Controle Financeiro</h2>
  <hr class="border-bottom">
  <div class="row mb-4">
    <div class="col-md-4 col-lg-3 mb-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Saldo Atual</h5>
          <p class="card-text" id="saldoAtual"></p>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-lg-3 mb-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">Total Contas Pagas</h5>
          <p class="card-text" id="totalContasPagas"></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Total Contas Vencidas</h5>
          <p class="card-text" id="totalContasVencidas"></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <h5 class="card-title">Total Contas a Pagar</h5>
          <p class="card-text" id="totalContasAPagar"></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <h5 class="card-title">Total Vendas</h5>
          <p class="card-text" id="totalAllVendas"></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-12">
      <input class="form-control" id="input-financial-control" type="search" placeholder="Pesquisar"
        aria-label="Pesquisar">
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="sales-tab" data-bs-toggle="tab" href="#sales" role="tab" aria-controls="sales"
        aria-selected="true">Contas a Receber</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="financial-tab" data-bs-toggle="tab" href="#financial" role="tab" aria-controls="financial"
        aria-selected="false">Saidas</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="entry-tab" data-bs-toggle="tab" href="#entry" role="tab" aria-controls="entry"
        aria-selected="false">Entradas</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="allsales-tab" data-bs-toggle="tab" href="#allsales" role="tab" aria-controls="allsales"
        aria-selected="false">Todas as Vendas</a>
    </li>
  </ul>

  <!-- Conteúdo das abas -->
  <div class="tab-content" id="myTabContent">
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

    <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover table-striped table-bordered">
          <thead>
            <tr style="white-space: nowrap;">
              <th>#</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Data</th>
              <th>Data Liquidação</th>
              <th>Tipo</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="result-financial-control"></tbody>
        </table>
      </div>
    </div>

    <div class="tab-pane fade" id="entry" role="tabpanel" aria-labelledby="entry-tab">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover table-striped table-bordered">
          <thead>
            <tr style="white-space: nowrap;">
              <th>#</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Data</th>
              <th>Tipo</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="result-entry"></tbody>
        </table>
      </div>
    </div>

    <div class="tab-pane fade" id="allsales" role="tabpanel" aria-labelledby="allsales-tab">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover table-striped table-bordered">
          <thead>
            <tr style="white-space: nowrap;">
              <th>#</th>
              <th>Clientes</th>
              <th>Formas de Pagamentos</th>
              <th>Valor Total</th>
              <th>Data da Venda</th>
            </tr>
          </thead>
          <tbody id="allsales-result"></tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="detailsModalLabel">Detalhes da Parcela</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered" id="table-financial-control-detals">
              <thead class="table-primary text-center">
                <tr>
                  <th>Selecionar</th>
                  <th>#</th>
                  <th>Data Vencimento</th>
                  <th>Valor</th>
                  <th>Status</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody id="financial-control-result-detals" class="text-center">
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Fechar
          </button>
          <button type="button" onclick="FinalizeAPrazo()" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Faturar
          </button>
        </div>
      </div>
    </div>
  </div>

  <br>
  <div class="card mb-4">
    <div class="card-header">
      Adicionar Entradas/Saidas
    </div>
    <div class="card-body">
      <form>
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Numero Doc</label>
            <input type="number" class="form-control" id="numberdoc" placeholder="Opcional">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
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
            <label class="form-label">Nome externo/Empresa</label>
            <input type="text" class="form-control" id="nameExterno" placeholder="Nome" required>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
          <div class="col-md-4">
            <label class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descriptionTransaction" placeholder="Descrição da transação"
              required>
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
          </div>
          <div class="col-md-4">
            <label class="form-label">Categoria</label>
            <select class="form-select" id="transactionType" required>
              <option value="" disabled selected>Selecione uma categoria</option>
              <option value="contas a pagar">Contas a Pagar</option>
              <option value="contas a receber">Contas a Receber</option>
            </select>
            <span id="errorType" class="error-message">Campo está inválido, ajuste se possível.</span>
          </div>
          <div class="col-md-12">
            <label class="form-label">Status</label>
            <select class="form-select" id="incomeExpense" required>
              <option value="" disabled selected>Selecione uma categoria</option>
              <option value="Despesa">Despesa</option>
              <option value="Receita">Receita</option>
            </select>
            <span id="errorType" class="error-message">Campo está inválido, ajuste se possível.</span>
          </div>
        </div>
        <button type="button" onclick="RegisterAccountsPayable()" class="btn btn-primary">Adicionar Transação</button>
      </form>
    </div>
  </div>
</div>