<?php

$products = Controllers::SelectAll('products');

?>

<div class="box-content">
    <h2 class="text-white mb-4">Lista de Produtos</h2>
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr style="white-space: nowrap;">
                            <th scope="col">Produtos</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col"> Codigo de barras</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Valor Custo</th>
                            <th scope="col">Quantidade em estoque</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Usuário cadastro</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <?php

                    foreach ($products as $key => $value) {

                        ?>

                        <tbody>
                            <tr style="white-space: nowrap;"
                                class="<?php echo $value['status_product'] == 'negativado' ? 'table-warning' : ''; ?>">
                                <th><?php echo htmlspecialchars($value['name']); ?></th>
                                <th><?php echo htmlspecialchars($value['quantity']); ?></th>
                                <th><?php echo htmlspecialchars($value['barcode']); ?></th>
                                <th>R$ <?php echo htmlspecialchars($value['value_product']); ?></th>
                                <th>R$ <?php echo htmlspecialchars($value['cost_value']); ?></th>
                                <th><?php echo htmlspecialchars($value['stock_quantity']); ?></th>
                                <th><?php echo htmlspecialchars($value['reference']); ?></th>
                                <th><?php echo htmlspecialchars($value['id_users']); ?></th>
                                <th><?php echo htmlspecialchars($value['model']); ?></th>
                                <th>
                                    <a onclick="ShowOnPage(this)" data-id="<?php echo $value['id']; ?>"
                                        class="btn btn-dark">
                                        Mostrar na pagina </a>

                                    <?php if ($value['status_product'] == 'negativado') { ?>
                                        <button class="btn btn-info">Fazer Solicitação</button>
                                    <?php } else { ?>
                                        <button class="d-none"></button>
                                    <?php } ?>
                                </th>
                            </tr>
                        </tbody>

                    <?php } ?>

                </table>
            </div>
        </div>
    </div>
</div>

<?php
$sql = Db::Connection();
$exec = $sql->prepare("SELECT SUM(value_product) as value_product FROM products");
$exec->execute();
$result_value_product = $exec->fetch(PDO::FETCH_ASSOC);

$sql = Db::Connection();
$exec = $sql->prepare("SELECT SUM(quantity) as quantity FROM products");
$exec->execute();
$result_quantity_product = $exec->fetch(PDO::FETCH_ASSOC);

$sql = Db::Connection();
$exec = $sql->prepare("SELECT SUM(cost_value) as cost_value FROM products");
$exec->execute();
$result_cost_value_product = $exec->fetch(PDO::FETCH_ASSOC);

$value_product = number_format($result_value_product['value_product'], 2, ',', '.');
$cost_value_product = number_format($result_cost_value_product['cost_value'], 2, ',', '.');
?>

<div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Quantidade total de itens</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $result_quantity_product['quantity']; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Total valores produtos</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $value_product; ?> Reais</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Total valores de custo</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $cost_value_product; ?> Reais</h5>
                </div>
            </div>
        </div>
    </div>
</div>