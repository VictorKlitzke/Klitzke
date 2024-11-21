<?php

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$page_permission = 'list-products';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
}

$products = Controllers::SelectAll('products');

?>

<div class="container-fluid p-4 shadow-lg border rounded-4 bg-light">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-dark mb-4">Lista de Produtos</h2>
        <a class="btn btn-success fw-bold px-4" <?php SelectedMenu('register-users') ?>
            href="<?php echo INCLUDE_PATH; ?>register-stockcontrol">+ Novo Produto</a>
    </div>
    <input type="text" id="searchProduct" class="form-control border mb-3" placeholder="Buscar produto..."
        onkeyup="searchListProduct()">
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                <table class="table table-striped table-hover align-middle text-center bg-white rounded-3 shadow-sm">
                    <thead class="table-dark text-light">
                        <tr style="white-space: nowrap;">
                            <th scope="col">#</th>
                            <th scope="col">Produtos</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Codigo de barras</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Valor Custo</th>
                            <th scope="col">Quantidade em estoque</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Usuário cadastro</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Situação</th>
                            <th scope="col">Unidade</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <?php

                    foreach ($products as $key => $value) {

                        ?>

                        <tbody>
                            <tr style="white-space: nowrap;"
                                class="<?php echo $value['status_product'] == 'negativado' ? 'table-warning' : ''; ?>">
                                <th><?php echo htmlspecialchars($value['id']); ?></th>
                                <th><?php echo htmlspecialchars($value['name']); ?></th>
                                <th><?php echo htmlspecialchars($value['quantity']); ?></th>
                                <th>
                                    <?php if (htmlspecialchars($value['barcode']) == "") { ?>
                                        <?php echo 'Sem Código de Barras'; ?>
                                    <?php } else { ?>
                                        <?php echo htmlspecialchars($value['barcode']); ?>
                                    <?php } ?>
                                </th>
                                <th>
                                    <?php if (htmlspecialchars($value['brand']) == "") { ?>
                                        <?php echo 'Sem Marca'; ?>
                                    <?php } else { ?>
                                        <?php echo htmlspecialchars($value['brand']); ?>
                                    <?php } ?>
                                </th>
                                <th>R$ <?php echo htmlspecialchars($value['value_product']); ?></th>
                                <th>
                                    <?php if (htmlspecialchars($value['cost_value']) == "") { ?>
                                        <?php echo 'Sem Valor de custo'; ?>
                                    <?php } else { ?>
                                        <?php echo 'R$ ' . htmlspecialchars($value['cost_value']); ?>
                                    <?php } ?>
                                </th>
                                <th><?php echo htmlspecialchars($value['stock_quantity']); ?></th>
                                <th>
                                    <?php if (htmlspecialchars($value['reference']) == "") { ?>
                                        <?php echo 'Sem Referencia'; ?>
                                    <?php } else { ?>
                                        <?php echo htmlspecialchars($value['reference']); ?>
                                    <?php } ?>
                                </th>
                                <th><?php echo htmlspecialchars($value['id_users']); ?></th>
                                <th>
                                    <?php if (htmlspecialchars($value['model']) == "") { ?>
                                        <?php echo 'Sem modelo'; ?>
                                    <?php } else { ?>
                                        <?php echo htmlspecialchars($value['model']) ?>
                                    <?php } ?>
                                </th>
                                <th><?php echo htmlspecialchars($value['status_product']); ?></th>
                                <th><?php echo htmlspecialchars($value['units']); ?></th>
                                <th>
                                    <!-- <button onclick="ShowOnPage(this)" data-id="<?php echo $value['id']; ?>" class="btn btn-dark">
                                        Mostrar na pagina
                                    </button> -->
                                    <a class="btn btn-info accessnivel"
                                        href="<?php echo INCLUDE_PATH ?>edit-products?id=<?php echo base64_encode($value['id']); ?>">Editar
                                    </a>
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
$result_quantity_product = $result_quantity_product['quantity'];
?>

<br>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow-lg rounded">
            <div class="card-header fw-bold">
                <i class="fas fa-box-open me-2"></i>Quantidade total de itens
            </div>
            <div class="card-body text-center">
                <h5 class="card-title display-6"><?php echo $result_quantity_product; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow-lg rounded">
            <div class="card-header fw-bold">
                <i class="fas fa-dollar-sign me-2"></i>Total valores produtos
            </div>
            <div class="card-body text-center">
                <h5 class="card-title display-6"><?php echo $value_product; ?> Reais</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3 shadow-lg rounded">
            <div class="card-header fw-bold">
                <i class="fas fa-tags me-2"></i>Total valores de custo
            </div>
            <div class="card-body text-center">
                <h5 class="card-title display-6"><?php echo $cost_value_product; ?> Reais</h5>
            </div>
        </div>
    </div>
</div>