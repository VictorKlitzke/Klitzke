<?php

$suppliers = Controllers::SelectAll('suppliers', ($currentPage - 1) * $porPage, $porPage);

?>

<div class="box-content">
  <h2 class="text-white mb-4">Lista de Fornecedores</h2>
  <div class="row">
    <div class="com">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover">
          <thead>
            <tr style="white-space: nowrap;">
              <th scope="col">Fornecedor</th>
              <th scope="col">Nome Fantasia</th>
              <th scope="col">Email</th>
              <th scope="col">Contato</th>
              <th scope="col">Endereço</th>
              <th scope="col">Cidade</th>
              <th scope="col">Estado</th>
              <th scope="col">CNPJ</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>

          <?php

          foreach ($suppliers as $key => $value) {

            ?>

            <tbody>
              <tr style="white-space: nowrap;">
                <th><?php echo htmlspecialchars($value['company']); ?></th>
                <th><?php echo htmlspecialchars($value['fantasy_name']); ?></th>
                <th><?php echo htmlspecialchars($value['email']); ?></th>
                <th><?php echo htmlspecialchars($value['phone']); ?></th>
                <th><?php echo htmlspecialchars($value['address']); ?></th>
                <th><?php echo htmlspecialchars($value['city']); ?></th>
                <th><?php echo htmlspecialchars($value['state']); ?></th>
                <th><?php echo htmlspecialchars($value['cnpjcpf']); ?></th>

                <th class="gap-2">
                    <a class="btn btn-info"
                      href="<?php echo INCLUDE_PATH; ?>edit-suppliers?id=<?php echo base64_encode($value['id']); ?>">Editar</a>

                    <button class="btn btn-danger" onclick="DeleteForn(this)"
                      data-id="<?php echo base64_encode($value['id']); ?>">Deletar</button>
                </th>
              </tr>
            </tbody>

          <?php } ?>

        </table>
      </div>
    </div>
  </div>
</div>
</div>