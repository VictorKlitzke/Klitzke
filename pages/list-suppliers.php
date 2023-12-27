<?php

if (isset($_GET['delete'])) {
  $del = intval($_GET['delete']);
  Controllers::Delete('suppliers', $del);
  header('Location: ' . INCLUDE_PATH . 'list-suppliers');
}

$currentPage = isset($_GET['page']) ? (int)($_GET['page']) : 1;
$porPage = 20;

$suppliers = Controllers::SelectAll('suppliers', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
  <h2>Lista de Fornecedores</h2>
  <div class="list">
    <table>
      <thead>
        <tr>
          <td>Fornecedor</td>
          <td>Nome Fantasia</td>
          <td>Email</td>
          <td>Contato</td>
          <td>Endereço</td>
          <td>Cidade</td>
          <td>Estado</td>
          <td>CNPJ</td>
        </tr>
      </thead>

      <?php

      foreach ($suppliers as $key => $value) {

      ?>

        <tbody>
          <tr>
            <td><?php echo $value['company']; ?></td>
            <td><?php echo $value['fantasy_name']; ?></td>
            <td><?php echo $value['email']; ?></td>
            <td><?php echo $value['phone']; ?></td>
            <td><?php echo $value['address']; ?></td>
            <td><?php echo $value['city']; ?></td>
            <td><?php echo $value['state']; ?></td>
            <td><?php echo $value['cnpjcpf']; ?></td>

            <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
              <div>
                <a class="btn-edit" href="<?php echo INCLUDE_PATH; ?>edit-suppliers?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
              </div>
              <div>
                <a class="btn-delete" href="<?php echo INCLUDE_PATH; ?>list-suppliers?delete=<?php echo base64_encode($value['id']); ?>">Deletar</a>
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
  $totalPage = ceil(count(Controllers::selectAll('suppliers')) / $porPage);

  for ($i = 1; $i <= $totalPage; $i++) {
    if ($i == $currentPage)
      echo '<a class="page-selected" href="' . INCLUDE_PATH . 'list-suppliers?page=' . $i . '">' . $i . '</a>';
    else
      echo '<a href="' . INCLUDE_PATH . 'list-suppliers?page=' . $i . '">' . $i . '</a>';
  }

  ?>
</div>