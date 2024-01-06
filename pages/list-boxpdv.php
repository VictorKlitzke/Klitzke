<?php

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  Controllers::delete('boxpdv', $id);
  header('Location: ' . INCLUDE_PATH . 'list-boxpdv');
}

$user_filter = isset($_POST['user_filter']) ? intval($_POST['user_filter']) : $user_id;

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 2;

$boxpdv = Controllers::SelectBoxPdv('boxpdv', ($currentPage - 1) * $porPage, $porPage, $user_filter);

?>

<div class="box-content">
  <h2>Filtro</h2>
  <div class="filter-form">
    <form method="post">
      <select name="user_filter" id="user_filter">

          <?php

          $users = Controllers::SelectAll('users');

          foreach ($users as $user) {
              echo '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
          }

          ?>

      </select>
      </form>
      <button class="filter" type="submit">Filtrar</button>
  </div>
  <div class="list">
    <h2>Lista de Caixas</h2>
    <table>
      <thead>
        <tr>
          <td>Usuario</td>
          <td>Valor</td>
          <td>Observação</td>
          <td>Data abertura</td>
          <td>Retirada</td>
          <td>Empresa</td>
        </tr>
      </thead>

      <?php
      foreach ($boxpdv as $key => $value) {
      ?>

        <tbody>
          <tr>
            <td><?php echo $value['users']; ?></td>
            <td><?php echo $value['value']; ?>,00</td>
            <td><?php echo $value['observation']; ?></td>
            <td><?php echo $value['open_date']; ?></td>
            <td><?php echo $value['Withdrawal']; ?></td>
            <td><?php echo $value['company']; ?></td>

            <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
              <div>
                <a class="btn-edit" href="<?php echo INCLUDE_PATH ?>boxpdv-sangria?id=<?php echo base64_encode($value['id']); ?>">Sagria</a>
              </div>

              <div>
                <a class="btn-delete" href="<?php echo INCLUDE_PATH ?>list-boxpdv=delete?id=<?php echo base64_encode($value['id']); ?>">Deletar</a>
              </div>
            </td>
          </tr>
        </tbody>
      <?php } ?>
    </table>
  </div>
</div>

<div class="page">
  <?php
  $totalPage = ceil(count(Controllers::SelectBoxPdv('boxpdv')) / $porPage);

  for ($i = 1; $i <= $totalPage; $i++) {
    if ($i == $currentPage)
      echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-boxpdv?page=' . $i . '">' . $i . '</a>';
    else
      echo '<a href="' . INCLUDE_PATH . 'list-boxpdv?page=' . $i . '">' . $i . '</a>';
  }

  ?>
</div>