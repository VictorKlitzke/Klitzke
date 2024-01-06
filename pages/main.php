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
                          products.name products,
                          sales_items.amount quantity,
                          sales_items.price_sales value
                        FROM
                          sales 
                          INNER JOIN sales_items ON sales_items.id_sales = sales.id
                          INNER JOIN products ON products.id = sales_items.id_product
                          INNER JOIN form_payment ON form_payment.id = sales.id_payment_method
                          INNER JOIN clients ON clients.id = sales.id_client
                          LEFT JOIN boxpdv ON boxpdv.id = sales.id_boxpdv
                          LEFT JOIN users ON users.id = sales.id_users
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

<div class="center">
  <div class="box-content">
    <h1 style="display: flex; justify-content: start;">
      Ola,
      <?php echo $_SESSION['name']; ?>
    </h1>
    <p style="color: #ccc; padding-top: 10px;">
      Aqui esta tudo que aconteceu hoje!
    </p>
  </div>
</div>

<div class="center">
  <div class="dashboard">
    <div class="card">
      <h2>Produtos em Estoque</h2>
      <h2>
        <?php echo $result['stock_quantity']; ?>
      </h2>
    </div>

    <div class="card">
      <h2>Clientes</h2>
      <h2>
        <h2>
          <?php echo count($count_clients); ?>
        </h2>
      </h2>
    </div>

    <div class="card">
      <h2>Usuarios</h2>
      <h2>
        <h2>
          <?php echo count($count_users); ?>
        </h2>
      </h2>
    </div>

    <div class="card">
      <h2>Vendas</h2>
      <h2>
        <?php echo count($count_sales); ?>
      </h2>
    </div>

    <div class="card">
      <h2>Produto + Vendido</h2>
      <h2>
        <?php echo $result_prod['name_product']; ?>
      </h2>
    </div>
    <div class="clear"></div>
  </div>
</div>

<?php

$sql = Db::Connection();

$products = $sql->prepare("SELECT name as product, stock_quantity as product_stock_quantity, status_product FROM products WHERE status_product = 'negativado'");
$products->execute();
$status_product = $products->fetchAll();

?>

<div class="center">
  <div class="box-content">
    <h2>Produtos negativados</h2>
    <div class="list">
      <table>
        <thead>
          <tr>
            <td>Nome</td>
            <td>Quantidade</td>
            <td>Status</td>
          </tr>
        </thead>

        <?php

        foreach ($status_product as $row => $value) {

          ?>

          <tbody>
            <tr>
              <td>
                <?php echo $value['product']; ?>
              </td>
              <td>
                <?php echo $value['product_stock_quantity']; ?>
              </td>
              <td>
                <?php echo $value['status_product']; ?>
              </td>
            </tr>
          </tbody>

        <?php } ?>

      </table>
    </div>
  </div>
</div>

<div class="center">
  <div class="box-content">
    <div class="list">
      <h2>Lista das 10 ultimas vendas</h2>
      <table>
        <thead>

          <tr>
            <td>Usuario</td>
            <p>
              <td>Cliente</td>
            </p>
            <p>
              <td>Forma de Pagamento</td>
            </p>
            <td>Produto</td>
            <p>
              <td>Quantidade</td>
            </p>
            <p>
              <td>Valor</td>
            </p>
          </tr>

        </thead>

        <?php

        foreach ($result_sales as $key => $value) {

          ?>

          <tbody>

            <tr>
              <p>
                <td>
                  <?php echo $value['users']; ?>
                </td>
              </p>
              <td>
                <?php echo $value['clients']; ?>
              </td>
              <td>
                <?php echo $value['form_payment']; ?>
              </td>
              <td>
                <?php echo $value['products']; ?>
              </td>
              <td>
                <?php echo $value['quantity']; ?>,00
              </td>
              <td>
                <?php echo $value['value']; ?>
              </td>

              <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                <div>
                  <p class="btn-reopen">
                    <a href="<?php echo INCLUDE_PATH ?>edit-sales?id=<?php echo base64_encode($value['id']); ?>">Reabrir
                      venda</a>
                  </p>
                </div>
                <div>
                  <p class="btn-delete">
                    <a href="">Cancelar venda
                    </a>
                  </p>
                </div>
                <button id="details-<?php echo $Key; ?>" onclick="InfoSales(<?php echo $key; ?>,'<?php echo $value['users']; ?>','<?php echo $value['clients']; ?>','<?php echo $value['form_payment']; ?>','<?php echo $value['quantity']; ?>','<?php echo $value['value']; ?>','<?php echo $value['total_value']; ?>')" class="btn-details">
                  <p>Mais detalhes</p>
                </button>
              </td>
            </tr>
          </tbody>

        <?php } ?>

      </table>
    </div>
  </div>
</div>

<div class="overlay-home" id="overlay-home">
  <div class="product-info-sales" id="product-info-sales">
    <div class="navbar-product-info-sales">
      <h2>informação da venda</h2>
      <svg id="product-info-modal" style="cursor: pointer;" fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24px"
        viewBox="0 0 24 24" width="24px" fill="#000000">
        <path d="M0 0h24v24H0z" fill="none" />
        <path
          d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
      </svg>
    </div>
    <div class="box-content">
      <div class="center">
        <div class="info-sales-product">
          <?php $info_sales = Controllers::InfoProductsSales('sales'); ?>
          <div>
            <span id="user"></span>
          </div>
          <div>
            <span id="client"></span>
          </div>
          <div>
            <span id="form_payment"></span>
          </div>
          <div>
            <span id="product"></span>
          </div>
          <div>
            <span id="quantity"></span>
          </div>
          <div>
            <span id="value"></span>
          </div>
          <div>
            <span id="value_total"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>





<script src="<?php echo INCLUDE_PATH; ?>./js/product_info_sales.js"></script> 