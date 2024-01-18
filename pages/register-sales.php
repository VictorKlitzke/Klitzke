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

    <div class="register-sales w100 left">

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

            <div id="start-sales" class="start-sales"
                onclick="AddSelectProducts(<?php echo $key; ?>, '<?php echo $value['id'] ?>', '<?php echo $value['name'] ?>', '<?php echo $value['stock_quantity'] ?>', '<?php echo $value['value_product'] ?>')">
                <div class="info-products">
                    <div class="img-product">
                        <img src="<?php echo INCLUDE_PATH; ?>config/public/upload/<?php echo $value['flow']; ?>" alt="">
                    </div>
                    <h2 class="sales-h2">
                        <?php echo $value['name'] ?>
                    </h2>
                    <p class="sales-p">R$
                        <?php echo $value['value_product'] ?>
                    </p>
                </div>
            </div>

        <?php } ?>

    </div>


    <div class="table-sales w70 left">
        <div class="names">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#fff">
                <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
            </svg>
            <h2>Selecionados</h2>
        </div>

        <?php

        $selects = Controllers::Select("products");

        ?>

        <div class="info-select">
            <input type="hidden" name="product-id[]" value="<?php echo base64_encode($selects['id']); ?>" />
            <input type="hidden" name="product-name[]" value="<?php echo base64_encode($selects['name']); ?>">
            <input type="hidden" name="product-quantity[]"
                value="<?php echo base64_encode($selects['stock_quantity']); ?>">
            <table id="product-result">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Produto</td>
                        <td>Quantidade</td>
                        <td>Preço</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="payment-method right">
        <div class="info-sales">
            <h1>Total</h1>
            <h3 id="totalAmount">R$ 0.00</h3>
            <div class="name-h4">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                    <path
                        d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                </svg>
                <p>
                <h4>Forma de Pagamento</h4>
                </p>
            </div>

            <div class="form-sales">
                <select id="id_payment_method" name="id_payment_method">

                    <?php

                    $form_paygament = Controllers::SelectAll("form_payment");


                    foreach ($form_paygament as $key => $value) {

                        ?>

                        <option <?php if ($value['id'] == @$_POST['id_payment_method'])
                            echo 'selected'; ?>value="<?php echo $value['id'] ?>">
                            <?php echo $value['name']; ?>
                        </option>

                    <?php } ?>

                </select>
                <input type="hidden" name="id_payment_method" value="<?php $value['id']; ?>">
                <div id="finish-sales" class="finish-button">
                    <button id="finish-sales" type="submit" class="finish-sales">Finalizar Venda</button>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay">
        <div class="client-search-sales" id="client-search-sales">
            <div class="search-client-sales">
                <div class="search-client">
                    <h2>Buscar Clientes</h2>
                    <svg id="close-search-client" fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24px"
                        viewBox="0 0 24 24" width="24px" fill="#000000">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </div>
                <form class="form-sales-search right" method="post" id="sales-search-form">
                    <input type="text" id="clientSelectedSales" name="clientSelectedSales"
                        placeholder="Buscar Clientes" />
                </form>
                <div class="box-content">
                    <div class="list">
                        <table>
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Cliente</td>
                                    <td>Contato</td>
                                    <td>Email</td>
                                    <td>CPF</td>
                                </tr>
                            </thead>

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

                                <tbody>
                                    <tr class="tbody-selected">
                                        <td>
                                            <?php echo $value['id'] ?>
                                        </td>
                                        <td>
                                            <?php echo $value['name'] ?>
                                        </td>
                                        <td>
                                            <?php echo $value['phone'] ?>
                                        </td>
                                        <td>
                                            <?php echo $value['email'] ?>
                                        </td>
                                        <td>
                                            <?php echo $value['cpf'] ?>
                                        </td>
                                    </tr>
                                </tbody>

                            <?php } ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="client-page-selected left w100">
        <div class="names">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#fff">
                <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
            </svg>
            <h2>Cliente Selecionado</h2>
        </div>
        <?php

        $client_id = Controllers::Select("clients")

            ?>
        <input type="hidden" id="sales_id_client" name="sales_id_client" value="<?php echo $client_id['id']; ?>">
        <div class="w100">
            <p>
            <h3 id="sales-page">Nenhum Cliente Selecionado</h3>
            </p>
        </div>
    </div>
</form>

<div id="error-container"
    style="color: black; display: none; background: #f75353; display: none; align-items: center; justify-content: center; padding: 20px; top: 50%; left: 50%; width: 100%;">
    <span id="error-message"></span>
</div>

<div id="success-container"
    style="color: black; display: none; background: green; display: none; align-items: center; justify-content: center; padding: 20px; top: 50%; left: 50%; width: 100%;">
    <span id="success-message"></span>
</div>

<div class="overlay-portion">
    <div class="portion-sales">
        <h2 class="h2-portion">Adicionar parcelas</h2>
        <div class="form-portion-sales">
            <form class="form" action="">
                <div class="content-form">
                    <input type="text" name="portion-total" id="portion-total" placeholder="Parcelas">
                </div>
            </form>
            <div class="content-form">
                <input class="button-portion right" type="button" name="action" value="Salvar">
            </div>
        </div>
        <div class="list-table">
            <div class="box-content">
                <div class="list">
                    <table>
                        <thead>
                            <td>#</td>
                            <td>Parcelas</td>
                            <td>Valor das parcelas</td>
                        </thead>

                        <tbody>
                            <td>1</td>
                            <td>1</td>
                            <td>12,00</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <p id="totalPortion" class="p-portion right">R$ 0.00</p>
    </div>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/selected_clients.js"></script>
<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_sales.js"></script>