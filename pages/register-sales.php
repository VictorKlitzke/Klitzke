<form class="" method="POST" enctype="multipart/form-data">
    <div class="names left">
        <svg fill="#000" xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 -960 960 960" width="40">
            <path
                d="M760-400v-260L560-800 360-660v60h-80v-100l280-200 280 200v300h-80ZM560-800Zm20 160h40v-40h-40v40Zm-80 0h40v-40h-40v40Zm80 80h40v-40h-40v40Zm-80 0h40v-40h-40v40ZM280-220l278 76 238-74q-5-9-14.5-15.5T760-240H558q-27 0-43-2t-33-8l-93-31 22-78 81 27q17 5 40 8t68 4q0-11-6.5-21T578-354l-234-86h-64v220ZM40-80v-440h304q7 0 14 1.5t13 3.5l235 87q33 12 53.5 42t20.5 66h80q50 0 85 33t35 87v40L560-60l-280-78v58H40Zm80-80h80v-280h-80v280Z" />
        </svg>
        <h7>Vendas</h7>
    </div>

    <div class="search-product right">
        <form method="post">
            <input type="text" id="product_search" name="product_search" placeholder="Buscar produtos">

            <?php

            $sales_new = Controllers::Select("sales");

            ?>

            <input type="hidden" id="id_sales" name="sales_id" value="<?php echo $sales_new['id'] ?>">

            <button type="button" id="selected-client" class="new-sales right">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path
                        d="M520-400h80v-120h120v-80H600v-120h-80v120H400v80h120v120ZM320-240q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="register-sales w100">

        <?php

        $sales = Controllers::SelectAll('products');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_search'])) {
            $searchTerm = $_POST['product_search'];
            $filteredSales = array_filter($sales, function ($product) use ($searchTerm) {
                return stripos($product['name'], $searchTerm) !== false || stripos($product['id'], $searchTerm) !== false;
            });
            $salesToDisplay = !empty($filteredSales) ? $filteredSales : $sales;
        } else {
            $salesToDisplay = $sales;
        }

        foreach ($salesToDisplay as $key => $value) {

            ?>

            <div class="container">
                <div class="row" id="start-sales"
                    onclick="AddSelectProducts(<?php echo $key; ?>, '<?php echo $value['id'] ?>', '<?php echo $value['name'] ?>', '<?php echo $value['stock_quantity'] ?>', '<?php echo $value['value_product'] ?>')">
                    <div class="col-sm-16">
                        <div class="card border-light mb-3 h-100" style="min-height: 200px; min-width: 200px;">
                            <div class="card-header"><?php echo $value['name'] ?></div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $value['name'] ?></h5>
                                <p class="card-text"><?php echo $value['value_product'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <div class="table-sales w60 left">
        <div class="names">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#fff">
                <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
            </svg>
            <h2 class="text-white">Selecionados</h2>
        </div>

        <?php

        $selects = Controllers::Select("products");

        ?>

        <div class="row">
            <div class="col">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                    <input type="hidden" name="product-id[]" value="<?php echo base64_encode($selects['id']); ?>" />
                    <input type="hidden" name="product-name[]" value="<?php echo base64_encode($selects['name']); ?>">
                    <input type="hidden" name="product-quantity[]"
                        value="<?php echo base64_encode($selects['stock_quantity']); ?>">
                    <table id="product-result" class="table table-dark table-hover">
                        <thead style="white-space: nowrap;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-method right">
        <div class="row">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h1>Total</h1>
                        <h3 id="totalAmount">R$ 0.00</h3>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4>Forma de Pagamento</h4>
                        <select id="id_payment_method" name="id_payment_method" class="form-control">
                            <?php
                            $form_paygament = Controllers::SelectAll("form_payment");
                            foreach ($form_paygament as $key => $value) {
                                ?>
                                <option <?php if ($value['id'] == @$_POST['id_payment_method'])
                                    echo 'selected'; ?>
                                    value="<?php echo $value['id'] ?>">
                                    <?php echo $value['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="id_payment_method" value="<?php echo $value['id']; ?>">
                        <button onclick="finalizeSale()" id="finish-sales" type="submit"
                            class="btn btn-success mt-4 w-100">Finalizar
                            Venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay">
        <div class="client-search-sales" id="client-search-sales">
            <div class="card-header d-flex justify-content-between align-items-center m-2">
                <h2 class="text-white">Buscar Clientes</h2>
                <svg id="close-search-client" fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24px"
                    viewBox="0 0 24 24" width="24px" onclick="closeClientSearch()">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </div>
            <div class="card-body m-2">
                <form class="" method="post" id="sales-search-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="clientSelectedSales" name="clientSelectedSales"
                            placeholder="Buscar Clientes" />
                    </div>
                </form>
                <div class="table-responsive m-2">
                    <table class="table table-hover table-dark">
                        <thead style="white-space: nowrap;">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Contato</th>
                                <th>Email</th>
                                <th>CPF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $clientSelectedSales = Controllers::SelectAll("clients");

                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clientSelectedSales'])) {
                                $searchclient = $_POST['clientSelectedSales'];
                                $filteredClient = array_filter($clientSelectedSales, function ($clientSelectedSales) use ($searchclient) {
                                    return stripos($clientSelectedSales['name'], $searchclient) !== false and stripos($clientSelectedSales['id'], $searchclient) !== false;
                                });
                                $salesclient = !empty($filteredClient) ? $filteredClient : $clientSelectedSales;
                            } else {
                                $salesclient = $clientSelectedSales;
                            }

                            foreach ($salesclient as $key => $value) {
                                ?>
                                <tr class="tbody-selected">
                                    <th><?php echo $value['id'] ?></th>
                                    <th><?php echo $value['name'] ?></th>
                                    <th><?php echo $value['phone'] ?></th>
                                    <th><?php echo $value['email'] ?></th>
                                    <th><?php echo $value['cpf'] ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="client-page-selected left w100">
        <div class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#fff">
                <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
            </svg>
            <h2 class="text-white">Cliente Selecionado</h2>
        </div>
        <?php

        $client_id = Controllers::Select("clients")

            ?>
        <input type="hidden" id="sales_id_client" name="sales_id_client" value="<?php echo $client_id['id']; ?>">
        <h3 class="text-white table-number" id="sales-page">Nenhum Cliente Selecionado</h3>
    </div>
</form>

<div class="overlay-portion" id="overlay-portion">
    <div class="portion-sales" id="portion-sales">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h2 class="text-white m-0">Adicionar parcelas</h2>
            <svg id="close-portion" onclick="closeModalPortion()" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                height="24px" viewBox="0 0 24 24" width="24px">
                <path d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
            </svg>
        </div>
        <div class="p-3">
            <form class="form" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="portion-total" id="portion-total"
                        placeholder="Parcelas">
                </div>
                <div class="d-grid gap-2 d-flex">
                    <button id="button-portion" class="btn btn-primary" type="button">Salvar</button>
                </div>
            </form>
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                        <table class="table table-dark table-hover">
                            <thead style="white-space: nowrap;"">
                                <tr>
                                    <th>#</th>
                                    <th>Parcelas</th>
                                    <th>Valor das parcelas</th>
                                </tr>
                            </thead>
                    <tbody id="desc-portion"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <button onclick="finalizeSalePortion()" id="finish-portion" class="btn btn-success" type="submit">Finalizar
                venda</button>
            <p id="total-portion-sales" class="text-white fw-bold m-0">R$ 0.00</p>
        </div>
    </div>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/selected_clients.js"></script>
<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_sales.js"></script>