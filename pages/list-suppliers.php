<?php

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
            <td><?php echo htmlspecialchars($value['company']); ?></td>
            <td><?php echo htmlspecialchars($value['fantasy_name']); ?></td>
            <td><?php echo htmlspecialchars($value['email']); ?></td>
            <td><?php echo htmlspecialchars($value['phone']); ?></td>
            <td><?php echo htmlspecialchars($value['address']); ?></td>
            <td><?php echo htmlspecialchars($value['city']); ?></td>
            <td><?php echo htmlspecialchars($value['state']); ?></td>
            <td><?php echo htmlspecialchars($value['cnpjcpf']); ?></td>

            <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
              <div>
                <a class="btn-edit" href="<?php echo INCLUDE_PATH; ?>edit-suppliers?id=<?php echo base64_encode($value['id']); ?>">Editar</a>
              </div>
              <div>
                <a class="btn-delete" onclick="DeleteForn(this)" data-id="<?php echo base64_encode($value['id']); ?>">Deletar</a>
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