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

<div class="box-content">
  <div class="row">
    <div class="col">
      <h1 class="text-white">
        Ola,
        <?php echo $_SESSION['name']; ?>
      </h1>
      <p class="text-white">
        Aqui esta tudo que aconteceu até o momento!
      </p>
    </div>
  </div>
</div>

<div class="container-fluid card">
  <div class="row">
    <div class="col" style="max-height: 700px; overflow-y: auto; overflow-x: auto;">
      <h2 class="text-black mb-4">Quadro de Avisos</h2>
      <div aria-live="polite" aria-atomic="true" class="d-flex flex-wrap justify-content-start">
        <table>
        <div class="notice-board row"></div> 
        </table>
      </div>
    </div>
  </div>
</div>

<br>

<div class="row">
  <div class="col-sm-6 mb-3">
    <div class="card border-primary shadow">
      <div class="card-body">
        <h4 class="card-title text-primary">Produtos em Estoque</h4>
        <h2 class="display-5 fw-bold text-center">
          <?php echo $result['stock_quantity']; ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-sm-6 mb-3">
    <div class="card border-success shadow">
      <div class="card-body">
        <h4 class="card-title text-success">Clientes</h4>
        <h2 class="display-5 fw-bold text-center">
          <?php echo count($count_clients); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-sm-6 mb-3">
    <div class="card border-info shadow">
      <div class="card-body">
        <h4 class="card-title text-info">Vendas</h4>
        <h2 class="display-5 fw-bold text-center">
          <?php echo count($count_sales); ?>
        </h2>
      </div>
    </div>
  </div>

  <div class="col-sm-6 mb-3">
    <div class="card border-warning shadow">
      <div class="card-body">
        <h4 class="card-title text-warning">Produto + Vendido</h4>
        <h2 class="display-5 fw-bold text-center">
          <?php echo $result_prod['name_product']; ?>
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

<div class="box-content">
  <div class="row">
    <div class="col">
      <h2 class="text-white mb-4">Produtos Negativados</h2>
      <div class="table-responsive">
        <table class="table table-dark table-hover">
          <thead>
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Quantidade</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($status_product as $row => $value) {
              ?>
              <tr>
                <th scope="row"><?php echo $value['product']; ?></th>
                <td><?php echo $value['product_stock_quantity']; ?></td>
                <td><?php echo $value['status_product']; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="box-content">
  <div class="row">
    <div class="col" style="max-height: 700px;">
      <h2 class="text-white mb-4">Lista das 10 ultimas vendas</h2>
      <div class="table-responsive" style="max-height: 700px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover">
          <thead style="white-space: nowrap;">
            <th scope="col">#</th>
            <th scope="col">Usuario</th>
            <th scope="col">Cliente</th>
            <th scope="col">Forma de Pagamento</th>
            <th scope="col">Status Venda</th>
            <th scope="col">Valor Total</th>
            <th scope="col">Data</th>
            <th scope="col">Ações</th>
          </thead>

          <?php

          foreach ($result_sales as $key => $value) {

            ?>

            <tbody style="white-space: nowrap;">

              <tr>
                <th scope="row"><?php echo htmlspecialchars($value['id']); ?></th>
                <th> <?php echo htmlspecialchars($value['users']); ?></th>

                <?php if (htmlspecialchars($value['clients']) == null) {
                  echo '<td><p>' . 'Cliente consumidor final' . '</p></td>';
                } else {
                  echo '<td><p>' . htmlspecialchars($value['clients']) . '</p></td>';
                }
                ?>
                <th><?php echo htmlspecialchars($value['form_payment']); ?></th>
                <th><?php echo htmlspecialchars($value['status_sales']); ?></th>
                <th><?php echo htmlspecialchars($value['total_value']); ?></th>
                <th><?php echo htmlspecialchars($value['date_sales']); ?></th>

                <th>
                  <?php

                  if ($value['status'] == 2) {

                    ?>

                    <button onclick="ReopenSales(this)" type="button" data-id="<?php echo $value['id'] ?>"
                      class="btn btn-info accessnivel">Reabrir venda
                    </button>

                  <?php } else { ?>

                    <button onclick="CancelSales(this)" data-id="<?php echo $value['id']; ?>" type="button"
                      class="btn btn-danger accessnivel">Cancelar venda
                    </button>

                  <?php } ?>

                  <!-- <button onclick="Details(this)" data-id="<?php echo $value['id']; ?>" type="button"
                      class="btn btn-primary">
                      Mais detalhes
                    </button> -->
                </th>
              </tr>
            </tbody>

          <?php } ?>

        </table>
      </div>
    </div>
  </div>
</div>

<div class="overlay-details" id="overlay-details">
  <div id="modal-print" class="modal">
    <div class="modal-content-details" id="modal-content-details">
      <span class="close-details" onclick="CloseModalInfo()" id="close-details">&times;</span>
      <h1>Venda ID: <span id="saleId"></span></h1>
      <table id="modalTable" border="1">
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Status</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor</th>
            <th>Forma de pagamento</th>
            <th>Usuario</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>