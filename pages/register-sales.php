<?php
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$page_permission = 'register-sales';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
}
?>

<div class="card">
    <div class="container-fluid mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Tela de Vendas</h1>
            <button class="btn btn-success btn-lg" onclick="finalizeSale()" id="finish-sales" type="button">Fechar
                Venda</button>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-body">
                <h5 class="card-title">Buscar Produto</h5>
                <form id="searchForm" class="input-group mb-3" onsubmit="return false;">
                    <input type="text" id="product-search" class="form-control"
                        placeholder="Digite o nome ou código do produto" required oninput="searchProduct(event)">
                    <button type="button" class="btn btn-primary" onclick="searchProduct(event)">Buscar</button>
                </form>
                <div id="search-results" class="mt-3"></div>
            </div>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-body">
                <h5 class="card-title">Adicionar Cliente</h5>
                <div class="input-group mb-3">
                    <form class="input-group mb-3" onsubmit="return false;">
                        <input type="text" class="form-control form-custom" id="client-search"
                            placeholder="Digite o nome do cliente" required oninput="searchClients(event)">
                        <button type="button" class="btn btn-primary" onclick="searchClients(event)">Buscar</button>
                    </form>
                </div>
                <div id="client-results" class="mt-3"></div>
                <div id="selected-client" class="mt-3"></div>
            </div>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-body">
                <h5 class="card-title">Produtos Selecionados</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-custom" id="selected-products-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Total</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="selected-products-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-body">
                        <h5 class="card-title text-center">Formas de Pagamento</h5>
                        <?php
                        $form_payment = Controllers::SelectAll("form_payment");
                        foreach ($form_payment as $value) {
                            ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="id_payment_method"
                                    id="id_payment_method<?php echo $value['id']; ?>" value="<?php echo $value['id']; ?>"
                                    <?php if ($value['id'] == @$_POST['id_payment_method'])
                                        echo 'checked'; ?>>
                                <label class="form-check-label" for="id_payment_method<?php echo $value['id']; ?>">
                                    <?php echo htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-body">
                        <h5 class="card-title text-center">Resumo da Venda</h5>
                        <div class="mb-3">
                            <p class="mb-1">Total:</p>
                            <strong id="total-display" class="text-success">R$ 0,00</strong>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1">Valor Recebido:</p>
                            <input type="text" id="change-sale" class="form-control"
                                placeholder="Digite o valor recebido" oninput="calculateChange()">
                        </div>
                        <div class="mb-3">
                            <p class="mb-1">Troco:</p>
                            <strong id="change-amount" class="text-danger">R$ 0,00</strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrCodeContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay-aprazo" id="overlay-aprazo">
        <div id="aprazo-sales" class="aprazo-sales card bg-dark text-white">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="m-0">Adicionar A Prazo</h2>
                <svg id="close-aprazo" onclick="CloseModalAPrazo()" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                    height="24px" viewBox="0 0 24 24" width="24px">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </div>

            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="aprazo-number" class="form-label">Número de Parcelas</label>
                        <input type="number" class="form-control" id="aprazo-number" placeholder="Parcelas" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="aprazo-venciment-date" class="form-label">Data de Vencimento</label>
                        <input type="date" class="form-control" id="aprazo-venciment-date" placeholder="Data de início">
                    </div>
                    <div class="mb-3">
                        <label for="aprazo-venciment" class="form-label">Intervalo de Vencimento (dias)</label>
                        <input type="text" class="form-control" id="aprazo-venciment" placeholder="Dias de Vencimento">
                    </div>
                    <div class="d-grid gap-2">
                        <button id="button-aprazo" class="btn btn-primary" type="button"
                            onclick="calculateInstallments()">Calcular Parcelas</button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Parcelas</th>
                                <th>Valor das Parcelas (R$)</th>
                            </tr>
                        </thead>
                        <tbody id="desc-aprazo"></tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <button onclick="FinalizeAprazo()" id="finish-aprazo" class="btn btn-success" type="button">Finalizar
                    venda</button>
                <p id="total-aprazo-sales" class="fw-bold m-0">Total a Pagar: R$ 0,00</p>
            </div>
        </div>
    </div>


    <div class="overlay-portion" id="overlay-portion">
        <div id="portion-sales" class="portion-sales card bg-dark text-white w-50">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h2 class="m-0">Adicionar Parcelas</h2>
                <svg id="close-portion" onclick="closeModalPortion()" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                    height="24px" viewBox="0 0 24 24" width="24px" style="cursor: pointer;">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="portion-total" class="form-label">Número de Parcelas</label>
                        <input type="text" class="form-control" name="portion-total" id="portion-total"
                            placeholder="Número de Parcelas" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button id="button-portion" class="btn btn-primary" type="button">Salvar</button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-dark">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Parcelas</th>
                                <th>Valor das Parcelas</th>
                            </tr>
                        </thead>
                        <tbody id="desc-portion"></tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center border-top">
                <button onclick="finalizeSalePortion()" id="finish-portion" class="btn btn-success"
                    type="submit">Finalizar
                    Venda</button>
                <p id="total-portion-sales" class="fw-bold text-white m-0">R$ 0.00</p>
            </div>
        </div>
    </div>


    <script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/selected_clients.js"></script>
    <script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_sales.js"></script>