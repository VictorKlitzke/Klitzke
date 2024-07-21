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
        </tr>
      </thead>

      <?php
      foreach ($boxpdv as $key => $value) {
        ?>

        <tbody>
          <tr>
            <td><?php echo htmlspecialchars($value['users']); ?></td>
            <td><?php echo htmlspecialchars($value['value']); ?></td>
            <td><?php echo htmlspecialchars($value['observation']); ?></td>
            <td>
              <p><?php echo htmlspecialchars($value['open_date']); ?></p>
            </td>
            <td><?php echo htmlspecialchars($value['Withdrawal']); ?></td>

            <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
              <?php if ($value['status'] == 1) { ?>
                <div>
                  <a class="btn-edit"
                    href="<?php echo INCLUDE_PATH ?>boxpdv-sangria?id=<?php echo base64_encode($value['id']); ?>">Sagria</a>
                </div>
              <?php } else { ?>
                <div>
                  <a class="btn-reopen">Fecho</a>
                </div>
              <?php } ?>
            </td>
          </tr>
        </tbody>
      <?php } ?>
    </table>
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