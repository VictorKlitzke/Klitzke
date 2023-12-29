<?php

$sql = Db::Connection();
$stock = $sql->prepare("SELECT SUM(stock_quantity) as stock_quantity FROM products");
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

<div class="center">
  <div class="dashboard">
    <div class="card">
      <h2>Produtos em Estoque</h2>
      <h2 id="estoqueQuantidade">
        <?php echo $result['stock_quantity']; ?>
      </h2>
    </div>

    <div class="card">
      <h2>Produtos em Estoque</h2>
      <h2 id="estoqueQuantidade">
        <?php echo $result['stock_quantity']; ?>
      </h2>
    </div>

    <div class="card">
      <h2>Produtos em Estoque</h2>
      <h2 id="estoqueQuantidade">
        <?php echo $result['stock_quantity']; ?>
      </h2>
    </div>

    <div class="card">
      <h2>Produtos em Estoque</h2>
      <h2 id="estoqueQuantidade">
        <?php echo $result['stock_quantity']; ?>
      </h2>
    </div>

    <div class="card">
      <h2>Produtos Mais Vendidos</h2>
      <!-- <li>Produto A - 50 unidades</li>
      <li>Produto B - 40 unidades</li>
      <li>Produto C - 30 unidades</li> -->
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>

<div class="box-content">
  <div class="list">
    <h2>Lista das 10 ultimas vendas</h2>
    <table>
      <thead>
        <tr>

          <td>Usuario</td>
          <p><td>Cliente</td></p>
          <p><td>Forma de Pagamento</td></p>
          <td>Produto</td>
          <p><td>Quantidade</td></p>
          <p><td>Valor</td></p>

        </tr>
      </thead>

      <?php

        foreach ($result_sales as $key => $value) {

      ?>

      <tbody>

        <tr>
          <p><td><?php echo $value['users']; ?></td></p>
          <td><?php echo $value['clients']; ?></td>
          <td><?php echo $value['form_payment']; ?></td>
          <td><?php echo $value['products']; ?></td>
          <td><?php echo $value['quantity']; ?>,00</td>
          <td><?php echo $value['value']; ?></td>

          <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
            <div>
              <a class="btn-edit"
                href="<?php echo INCLUDE_PATH ?>edit-sales?id=<?php echo base64_encode($value['id']); ?>">Reabrir
                venda</a>
            </div>
            <div>
              <a class="btn-edit"
                href="<?php echo INCLUDE_PATH ?>edit-sales?id=<?php echo base64_encode($value['id']); ?>">Cancelar
                venda</a>
            </div>
            <div>
              <a class="btn-delete"
                href="<?php echo INCLUDE_PATH ?>list-sales?delete=<?php echo base64_encode($value['id']); ?>">Imprimir</a>
            </div>
          </td>
        </tr>
      </tbody>

      <?php } ?>

    </table>
  </div>
</div>