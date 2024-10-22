<?php

$sql = Db::Connection();

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
$page_permission = 'list-users';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
  header("Location: " . INCLUDE_PATH . "access-denied.php");
  exit();
}

$users = Controllers::SelectAll('users');

?>

<div class="box-content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-white mb-4">Lista de Usuários</h2>
    <a class="btn btn-success" <?php SelectedMenu('register-users') ?>
      href="<?php echo INCLUDE_PATH; ?>register-users">Novo Usuário</a>
  </div>
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
              <th scope="col">Tipo de Usuário</th>
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
                <th>
                  <?php echo $value['access'] == 10 ? 'Padrão' : ($value['access'] == 50 ? 'Moderado' : ($value['access'] == 100 ? 'Administrador' : '')); ?>
                </th>
                <th><?php echo htmlspecialchars($value['type_users']); ?></th>
                <th class="gap-2">
                  <?php if ($value['disable'] == 2) { ?>
                    <a></a>
                  <?php } else { ?>
                    <a class="btn btn-info accessnivel"
                      href="<?php echo INCLUDE_PATH ?>edit-users?id=<?php echo base64_encode($value['id']); ?>">Editar
                    </a>
                  <?php } ?>
                  <?php if ($value['disable'] == 1) { ?>
                    <button onclick="InativarUsers(this)" type="button" data-id="<?php echo $value['id']; ?>"
                      class="btn btn-warning">Desativar
                    </button>
                  <?php } else { ?>
                    <button class="btn btn-secondary" disabled>Desativado</button>
                  <?php } ?>
                  <button class="btn btn-danger accessnivel" onclick="DeleteUsers(this)"
                    data-id="<?php echo base64_encode($value['id']); ?>">Deletar
                  </button>
                  <?php if ($value['disable'] == 1) { ?>
                    <button class="btn btn-light accessnivel" onclick="AccessUsers(this)"
                      data-id="<?php echo base64_encode($value['id']); ?>">
                      Acessos de Menu
                    </button>
                  <?php } ?>
                </th>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="menu-access-user" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Menus de Acesso do Usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button"
              role="tab" aria-controls="sales" aria-selected="true">Remover</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="budgets-tab" data-bs-toggle="tab" data-bs-target="#budgets" type="button"
              role="tab" aria-controls="budgets" aria-selected="false">Adicionar</button>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
            <div id="remover-menus-user" class="row p-lg-2">
            </div>
          </div>
        </div>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade" id="budgets" role="tabpanel" aria-labelledby="budgets-tab">
            <div id="edit-menus-user" class="row p-lg-2"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>