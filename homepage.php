<?php
global $title_home;

include_once 'classes/panel.php';
include_once 'classes/controllers.php';
include_once 'config/config.php';
include_once 'services/db.php';

$sql = Db::Connection();

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

$checkCode = $sql->prepare("SELECT * FROM validade_system WHERE id_users = ? ORDER BY date_final DESC LIMIT 1");
$checkCode->execute(array($_SESSION['id']));
$currentDate = date('Y-m-d H:i:s');

if ($checkCode->rowCount() > 0) {
    $validityInfo = $checkCode->fetch();
    if ($currentDate > $validityInfo['date_final']) {
        die();
    }
}

$checkMenu = $sql->prepare("SELECT menu FROM menu_access WHERE user_id = :user_id");
$checkMenu->bindParam('user_id', $user_id, PDO::PARAM_INT);
$checkMenu->execute();

$user_permissions = $checkMenu->fetchAll(PDO::FETCH_COLUMN);
$_SESSION['user_permissions'] = array_fill_keys($user_permissions, 1);

if (isset($_GET['loggout'])) {
    Panel::Loggout();
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title><?php echo $title_home; ?></title>
</head>

<body>

    <nav class="navbar bg-dark">
        <div class="container-fluid shadow-sm">

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
                                aria-expanded="false">Cadastros Gerais</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php
                                if ($user_id) {
                                    if (isset($_SESSION['user_permissions']['list-users'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-users'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-users">Usuários
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (isset($_SESSION['user_permissions']['list-clients'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-clients'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-clients">Clientes
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (isset($_SESSION['user_permissions']['list-suppliers'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-suppliers'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-suppliers">Fornecedores
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Faturamento</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php if (isset($_SESSION['user_permissions']['register-sales'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('register-sales'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>register-sales">Vendas
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (isset($_SESSION['user_permissions']['list-sales'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-sales'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-sales">Lista de Vendas
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Food</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php if (isset($_SESSION['user_permissions']['register-request'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('register-request'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>register-request">Pedidos
                                            </a>
                                        </li>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION['user_permissions']['list-request'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-request'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-request">Lista de Pedidos
                                            </a>
                                        </li>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION['user_permissions']['register-table'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('register-table'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>register-table">Cadastrar Mesa
                                            </a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Fluxo de Caixa</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php if (isset($_SESSION['user_permissions']['register-boxpdv'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('register-boxpdv'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>register-boxpdv">Abrir Caixa
                                            </a>
                                        </li>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION['user_permissions']['list-boxpdv'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-boxpdv'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-boxpdv">Lista de Caixas
                                            </a>
                                        </li>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION[''][''])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu(''); ?>
                                                href="<?php echo INCLUDE_PATH; ?>">Relatorios Fluxo de Caixa
                                            </a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Suprimentos
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php if (isset($_SESSION['user_permissions']['shopping-request'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('shopping-request'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>shopping-request"> Solicitação de Compras
                                            </a>
                                        </li>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION['user_permissions']['list-purchase-request'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-purchase-request'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-purchase-request"> Lista das Solicitações
                                                de Compras
                                            </a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </li>
                            <?php if ($showMenuAdm): ?>
                                <li class="nav-item dropdown">
                                    <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">Controle Financeiro</a>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <?php if ($_SESSION['user_permissions']['financial-control']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('financial-control.php'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>financial-control"> Visualizar Pagamentos
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if ($showMenuAdm): ?>
                                <li class="nav-item dropdown">
                                    <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">Controle Estoque</a>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <?php if ($_SESSION['user_permissions']['list-products']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('list-products'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>list-products"> Lista de Produtos
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['user_permissions']['register-stockcontrol']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('register-stockcontrol'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>register-stockcontrol">Cadastrar Produtos
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['user_permissions']['list-inventary']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('list-inventary'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>list-inventary">Lista de Iventarios
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['user_permissions']['stock-inventory']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('stock-inventory'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>stock-inventory">Iventario dos Produtos
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['user_permissions']['register-portions']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('register-portions'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>register-portions">Criar Porção</a>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if ($showMenuAdm): ?>
                                <li class="nav-item dropdown">
                                    <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">Administrativo</a>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <?php if ($_SESSION['user_permissions']['dashboard']): ?>
                                            <li>
                                                <a class="dropdown-item" <?php SelectedMenu('dashboard'); ?>
                                                    href="<?php echo INCLUDE_PATH; ?>dashboard"> Dashboard ADM
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item dropdown">
                                <a style="color: #fff !important; font-size: 1.3rem" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Minha Empresa</a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <?php if (isset($_SESSION['user_permissions']['list-companys'])): ?>
                                        <li>
                                            <a class="dropdown-item" <?php SelectedMenu('list-companys'); ?>
                                                href="<?php echo INCLUDE_PATH; ?>list-companys">Empresa
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php } ?>
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

            $exec = $sql->prepare("
                                    SELECT 
                                        SUM(CASE WHEN sales.id_payment_method = 1 THEN total_value ELSE 0 END) AS total_pix,
                                        SUM(CASE WHEN sales.id_payment_method = 2 THEN total_value ELSE 0 END) AS total_debit,
                                        SUM(CASE WHEN sales.id_payment_method = 3 THEN total_value ELSE 0 END) AS total_credit,
                                        SUM(CASE WHEN sales.id_payment_method = 4 THEN total_value ELSE 0 END) AS total_money,
                                        SUM(CASE WHEN sales.id_payment_method = 5 AND sales_aprazo.status = 'paga' THEN total_value ELSE 0 END) AS total_aprazo,
                                        SUM(CASE WHEN sales.id_payment_method = 4 THEN change_sales ELSE 0 END) AS change_sales
                                    FROM sales
                                    LEFT JOIN sales_aprazo ON sales_aprazo.sale_id = sales.id AND sales.id_payment_method = 5
                                    WHERE sales.id_boxpdv = :boxId AND sales.id_users = :id_users
                                ");

            $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $results = $exec->fetch(PDO::FETCH_ASSOC);

            $total_pix = $results['total_pix'];
            $total_debit = $results['total_debit'];
            $total_credit = $results['total_credit'];
            $total_money = $results['total_money'];
            $total_aprazo = $results['total_aprazo'];
            $change_sales = $results['change_sales'];


            $exec = $sql->prepare("
                                    SELECT 
                                        SUM(boxpdv.value) - COALESCE(
                                            (SELECT SUM(s.value) 
                                            FROM sangria_boxpdv s 
                                            WHERE s.id_boxpdv = boxpdv.id), 
                                            0
                                        ) AS value_boxpdv 
                                    FROM 
                                        boxpdv 
                                    WHERE 
                                        id = :id 
                                        AND id_users = :id_users
                                ");
            $exec->bindParam(':id', $openBoxId, PDO::PARAM_INT);
            $exec->bindParam(':id_users', $user_id, PDO::PARAM_INT);
            $exec->execute();
            $result_system = $exec->fetch(PDO::FETCH_ASSOC);


        }

        ?>

        <div class="overlay" id="overlay">
            <div class="modal" id="close-boxpdv" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-light text-dark rounded-3 shadow">
                        <div class="modal-header" style="background-color: #007BFF; color: white;">
                            <h4 class="modal-title" id="modalLabel">Fechamento do Caixa</h4>
                            <button type="button" id="close-boxpdv-modal" class="btn-close btn-close-white"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="form" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_debit" class="form-label">Débito</label>
                                            <input id="value_debit" class="form-control" type="text"
                                                placeholder="Débito" name="value_debit"
                                                value="<?php echo numberFormat($total_debit); ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_credit" class="form-label">Crédito</label>
                                            <input id="value_credit" class="form-control" type="text"
                                                placeholder="Crédito" name="value_credit"
                                                value="<?php echo numberFormat($total_credit); ?>" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_pix" class="form-label">PIX</label>
                                            <input id="value_pix" class="form-control" type="text" placeholder="PIX"
                                                name="value_pix" value="<?php echo numberFormat($total_pix); ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_money" class="form-label">Dinheiro</label>
                                            <input id="value_money" class="form-control" type="text"
                                                placeholder="Dinheiro" name="value_money"
                                                value="<?php echo numberFormat($total_money); ?>" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_aprazo" class="form-label">A Prazo</label>
                                            <input id="value_aprazo" class="form-control" type="text"
                                                placeholder="A Prazo" name="value_aprazo"
                                                value="<?php echo numberFormat($total_aprazo); ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_system" class="form-label">Abertura de Caixa</label>
                                            <input id="value_system" class="form-control" type="text"
                                                placeholder="Caixa Sistema" name="value_system"
                                                value="<?php echo numberFormat($result_system['value_boxpdv']); ?>" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_fisico" class="form-label">Caixa Físico</label>
                                            <input id="value_fisico" class="form-control" type="text"
                                                placeholder="Caixa Físico" name="value_fisico"
                                                oninput="calculateDifference()" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="value_difference" class="form-label">Diferença</label>
                                            <input id="value_difference" class="form-control" type="text"
                                                placeholder="Diferença" name="value_difference" readonly />
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="text-dark">Data fechamento</label>
                                            <input id="date_close" class="form-control" type="date" name="date_close" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="text-dark">Soma: Dinheiro + Abertura de Caixa</label>
                                            <input id="soma" class="form-control" type="text"
                                                placeholder="Soma: Dinheiro + caixa Sistema" readonly />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="text-dark">Soma: Total + Abertura de Caixa</label>
                                            <input id="TotalizadorBox" class="form-control" type="text"
                                                placeholder="Soma: Total + caixa Sistema" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="change_sales" class="form-label">Total de Troco</label>
                                            <input id="change_sales" class="form-control" type="text"
                                                placeholder="Troco" name="change_sales"
                                                value="<?php echo numberFormat($change_sales); ?>" readonly />
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="id_boxpdv" name="id_boxpdv">
                                <div class="d-grid">
                                    <button id="finish-sales" onclick="closeBox()" type="button"
                                        class="btn btn-success py-2">Fechar Caixa</button>
                                </div>
                            </form>
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

    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>const_globais.js">
    </script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>alert.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>main.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>register_system.js">
    </script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>delete_system.js">
    </script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>edit_system.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>values.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>menu.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>list_system.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>buy_request.js"></script>
    <script language="JavaScript" type="text/javascript"
        src="<?php echo INCLUDE_JAVASCRIPT; ?>financial_control.js"></script>
    <script src="<?php echo INCLUDE_JAVASCRIPT; ?>dashboard.js"></script>
    <script src="<?php echo INCLUDE_JAVASCRIPT; ?>querys.js"></script>
    <script src="<?php echo INCLUDE_JAVASCRIPT; ?>inventary.js"></script>
    <script src="<?php echo INCLUDE_JAVASCRIPT; ?>create_portion.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>

</html>