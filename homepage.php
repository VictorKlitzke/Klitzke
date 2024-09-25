<?php
global $title_home;

include_once 'classes/panel.php';
include_once 'classes/controllers.php';
include_once 'config/config.php';
include_once 'services/db.php';

$sql = Db::Connection();

$checkCode = $sql->prepare("SELECT * FROM validade_system WHERE id_users = ? ORDER BY date_final DESC LIMIT 1");
$checkCode->execute(array($_SESSION['id']));
$currentDate = date('Y-m-d H:i:s');

if ($checkCode->rowCount() > 0) {
    $validityInfo = $checkCode->fetch();
    if ($currentDate > $validityInfo['date_final']) {
        die();
    }
}

?>

<?php if (isset($_GET['loggout'])) {
    Panel::Loggout();
} ?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/system.css">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/modalInvoicing.css">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/style.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/sales.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/main.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/request.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/selectedClients.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/modalPortion.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title><?php echo $title_home; ?></title>
</head>

<body>

    <nav class="navbar bg-dark">
        <div class="container-fluid">

            <button style="background: #fff" id="menu-btn" class="navbar-toggler" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                Menu
            </button>

            <div class="d-flex justify-content-center align-items-sm-center">
                <div class="navbar-brand d-flex">
                    <?php
                    $sql = Db::Connection();

                    if (!empty($_SESSION['id'])) {
                        $user_id = $_SESSION['id'];

                        $exec = $sql->prepare("SELECT * FROM boxpdv WHERE id_users = :user_id ORDER BY id DESC LIMIT 1");
                        $exec->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $exec->execute();
                        $result = $exec->fetchAll(PDO::FETCH_ASSOC);

                        if ($result) {
                            $boxpdv = $result[0];

                            if ($boxpdv["status"] == 1) {
                                ?>
                                <div class="btn btn-success" id="open-boxpdv">
                                    <span>Caixa aberto</span>
                                    <form action="">
                                        <input type="hidden" name="id_box" value="<?php echo base64_encode($boxpdv['id']); ?>">
                                    </form>
                                </div>
                            <?php } else {
                                ?>
                                <div class="btn btn-danger">
                                    <span>Caixa Fechado</span>
                                </div>
                            <?php }
                        } else {
                            ?>
                            <div class="btn btn-info ">
                                <span>Nenhum caixa aberto</span>
                            </div>
                        <?php }
                    }
                    ?>
                </div>

                <div aria-live="polite" aria-atomic="true" class="d-flex">
                    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer">
                    </div>
                </div>

                <a class="navbar-brand" <?php if (@$_GET['url'] == '') { ?>
                        style="border: 3px solid #c1c1c1; border-radius: 7px;" <?php } ?> href="<?php echo INCLUDE_PATH ?>">
                    <span style="color: #fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="20">
                            <path fill="#fff"
                                d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg>
                        Pagina Inicial
                    </span>
                </a>

                <a class="navbar-brand" <?php if (@$_GET['url'] == 'config-system') { ?>
                        style="border: 3px solid #c1c1c1; border-radius: 7px;" <?php } ?>
                    href="<?php echo INCLUDE_PATH ?>config-system">
                    <span style="color: #fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="20"
                            fill="#fff">
                            <path
                                d="m234-480-12-60q-12-5-22.5-10.5T178-564l-58 18-40-68 46-40q-2-13-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T222-820l12-60h80l12 60q12 5 22.5 10.5T370-796l58-18 40 68-46 40q2 13 2 26t-2 26l46 40-40 68-58-18q-11 8-21.5 13.5T326-540l-12 60h-80Zm40-120q33 0 56.5-23.5T354-680q0-33-23.5-56.5T274-760q-33 0-56.5 23.5T194-680q0 33 23.5 56.5T274-600ZM592-40l-18-84q-17-6-31.5-14.5T514-158l-80 26-56-96 64-56q-2-18-2-36t2-36l-64-56 56-96 80 26q14-11 28.5-19.5T574-516l18-84h112l18 84q17 6 31.5 14.5T782-482l80-26 56 96-64 56q2 18 2 36t-2 36l64 56-56 96-80-26q-14 11-28.5 19.5T722-124l-18 84H592Zm56-160q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z" />
                        </svg>
                        Configuração
                    </span>
                </a>

                <a class="navbar-brand" href="<?php echo INCLUDE_PATH; ?>?loggout">
                    <span style="color: #fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="20">
                            <path fill="#fff"
                                d="M300-640v320l160-160-160-160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm440-80h120v-560H640v560Zm-80 0v-560H200v560h360Zm80 0h120-120Z" />
                        </svg>
                        Sair
                    </span>
                </a>
            </div>

            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item dropdown">
                            <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                style="cursor: pointer;" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">Cadastros</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" <?php SelectedMenu('register-users') ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-users">Cadastrar Usuários</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-suppliers'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-suppliers">Cadastrar Fornecedores</a>
                                </li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-clients'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-clients">Cadastrar Clientes</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-companys'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-companys">Cadastrar Empresa</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-table'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-table">Cadastrar Mesa</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-back-account'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-back-account">Cadastrar Conta
                                        Bancaria</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                style="cursor: pointer;" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">Listagens</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" <?php SelectedMenu('list-users'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-users">Lista de Usuários</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('list-clients'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-clients">Lista de Clientes</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('list-suppliers'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-suppliers">Lista de Fornecedores</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('list-sales'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-sales">Lista de Vendas </a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('list-request'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-request">Lista de Pedidos</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">Faturamento</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" <?php SelectedMenu('register-boxpdv'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-boxpdv">Abrir Caixa</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-sales'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-sales">Vendas</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('register-request'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-request">Pedidos</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">Fluxo de Caixa</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" <?php SelectedMenu('register-boxpdv'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>register-boxpdv">Abrir Caixa</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu('list-boxpdv'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-boxpdv">Lista de Caixas</a></li>
                                <li><a class="dropdown-item" <?php SelectedMenu(''); ?>
                                        href="<?php echo INCLUDE_PATH; ?>">Relatorios Fluxo de Caixa</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"
                                onclick="ToggleRequest()">Suprimentos</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" <?php SelectedMenu('shopping-request'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>shopping-request"> Solicitação de Compras </a>
                                </li>
                                <li><a class="dropdown-item" <?php SelectedMenu('list-purchase-request'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-purchase-request"> Lista das Solicitações
                                        de Compras </a></li>
                            </ul>
                        </li>
                        <?php if ($showMenuAdm): ?>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Controle Financeiro</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item" <?php SelectedMenu('financial-control.php'); ?>
                                            href="<?php echo INCLUDE_PATH; ?>financial-control"> Visualizar Pagamentos </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($showMenuAdm): ?>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Controle Estoque</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item" <?php SelectedMenu('list-products'); ?>
                                            href="<?php echo INCLUDE_PATH; ?>list-products"> Lista de Produtos</a></li>
                                    <li><a class="dropdown-item" <?php SelectedMenu('register-stockcontrol'); ?>
                                            href="<?php echo INCLUDE_PATH; ?>register-stockcontrol">Cadastrar Produtos</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($showMenuAdm): ?>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Administrativo</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item" <?php SelectedMenu('dashboard.php'); ?>
                                            href="<?php echo INCLUDE_PATH; ?>dashboard"> Dashboard ADM </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">Minha Empresa</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" <?php echo VerificationMenu(); ?> <?php SelectedMenu('list-companys'); ?>
                                        href="<?php echo INCLUDE_PATH; ?>list-companys">Empresa</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="content" id="content">

        <?php Panel::LoadPage(); ?>

        <?php

        $sql = DB::Connection();
        $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $status = 1;

        $openBoxQuery = $sql->prepare("SELECT id FROM boxpdv WHERE status = :status AND id_users = :id_users");
        $openBoxQuery->bindParam(':status', $status, PDO::PARAM_INT);
        $openBoxQuery->bindParam(':id_users', $user_id, PDO::PARAM_INT);
        $openBoxQuery->execute();
        $openBoxResult = $openBoxQuery->fetch(PDO::FETCH_ASSOC);

        if ($openBoxResult) {
            $openBoxId = $openBoxResult['id'];

            $exec = $sql->prepare("SELECT SUM(total_value) as total_pix FROM sales WHERE sales.id_payment_method = 1 AND id_boxpdv = :boxId AND id_users = :id_users");
            $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result_pix = $exec->fetch(PDO::FETCH_ASSOC);

            $exec = $sql->prepare("SELECT SUM(total_value) as total_debit FROM sales WHERE sales.id_payment_method = 2 AND id_boxpdv = :boxId AND id_users = :id_users");
            $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result_debit = $exec->fetch(PDO::FETCH_ASSOC);

            $exec = $sql->prepare("SELECT SUM(total_value) as total_credit FROM sales WHERE sales.id_payment_method = 3 AND id_boxpdv = :boxId AND id_users = :id_users");
            $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result_credit = $exec->fetch(PDO::FETCH_ASSOC);

            $exec = $sql->prepare("SELECT SUM(total_value) as total_money FROM sales WHERE sales.id_payment_method = 4 AND id_boxpdv = :boxId AND id_users = :id_users");
            $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result_money = $exec->fetch(PDO::FETCH_ASSOC);

            $exec = $sql->prepare("SELECT SUM(total_value) as total_aprazo FROM sales inner join sales_aprazo on `sales_aprazo`.`sale_id` = sales.id WHERE sales.id_payment_method = 5 and sales_aprazo.status = 'paga' AND id_boxpdv = :boxId AND id_users = :id_users");
            $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result_aprazo = $exec->fetch(PDO::FETCH_ASSOC);

        }

        ?>

        <div class="overlay" id="overlay">
            <div class="close-boxpdv" id="close-boxpdv">
                <div class="card-header d-flex justify-content-between align-items-center m-2">
                    <h2 class="text-white">Fechamento do caixa</h2>
                    <svg id="close-boxpdv-modal" style="cursor: pointer;" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                        height="24px" viewBox="0 0 24 24" width="24px">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </div>
                <div class="card-body m-2">
                    <div class="row g-3">
                        <form class="form" method="POST" enctype="multipart/form-data">
                            <div class="col-sm-12">
                                <label class="text-white">Debito</label>
                                <input id="value_debit" class="form-control" type="text" placeholder="Debito"
                                    name="value_debit" value="<?php echo $result_debit["total_debit"]; ?>" />
                            </div>
                            <div class="col-sm-12">
                                <label class="text-white">Credito</label>
                                <input id="value_credit" class="form-control" type="text" placeholder="Credito"
                                    name="value_credit" value="<?php echo $result_credit["total_credit"]; ?>" />
                            </div>
                            <div class="col-sm-12">
                                <label class="text-white">PIX</label>
                                <input id="value_pix" class="form-control" type="text" placeholder="PIX"
                                    name="value_pix" value="<?php echo $result_pix["total_pix"]; ?>">
                            </div>
                            <div class="col-sm-12">
                                <label class="text-white">Dinheiro</label>
                                <input id="value_money" class="form-control" type="text" placeholder="Dinheiro"
                                    name="value_money" value="<?php echo $result_money["total_money"]; ?>" />
                            </div>
                            <div class="col-sm-12">
                                <label class="text-white">A Prazo</label>
                                <input id="value_aprazo" class="form-control" type="text" placeholder="A Prazo"
                                    name="value_aprazo" value="<?php echo $result_aprazo["total_aprazo"]; ?>" />
                            </div>
                            <div class="col-sm-12">
                                <label class="text-white">Data fechamento</label>
                                <input id="date_close" class="form-control" type="date" placeholder="Data fechamento"
                                    name="date_close">
                            </div>
                        </form>
                        <div class="col-12">
                            <input type="hidden" id="id_boxpdv" name="id_boxpdv">
                            <button id="finish-sales" onclick="closeBox()" type="button" class="btn btn-primary">Fechar
                                caixa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="message-container" id="message-container"></div>
    </div>

    <!-- <h2 style="cursor: pointer;" onclick="ToggleDelivery()">delivery</h2>
            <div id="delivery" style="display: none;">
                <a class="dropdown-item" href="<?php echo INCLUDE_PATH; ?>list-product-delivery"> Pedidos delivery </a>
            </div> -->
    <!--
            <a onclick="ToggleReport()">Relatórios</a>
            <div id="report">
                <a class="dropdown-item" <?php SelectedMenu('reports') ?> href="<?php echo INCLUDE_PATH; ?>reports">Relatórios diversos</a>
            </div>
                </ul>
            </div> -->

    <script language="JavaScript" type="text/javascript"
        src="<?php echo INCLUDE_JAVASCRIPT; ?>const_globais.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>alert.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>main.js"></script>
    <script language="JavaScript" type="text/javascript"
        src="<?php echo INCLUDE_JAVASCRIPT; ?>register_system.js"></script>
    <script language="JavaScript" type="text/javascript"
        src="<?php echo INCLUDE_JAVASCRIPT; ?>delete_system.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>edit_system.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>values.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>menu.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>list_system.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>buy_request.js"></script>
    <script language="JavaScript" type="text/javascript"
        src="<?php echo INCLUDE_JAVASCRIPT; ?>financial_control.js"></script>
    <script src="<?php echo INCLUDE_JAVASCRIPT; ?>dashboard.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
</body>

</html>