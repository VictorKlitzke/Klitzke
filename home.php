<?php
include_once 'classes/panel.php';
include_once 'classes/controllers.php';
include_once 'config/config.php';
?>

<?php if(isset($_GET['loggout'])) {
    Panel::Loggout();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/style.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/sales.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/main.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/SelectedClients.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/ModalValueSales.css" />
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/font-awesome.min.css">
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title>Klitzke Software - Admin</title>
</head>

<body>
    <div class="menu">
        <div class="container-menu">
        
            <?php

                $boxpdv = Controllers::Select('boxpdv');

                if ($boxpdv['open_date'] == true){

            ?>

            <div class="boxpdv">
                <span>Caixa aberto</span>
            </div> 

            <?php } else { ?>

            <div class="boxpdv-close">
                <span>Caixa Fechado</span>
            </div> 

            <?php } ?>

            <h2>Cadastros</h2>
            <div class="items-menu">
                <a <?php SelectedMenu('register-users') ?> href="<?php echo INCLUDE_PATH; ?>register-users">Cadastrar
                    Usuários
                </a>
                <a <?php SelectedMenu('register-suppliers') ?>
                    href="<?php echo INCLUDE_PATH; ?>register-suppliers">
                    Cadastrar Fornecedores
                </a>
                <a <?php SelectedMenu('register-clients') ?>
                    href="<?php echo INCLUDE_PATH; ?>register-clients">
                    Cadastrar Clientes
                </a>
                <a <?php SelectedMenu('register-companys') ?>
                    href="<?php echo INCLUDE_PATH; ?>register-companys">
                    Cadastrar Empresa
                </a>
                <a <?php SelectedMenu('register-stockcontrol') ?>
                    href="<?php echo INCLUDE_PATH; ?>register-stockcontrol">
                    Cadastrar Produtos
                </a>
            </div>
            <h2>Listagens</h2>
            <a <?php SelectedMenu('list-users') ?> href="<?php echo INCLUDE_PATH; ?>list-users">
                Lista de Usuários
            </a>
            <a <?php SelectedMenu('list-clients') ?> href="<?php echo INCLUDE_PATH; ?>list-clients">
            Lista de Clientes
            </a>
            <a <?php SelectedMenu('list-suppliers') ?> href="<?php echo INCLUDE_PATH; ?>list-suppliers">
            Lista de Fornecedores
            </a>
            <a <?php SelectedMenu('list-boxpdv') ?> href="<?php echo INCLUDE_PATH; ?>list-boxpdv">
                Lista de Caixas
            </a>
            <a <?php SelectedMenu('list-products') ?> href="<?php echo INCLUDE_PATH; ?>list-products">
            Lista de Produtos
            </a>
            <a href="<?php echo INCLUDE_PATH; ?>list-sales">
                Lista de Vendas
            </a>
            <h2>Faturamento</h2>
            <a <?php SelectedMenu('register-boxpdv') ?> href="<?php echo INCLUDE_PATH; ?>register-boxpdv">
            Abrir Caixa
            </a>
            <a href="<?php echo INCLUDE_PATH; ?>register-sales">Vendas</a>
            <h2>Empresa</h2>
            <a <?php echo VerificationMenu(); ?> <?php SelectedMenu('list-companys'); ?>
                href="<?php echo INCLUDE_PATH; ?>list-companys">Empresa</a>
        </div>
    </div>

    <header>
        <div class="center">

            <div class="menu-btn">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#fff">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
            </div>
            <div class="loggout right">
                <a <?php if(@$_GET['url'] == '') { ?> style="background: #051f57;padding: 15px;" <?php } ?>
                    href="<?php echo INCLUDE_PATH ?>"><span><svg xmlns="http://www.w3.org/2000/svg" height="24"
                            viewBox="0 -960 960 960" width="24">
                            <path fill="#fff"
                                d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg></span></a>

                <a href="<?php echo INCLUDE_PATH; ?>?loggout">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path fill="#fff"
                                d="M300-640v320l160-160-160-160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm440-80h120v-560H640v560Zm-80 0v-560H200v560h360Zm80 0h120-120Z" />
                        </svg>
                    </span></a>
                <span>
                    <div class="left" style="padding-right: 18px;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"
                            fill="#fff">
                            <path
                                d="m234-480-12-60q-12-5-22.5-10.5T178-564l-58 18-40-68 46-40q-2-13-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T222-820l12-60h80l12 60q12 5 22.5 10.5T370-796l58-18 40 68-46 40q2 13 2 26t-2 26l46 40-40 68-58-18q-11 8-21.5 13.5T326-540l-12 60h-80Zm40-120q33 0 56.5-23.5T354-680q0-33-23.5-56.5T274-760q-33 0-56.5 23.5T194-680q0 33 23.5 56.5T274-600ZM592-40l-18-84q-17-6-31.5-14.5T514-158l-80 26-56-96 64-56q-2-18-2-36t2-36l-64-56 56-96 80 26q14-11 28.5-19.5T574-516l18-84h112l18 84q17 6 31.5 14.5T782-482l80-26 56 96-64 56q2 18 2 36t-2 36l64 56-56 96-80-26q-14 11-28.5 19.5T722-124l-18 84H592Zm56-160q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z" />
                        </svg>
                    </div>
                </span>
            </div>
            <div class="clear"></div>
        </div>
    </header>

    <div class="content">
        <?php Panel::LoadPage(); ?>
    </div>

    <script src="<?php echo INCLUDE_PATH; ?>./js/main.js"></script>
    <script src="<?php echo INCLUDE_PATH; ?>./js/alert.js"></script>
    <script src="<?php echo INCLUDE_PATH; ?>./js/values.js"></script>

</body>

</html>