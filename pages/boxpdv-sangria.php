<?php

if (isset($_GET['id'])) {
  $id = (int) base64_decode($_GET['id']);
} else {
  Panel::alert('error', 'Você precisa passar o parametro ID.');
  die();
}

?>

<?php

$boxpdv  = Controllers::SelectAll('boxpdv');
$users = Controllers::Select("users");

if (empty($boxpdv)) {
  Panel::Alert('error', 'Nenhum caixa encontrado');
} else {
  foreach ($boxpdv as $box) {
    if ($box['id'] == $id) {
      if (isset($_POST['action'])) {

        $value = $_POST['value'];
        $value = str_replace(',', '.', preg_replace("/[^0-9,.]/", "", $value));
        number_format($value);
        $today = date("Y-m-d H:i:s");
        $observation = $_POST['observation'];

        if ($value == '' || $observation == '') {
          Panel::Alert('attention', 'Os campos não podem ficar vazios!');
        } else {
          $verification = Db::Connection()->prepare("SELECT id, id_boxpdv, withdrawa_date, observation FROM `sangria_boxpdv` WHERE value = ? AND withdrawa_date = ? AND observation = ? AND id_boxpdv = ? AND id_users = ?");
          $verification->execute(array($value, $today, $observation, $box['id'], $loggedInUserId));

          if ($verification->rowCount() == 0) {
            $arr = ['value' => $value, 'withdrawa_date' => $today, 'observation' => $observation, 'id_boxpdv' => $box['id'], 'id_users' => $loggedInUserId, 'name_table' => 'sangria_boxpdv'];
            Controllers::Insert($arr);
            Panel::Alert('success', 'Retirada de ' . $value . ' efetuada com sucesso');
          }
        }
      }
    }
  }
}

?>

<div class="box-content">
  <h2>Fazer retirada do caixa</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Valor</label>
      <input type="text" name="value" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
    </div>
    <div class="content-form">
      <label for="">Observação</label>
      <input type="text" name="observation">
    </div>
    <div class="content-form">
      <input type="hidden" name="id_boxpdv" />
      <input type="hidden" name="id_users" />
      <input type="hidden" name="withdrawa_date">
      <input type="hidden" name="name_table" value="sangria_boxpdv" />
      <input type="submit" name="action" value="Retirada">
    </div>
  </form>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>