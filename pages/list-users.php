<?php

if (isset($_GET['delete'])) {
  $del = intval($_GET['delete']);
  Controllers::Delete('users', $del);
  header('Location: ' . INCLUDE_PATH . 'list-users');
}

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$users = Controllers::SelectAll('users', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
  <h2>Lista de Usuários</h2>
  <div class="list">
    <table>
      <tr>
        <td>Usuário</td>
        <td>Email</td>
        <td>Contato</td>
        <td>Função</td>
        <td>Comissão</td>
        <td>
          <p>Comissão por venda</p>
        </td>
      </tr>

      <?php

      foreach ($users as $key => $value) {

      ?>

        <tr>
          <td>
            <p><?php echo $value['name']; ?></p>
          </td>
          <td><?php echo $value['email']; ?></td>
          <td><?php echo $value['phone']; ?></td>
          <td><?php echo $value['function']; ?></td>
          <td> <?php echo $value['commission']; ?> %</td>
          <td><?php echo $value['target_commission']; ?> % </td>

          <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
            <div>
              <a class="btn-edit" href="<?php echo INCLUDE_PATH ?>edit-users?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
            </div>
            <div>
              <a href="">
                <form method="post" action="./ajax/disable.php">
                  <input type="hidden" name="id" value="<?php echo base64_encode($value['id']); ?>">
                  <!-- <input type="hidden" name="disable_id" value="<?php //echo base64_encode($value['disable']); 
                                                                      ?>"> -->
                  <button onclick="confirmDesativar()" class="btn-disable">
                    Desativar
                  </button>
                </form>
              </a>
            </div>
            <div>
              <a class="btn-delete" href="<?php echo INCLUDE_PATH ?>list-users?delete=<?php echo base64_encode($value['id']); ?>">Deletar</a>
            </div>
          </td>
        </tr>

      <?php } ?>

    </table>
  </div>
</div>

<div class="page">
  <?php
  $totalPage = ceil(count(Controllers::selectAll('users')) / $porPage);

  for ($i = 1; $i <= $totalPage; $i++) {
    if ($i == $currentPage)
      echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-users?page=' . $i . '">' . $i . '</a>';
    else
      echo '<a href="' . INCLUDE_PATH . 'list-users?page=' . $i . '">' . $i . '</a>';
  }

  ?>
</div>
<div id="confirm-box" class="confirm-box">
  <p>Deseja realmente desativar o usuário?</p>
  <button id="confirm-yes" class="confirm-yes">Sim</button>
  <button id="confirm-no" class="confirm-no">Não</button>
</div>
<div id="message" class="message"></div>