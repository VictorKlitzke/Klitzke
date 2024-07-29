<?php

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$user_filter = isset($_POST['user_filter']) ? intval($_POST['user_filter']) : $user_id;

$currentPage = isset($_GET['page']) ? (int) ($_GET['page']) : 1;
$porPage = 25;

$boxpdv = Controllers::SelectBoxPdv('boxpdv', ($currentPage - 1) * $porPage, $porPage, $user_filter);

?>

<div class="box-content">
  <div class="filter-container">
    <div class="filter-content">
      <h2 style="color: #000">Filtros</h2>
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
          <button class="filter" type="submit">Filtrar</button>
        </form>
      </div>
    </div>
  </div>
  <h2 class="text-white mb-4">Lista de Caixas</h2>
  <div class="row">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover">
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Valor</th>
              <th>Observação</th>
              <th>Data abertura</th>
              <th>Retirada</th>
              <th>Ações</th>
            </tr>
          </thead>

          <?php
          foreach ($boxpdv as $key => $value) {
            ?>

            <tbody>
              <tr>
                <th><?php echo htmlspecialchars($value['users']); ?></th>
                <th><?php echo htmlspecialchars($value['value']); ?></th>
                <th><?php echo htmlspecialchars($value['observation']); ?></th>
                <th><?php echo htmlspecialchars($value['open_date']); ?></th>
                <th><?php echo htmlspecialchars($value['Withdrawal']); ?></th>

                <th class="gap-2">
                  <?php if ($value['status'] == 1) { ?>
                    <a class="btn btn-info"
                      href="<?php echo INCLUDE_PATH ?>boxpdv-sangria?id=<?php echo base64_encode($value['id']); ?>">Sagria</a>
                  <?php } else { ?>
                    <button class="btn btn-secondary">Fecho</button>
                  <?php } ?>
                </th>
              </tr>
            </tbody>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="page">
  <?php
  $totalPage = ceil(count(Controllers::SelectBoxPdv('boxpdv', 0, 0, $user_filter)) / $porPage);

  for ($i = 1; $i <= $totalPage; $i++) {
    if ($i == $currentPage)
      echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-boxpdv?page=' . $i . '&user_filter=' . $user_filter . '">' . $i . '</a>';
    else
      echo '<a href="' . INCLUDE_PATH . 'list-boxpdv?page=' . $i . '&user_filter=' . $user_filter . '">' . $i . '</a>';
  }

  ?>
</div>