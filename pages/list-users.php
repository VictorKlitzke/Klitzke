<?php

$users = Controllers::SelectAll('users');

?>

<div class="box-content">
  <h2 class="text-white mb-4">Lista de Usuários</h2>
  <div class="row">
    <div class="col">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-dark table-hover">
          <thead>
            <tr style="white-space: nowrap;">
              <th scope="col">Usuário</th>
              <th scope="col">Email</th>
              <th scope="col">Contato</th>
              <th scope="col">Função</th>
              <th scope="col">Comissão</th>
              <th scope="col">Comissão por venda</th>
              <th scope="col">Acessos</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $key => $value) { ?>
              <tr style="white-space: nowrap;" class="<?php echo $value['disable'] != 1 ? 'table-danger' : ''; ?>">
                <th><?php echo htmlspecialchars($value['name']); ?></th>
                <th><?php echo htmlspecialchars($value['email']); ?></th>
                <th><?php echo htmlspecialchars($value['phone']); ?></th>
                <th><?php echo htmlspecialchars($value['function']); ?></th>
                <th><?php echo htmlspecialchars($value['commission']); ?> %</th>
                <th><?php echo htmlspecialchars($value['target_commission']); ?> %</th>
                <th><?php echo $value['access'] == 10 ? 'Padrão' : ($value['access'] == 50 ? 'Moderado' : ($value['access'] == 100 ? 'Administrador' : ''));?></th>
                <th class="gap-2">
                  <a class="btn btn-info"
                    href="<?php echo INCLUDE_PATH ?>edit-users?id=<?php echo base64_encode($value['id']); ?>">Editar
                  </a>
                  <?php if ($value['disable'] == 1) { ?>
                    <button onclick="InativarUsers(this)" type="button" data-id="<?php echo $value['id']; ?>"
                      class="btn btn-warning">Desativar
                    </button>
                  <?php } else { ?>
                    <button class="btn btn-secondary" disabled>Desativado</button>
                  <?php } ?>

                  <button class="btn btn-danger" onclick="DeleteUsers(this)"
                    data-id="<?php echo base64_encode($value['id']); ?>">Deletar
                  </button>

                </th>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>