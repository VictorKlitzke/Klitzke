<div class="container-fluid p-4 bg-light border rounded-4">
  <h2>Dashboard</h2>
  <hr class="border-bottom" />
  <br>
  <div class="row">
    <div class="col-lg-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
          <h4 class="card-title mb-0">Dashboard Controle Financeiro</h4>
        </div>
        <div class="card-body">
          <canvas id="total-financial-control"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h4 class="card-title mb-0">Rendimento por usuário</h4>
        </div>
        <div class="card-body">
          <canvas id="sales-users"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h4 class="card-title mb-0">Rendimento todos os meses</h4>
        </div>
        <div class="card-body">
          <canvas id="total-sales"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="card-title mb-0">Rendimento por mês</h4>
        </div>
        <div class="card-body">
          <div id="best-month"></div>
          <canvas id="total-date-sales"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="card-title mb-0">Fechamento de Caixa - Visão Geral</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Gráfico de barras -->
            <div class="mb-4 col-lg-6">
              <h5 class="text-center">Comparativo de Tipos de Pagamento</h5>
              <canvas id="chartBar"></canvas>
            </div>
            <!-- Gráfico de linhas -->
            <div class="mb-4 col-lg-6">
              <h5 class="text-center">Evolução de Valores no Sistema e Físico</h5>
              <canvas id="chartLine"></canvas>
            </div>
          </div>
          <div class="row">
            <!-- Gráfico de pizza -->
            <div class="mb-4 col-lg-6">
              <h5 class="text-center">Proporção de Tipos de Pagamento</h5>
              <canvas id="chartPie"></canvas>
            </div>
            <!-- Gráfico de barras empilhadas -->
            <div class="mb-4 col-lg-6">
              <h5 class="text-center">Soma e Diferença no Fechamento</h5>
              <canvas id="chartStacked"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>