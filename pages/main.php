<?php

$sql = Db::Connection();
$stock = $sql->prepare("SELECT SUM(stock_quantity) as stock_quantity FROM products");
$stock->execute();
$result = $stock->fetch(PDO::FETCH_ASSOC);

?>

<div class="center">
  <div class="dashboard">
    <div class="card">
      <h2>Produtos em Estoque</h2>
      <h2 id="estoqueQuantidade"><?php echo $result['stock_quantity']; ?></h2>
    </div>

    <div class="card">
      <h2>Produtos Mais Vendidos</h2>
      <ul id="produtosMaisVendidos">
        <!-- <li>Produto A - 50 unidades</li>
      <li>Produto B - 40 unidades</li>
      <li>Produto C - 30 unidades</li> -->
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>