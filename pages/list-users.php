<?php
$currentPage = isset($_GET['page']) ? (int) ($_GET['page']) : 1;
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
            <p><?php echo htmlspecialchars($value['name']); ?></p>
          </td>
          <td><?php echo htmlspecialchars($value['email']); ?></td>
          <td><?php echo htmlspecialchars($value['phone']); ?></td>
          <td><?php echo htmlspecialchars($value['function']); ?></td>
          <td> <?php echo htmlspecialchars($value['commission']); ?> %</td>
          <td><?php echo htmlspecialchars($value['target_commission']); ?> % </td>

          <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
            <div>
              <a class="btn-edit"
                href="<?php echo INCLUDE_PATH ?>edit-users?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
            </div>

            <div>
              <?php if ($value['disable'] == 1){ ?>
              <button onclick="InativarUsers(this)" type="button" data-id="<?php echo $value['id']; ?>"
                class="btn-disable">
                Desativar
              </button>
              <?php } else { ?>
                <button
                class="btn-reopen">
                Desativado
              </button>
              <?php } ?>
            </div>

            <div>
              <a class="btn-delete" onclick="DeleteUsers(this)" data-id="<?php echo base64_encode($value['id']); ?>"
                >Deletar</a>
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