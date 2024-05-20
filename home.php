<?php
include_once 'classes/panel.php';
include_once 'classes/controllers.php';
include_once 'config/config.php';
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
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/delivery.css" />
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/request.css" />
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/selectedClients.css" />
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/modalPortion.css" />
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css"/>
<!--   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
  <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
  <title>Klitzke Software - Admin</title>
</head>

<body>
  <div class="menu">
    <div class="container-menu">

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
            <div class="boxpdv" id="open-boxpdv">
              <span>Caixa aberto</span>
              <form action="">
                <input type="hidden" name="id_box" value="<?php echo base64_encode($boxpdv['id']); ?>">
              </form>
            </div>
          <?php } else {
            ?>
            <div class="boxpdv-close">
              <span>Caixa Fechado</span>
            </div>
          <?php }
        } else {
          ?>
          <div class="boxpdv-reopen">
            <span>Nenhum caixa aberto</span>
          </div>
        <?php }
      }
      ?>

      <h2 style="cursor: pointer;" onclick="ToggleRegister()">Cadastros</h2>
      <div id="registers" style="display: none;">
        <a <?php SelectedMenu('register-users') ?> href="<?php echo INCLUDE_PATH; ?>register-users">
          Cadastrar Usuários
        </a>
        <a <?php SelectedMenu('register-suppliers') ?> href="<?php echo INCLUDE_PATH; ?>register-suppliers">
          Cadastrar Fornecedores
        </a>
        <a <?php SelectedMenu('register-clients') ?> href="<?php echo INCLUDE_PATH; ?>register-clients">
          Cadastrar Clientes
        </a>
        <a <?php SelectedMenu('register-companys') ?> href="<?php echo INCLUDE_PATH; ?>register-companys">
          Cadastrar Empresa
        </a>
        <a <?php SelectedMenu('register-stockcontrol') ?> href="<?php echo INCLUDE_PATH; ?>register-stockcontrol">
          Cadastrar Produtos
        </a>
        <a <?php SelectedMenu('register-table') ?> href="<?php echo INCLUDE_PATH; ?>register-table">Cadastrar Mesa
        </a>
        <a <?php SelectedMenu('register-back-account') ?>
          href="<?php echo INCLUDE_PATH; ?>register-back-account">Cadastrar Conta Bancaria
        </a>
      </div>

      <h2 style="cursor: pointer;" onclick="ToggleLists()">Listagens</h2>
      <div id="list-registers" style="display: none;">
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
        <a <?php SelectedMenu('list-sales') ?> href="<?php echo INCLUDE_PATH; ?>list-sales">
          Lista de Vendas
        </a>
        <a <?php SelectedMenu('list-request') ?> href="<?php echo INCLUDE_PATH; ?>list-request">
          Lista de Pedidos
        </a>
      </div>

      <h2 style="cursor: pointer;" onclick="ToggleInvoicing()">Faturamento</h2>
      <div id="invoicing" style="display: none;">
        <a <?php SelectedMenu('register-boxpdv') ?> href="<?php echo INCLUDE_PATH; ?>register-boxpdv">Abrir Caixa</a>
        <a <?php SelectedMenu('register-sales') ?> href="<?php echo INCLUDE_PATH; ?>register-sales">Vendas</a>
        <a <?php SelectedMenu('register-request') ?> href="<?php echo INCLUDE_PATH; ?>register-request">Pedidos</a>
      </div>

      <h2>Minha Empresa</h2>
      <div class="company">
        <a <?php echo VerificationMenu(); ?> <?php SelectedMenu('list-companys'); ?>
          href="<?php echo INCLUDE_PATH; ?>list-companys">Empresa</a>
      </div>

      <h2 style="cursor: pointer;" onclick="ToggleReport()">Relatórios</h2>
      <div id="report" style="display: none;">
        <a <?php SelectedMenu('') ?> href="<?php echo INCLUDE_PATH; ?>reports">Relatório Fechamento Caixa</a>
        <a <?php SelectedMenu('') ?> href="<?php echo INCLUDE_PATH; ?>reports">Relatório Vendas</a>
        <a <?php SelectedMenu('') ?> href="<?php echo INCLUDE_PATH; ?>reports">Relatório Vendas por Usuários</a>
        <a <?php SelectedMenu('') ?> href="<?php echo INCLUDE_PATH; ?>reports">Relatório Pedidos Faturados</a>
        <a <?php SelectedMenu('') ?> href="<?php echo INCLUDE_PATH; ?>reports">Relatório Compras de Mercadoria</a>
      </div>

      <h2 style="cursor: pointer;" onclick="ToggleDelivery()">delivery</h2>
      <div id="delivery" style="display: none;">
        <a href="<?php echo INCLUDE_PATH; ?>list-product-delivery"> Pedidos delivery</a>
      </div>

    </div>
  </div>

  <header>
    <div class="center">

      <div class="menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="20" fill="#fff">
          <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
        </svg>
      </div>
      <div class="loggout right">
        <a <?php if (@$_GET['url'] == '') { ?> style="background: #323232; padding: 15px;" <?php } ?>
          href="<?php echo INCLUDE_PATH ?>"><span><svg xmlns="http://www.w3.org/2000/svg" height="18"
              viewBox="0 -960 960 960" width="20">
              <path fill="#fff"
                d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
            </svg></span></a>

        <a href="<?php echo INCLUDE_PATH; ?>?loggout">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="20">
              <path fill="#fff"
                d="M300-640v320l160-160-160-160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm440-80h120v-560H640v560Zm-80 0v-560H200v560h360Zm80 0h120-120Z" />
            </svg>
          </span></a>
        <span>
          <div class="left">
            <a <?php if (@$_GET['url'] == 'config-system') { ?> style="background: #323232;" <?php } ?>
              href="<?php echo INCLUDE_PATH ?>config-system">
              <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="20" fill="#fff">
                <path
                  d="m234-480-12-60q-12-5-22.5-10.5T178-564l-58 18-40-68 46-40q-2-13-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T222-820l12-60h80l12 60q12 5 22.5 10.5T370-796l58-18 40 68-46 40q2 13 2 26t-2 26l46 40-40 68-58-18q-11 8-21.5 13.5T326-540l-12 60h-80Zm40-120q33 0 56.5-23.5T354-680q0-33-23.5-56.5T274-760q-33 0-56.5 23.5T194-680q0 33 23.5 56.5T274-600ZM592-40l-18-84q-17-6-31.5-14.5T514-158l-80 26-56-96 64-56q-2-18-2-36t2-36l-64-56 56-96 80 26q14-11 28.5-19.5T574-516l18-84h112l18 84q17 6 31.5 14.5T782-482l80-26 56 96-64 56q2 18 2 36t-2 36l64 56-56 96-80-26q-14 11-28.5 19.5T722-124l-18 84H592Zm56-160q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z" />
              </svg>
            </a>
          </div>
        </span>
      </div>
      <div class="clear"></div>
    </div>
  </header>

  <div class="content">

    <?php Panel::LoadPage(); ?>

    <?php

    $sql = DB::Connection();

    $openBoxQuery = $sql->prepare("SELECT id FROM boxpdv WHERE status = 1");
    $openBoxQuery->execute();
    $openBoxResult = $openBoxQuery->fetch(PDO::FETCH_ASSOC);

    if ($openBoxResult) {
      $openBoxId = $openBoxResult['id'];

      $exec = $sql->prepare("SELECT SUM(total_value) as total_pix FROM sales WHERE sales.id_payment_method = 1 AND id_boxpdv = :boxId");
      $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
      $exec->execute();
      $result_pix = $exec->fetch(PDO::FETCH_ASSOC);

      $exec = $sql->prepare("SELECT SUM(total_value) as total_debit FROM sales WHERE sales.id_payment_method = 2 AND id_boxpdv = :boxId");
      $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
      $exec->execute();
      $result_debit = $exec->fetch(PDO::FETCH_ASSOC);

      $exec = $sql->prepare("SELECT SUM(total_value) as total_credit FROM sales WHERE sales.id_payment_method = 3 AND id_boxpdv = :boxId");
      $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
      $exec->execute();
      $result_credit = $exec->fetch(PDO::FETCH_ASSOC);

      $exec = $sql->prepare("SELECT SUM(total_value) as total_money FROM sales WHERE sales.id_payment_method = 4 AND id_boxpdv = :boxId");
      $exec->bindParam(':boxId', $openBoxId, PDO::PARAM_INT);
      $exec->execute();
      $result_money = $exec->fetch(PDO::FETCH_ASSOC);

    }

    ?>

    <div class="overlay" id="overlay">
      <div class="close-boxpdv" id="close-boxpdv">
        <div class="navbar-boxpdv">
          <h2>Fechamento do caixa</h2>
          <svg id="close-boxpdv-modal" style="cursor: pointer;" fill="#fff" xmlns="http://www.w3.org/2000/svg"
               height="24px" viewBox="0 0 24 24" width="24px">
            <path d="M0 0h24v24H0z" fill="none" />
            <path
              d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
          </svg>
        </div>
        <div class="box-content">
          <div class="center">
            <form class="form" method="POST" enctype="multipart/form-data">
              <div class="content-form">
                <label for="">Debito</label>
                <input id="value_debit" type="text" placeholder="Debito" name="value_debit"
                  value="<?php echo $result_debit["total_debit"]; ?>" />
              </div>
              <div class="content-form">
                <label for="">Credito</label>
                <input id="value_credit" type="text" placeholder="Credito" name="value_credit"
                  value="<?php echo $result_credit["total_credit"]; ?>" />
              </div>
              <div class="content-form">
                <label for="">PIX</label>
                <input id="value_pix" type="text" placeholder="PIX" name="value_pix"
                  value="<?php echo $result_pix["total_pix"]; ?>">
              </div>
              <div class="content-form">
                <label for="">Dinheiro</label>
                <input id="value_money" type="text" placeholder="Dinheiro" name="value_money"
                  value="<?php echo $result_money["total_money"]; ?>" />
              </div>
              <div class="content-form">
                <label for="">Data fechamento</label>
                <input id="date_close" type="date" placeholder="Data fechamento" name="date_close">
              </div>
              <div class="content-form">
                <input type="hidden" id="id_boxpdv" name="id_boxpdv">
                <button id="finish-sales" onclick="closeBox()" type="button" class="finish-box">Fechar
                  caixa</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_PATH; ?>./js/main.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_PATH; ?>./js/alert.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_PATH; ?>./js/values.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_PATH; ?>./js/menu.js"></script>

</body>

</html>