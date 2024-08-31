<div class="box-content">
  <h2 class="text-white">Controle Financeiro</h2>
  <!-- Cards de Resumo -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Saldo Atual</h5>
          <p class="card-text" id="saldoAtual">R$ 12.000,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">Receitas do Mês</h5>
          <p class="card-text" id="receitasMes">R$ 8.000,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Despesas do Mês</h5>
          <p class="card-text" id="despesasMes">R$ 5.000,00</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <h5 class="card-title">Resultado do Mês</h5>
          <p class="card-text" id="resultadoMes">R$ 3.000,00</p>
        </div>
      </div>
    </div>
  </div>
  <!-- Filtros -->
  <div class="row mb-4">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Buscar por descrição...">
    </div>
    <div class="col-md-4">
      <select class="form-select">
        <option selected>Filtrar por tipo</option>
        <option value="1">Receita</option>
        <option value="2">Despesa</option>
      </select>
    </div>
    <div class="col-md-4">
      <input type="date" class="form-control">
    </div>
  </div>

  <!-- Tabela de Transações -->
  <div class="table-responsive mb-4">
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Data</th>
          <th>Descrição</th>
          <th>Tipo</th>
          <th>Valor</th>
          <th>Parcelas</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>01/08/2024</td>
          <td>Venda de Produto</td>
          <td>Receita</td>
          <td class="text-success">R$ 1.000,00</td>
          <td>1</td>
          <td>
            <button class="btn btn-sm btn-warning">Editar</button>
            <button class="btn btn-sm btn-danger">Excluir</button>
          </td>
        </tr>
        <!-- Mais linhas podem ser adicionadas aqui -->
      </tbody>
    </table>
  </div>

  <!-- Formulário de Adição de Transações -->
  <div class="card mb-4">
    <div class="card-header">
      Adicionar Nova Transação
    </div>
    <div class="card-body">
      <form>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="dataTransacao" class="form-label">Data</label>
            <input type="date" class="form-control" id="dataTransacao" required>
          </div>
          <div class="col-md-4">
            <label for="tipoTransacao" class="form-label">Tipo</label>
            <select class="form-select" id="tipoTransacao" required>
              <option selected disabled>Escolha...</option>
              <option value="Receita">Receita</option>
              <option value="Despesa">Despesa</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="valorTransacao" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valorTransacao" placeholder="R$" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="descricaoTransacao" class="form-label">Descrição</label>
          <input type="text" class="form-control" id="descricaoTransacao" placeholder="Descrição da transação" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Transação</button>
      </form>
    </div>
  </div>
</div>