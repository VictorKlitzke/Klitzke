<?php

$sql = Db::Connection();
$stock = $sql->prepare("SELECT SUM(stock_quantity) as stock_quantity FROM products WHERE status_product = 'Em estoque'");
$stock->execute();
$result = $stock->fetch(PDO::FETCH_ASSOC);

?>

<?php

$sql = Db::Connection();
$sales = $sql->prepare("SELECT 
                        sales.*,
                        users.name users,
                        clients.name clients,
                        form_payment.name form_payment,
                        case 
                            when sales.status = 1 then 'VENDIDO'
                            when sales.status = 2 then 'CANCELADA'
                            else 'ERRO'
                        end status_sales
                        FROM
                        sales 
                        INNER JOIN form_payment ON form_payment.id = sales.id_payment_method
                        LEFT JOIN clients ON clients.id = sales.id_client
                        INNER JOIN boxpdv ON boxpdv.id = sales.id_boxpdv
                        INNER JOIN users ON users.id = sales.id_users
                        ORDER BY sales.date_sales LIMIT 10");

$sales->execute();
$result_sales = $sales->fetchAll();

?>

<?php

$sql = Db::Connection();
$best_selling_product = $sql->prepare("SELECT
                                          p.id,
                                          p.name name_product,
                                          s.price_sales value_sales_item,
                                          SUM(s.amount) as total_sold
                                        FROM sales_items s
                                          JOIN products p ON s.id_product = p.id
                                        GROUP BY
                                          p.id, p.name, s.price_sales
                                        ORDER BY
                                          total_sold DESC;
                                        ");
$best_selling_product->execute();
$result_prod = $best_selling_product->fetch(PDO::FETCH_ASSOC);

?>

<?php

$count_clients = Controllers::SelectAll('clients');
$count_users = Controllers::SelectAll('users');
$count_sales = Controllers::SelectAll('sales');

?>

<div class="bg-primary text-light p-4">
  <div class="row justify-content-between align-items-center">
    <div class="col-lg-6 text-start">
      <h1 class="display-5 fw-bold mb-3" style="text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">
        Olá, <?php echo htmlspecialchars($_SESSION['name']); ?>
      </h1>
      <p class="lead mb-0" style="opacity: 0.9;">
        Aqui está tudo o que aconteceu até o momento!
      </p>
    </div>
  </div>
</div>

<br>

<div class="container-fluid card bg-light p-4 rounded-3 shadow-sm">
  <div class="row">
    <div class="col" style="max-height: 700px; overflow-y: auto;">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Quadro de Avisos</h2>
        <button id="toggle-btn" class="btn btn-outline-primary" onclick="toggleNoticeBoard()">
          <i id="toggle-icon" class="fas fa-chevron-down" style="font-size: 1.5rem;"></i>
        </button>
      </div>
      <div aria-live="polite" aria-atomic="true">
        <table class="table table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Conta</th>
              <th scope="col">Valor</th>
              <th scope="col">Data</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody id="notice-board" class="notice-board d-none">
          </tbody>
          <tbody class="d-none" id="no-data">
            <tr>
              <td colspan="4" class="text-center text-muted">Nenhum aviso disponível</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<br>

<div class="row g-4">
  <div class="col-sm-6">
    <div class="card border-light shadow-sm rounded-3">
      <div class="card-body text-center">
        <h4 class="card-title text-primary fw-bold">Produtos em Estoque</h4>
        <h2 class="display-5 fw-bold text-primary mb-0">
          <?php echo $result['stock_quantity']; ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-sm-6">
    <div class="card border-light shadow-sm rounded-3">
      <div class="card-body text-center">
        <h4 class="card-title text-success fw-bold">Clientes</h4>
        <h2 class="display-5 fw-bold text-success mb-0">
          <?php echo count($count_clients); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-sm-6">
    <div class="card border-light shadow-sm rounded-3">
      <div class="card-body text-center">
        <h4 class="card-title text-info fw-bold">Vendas</h4>
        <h2 class="display-5 fw-bold text-info mb-0">
          <?php echo count($count_sales); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-sm-6">
    <div class="card border-light shadow-sm rounded-3">
      <div class="card-body text-center">
        <h4 class="card-title text-warning fw-bold">Produto Mais Vendido</h4>
        <h2 class="display-5 fw-bold text-warning mb-0">
          <?php echo htmlspecialchars($result_prod['name_product']); ?>
        </h2>
      </div>
    </div>
  </div>
</div>

<br>

<?php

$sql = Db::Connection();

$products = $sql->prepare("SELECT name as product, stock_quantity as product_stock_quantity, status_product FROM products WHERE status_product = 'negativado'");
$products->execute();
$status_product = $products->fetchAll();

?>

<div class="container-fluid card bg-light shadow-sm p-4 rounded-4">
  <div class="row">
    <div class="col">
      <h2 class="text-primary mt-2" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);">Produtos Negativados</h2>
      <div class="mt-4">
        <table class="table table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Quantidade</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($status_product as $row => $value) { ?>
              <tr>
                <th scope="row" class="text-dark"><?php echo $value['product']; ?></th>
                <td class="text-dark"><?php echo $value['product_stock_quantity']; ?></td>
                <td class="text-dark">
                  <span class="badge 
                      <?php echo ($value['status_product'] === 'Ativo') ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo $value['status_product']; ?>
                  </span>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<br>

<div class="container-fluid card bg-light shadow-sm p-4 rounded-4">
  <div class="row">
    <div class="col">
      <h2 class="text-primary mt-2" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);">Lista das 10 Últimas Vendas
      </h2>
      <div class="mt-4" style="max-height: 700px; overflow-y: auto;">
        <table class="table table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Usuário</th>
              <th scope="col">Cliente</th>
              <th scope="col">Forma de Pagamento</th>
              <th scope="col">Status Venda</th>
              <th scope="col">Valor Total</th>
              <th scope="col">Data</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody style="white-space: nowrap;">
            <?php foreach ($result_sales as $key => $value): ?>
              <tr>
                <th scope="row"><?php echo htmlspecialchars($value['id']); ?></th>
                <td><?php echo htmlspecialchars($value['users']); ?></td>
                <td><?php echo htmlspecialchars($value['clients'] ?? 'Cliente consumidor final'); ?></td>
                <td><?php echo htmlspecialchars($value['form_payment']); ?></td>
                <td><?php echo htmlspecialchars($value['status_sales']); ?></td>
                <td><?php echo htmlspecialchars($value['total_value']); ?></td>
                <td><?php echo htmlspecialchars($value['date_sales']); ?></td>
                <td>
                  <?php if ($value['status'] == 2): ?>
                    <button onclick="ReopenSales(this)" type="button" data-id="<?php echo $value['id']; ?>"
                      class="btn btn-info">Reabrir venda</button>
                  <?php else: ?>
                    <button onclick="CancelSales(this)" data-id="<?php echo $value['id']; ?>" type="button"
                      class="btn btn-danger">Cancelar venda</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>