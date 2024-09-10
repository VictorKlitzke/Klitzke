<div class="container-fluid p-4">
  <h2>Dashboard</h2>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>