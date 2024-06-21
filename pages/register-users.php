<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$disable = 1;

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (isset($_POST['action'])) {
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $phone = trim($_POST['phone']);
    $function = trim($_POST['function']);
    $commission = filter_var($_POST['commission'], FILTER_VALIDATE_FLOAT);
    $target_commission = filter_var($_POST['target_commission'], FILTER_VALIDATE_FLOAT);
    $access = filter_var($_POST['access'], FILTER_VALIDATE_INT);

    if (!$name || !$email || !$password || !$phone || !$function || $commission === false || $target_commission === false || $access === false) {
        Panel::Alert('attention', 'Os campos não podem ficar vazios ou inválidos!');
    } else {
        $verification = Db::Connection()->prepare("SELECT * FROM `users` WHERE email = ?");
        $verification->execute([$email]);

        if ($verification->rowCount() == 0) {
            $arr = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'function' => $function,
                'commission' => $commission,
                'target_commission' => $target_commission,
                'access' => $access,
                'disable' => $disable,
                'name_table' => 'users'
            ];
            Controllers::Insert($arr);
            Panel::Alert('success', htmlspecialchars($name) . ' foi cadastrado com sucesso!');
            $message_log = "Usuário cadastrado: " . htmlspecialchars($name);
            Panel::LogAction($user_id, 'Cadastrando usuário', $message_log);
        } else {
            Panel::Alert('error', 'Já existe um usuário com este email!');
        }
    }
}
?>


<div class="box-content">
  <h2>Cadastrar Usuario</h2>
  <form class="form" method="post" enctype="multipart/form-data">
    <div class="content-form">
      <label for="">Nome</label>
      <input type="text" name="name">
    </div>
    <div class="content-form">
      <label for="">Email</label>
      <input type="text" name="email">
    </div>
    <div class="content-form">
      <label for="">Senha</label>
      <input type="password" name="password">
    </div>
    <div class="content-form">
      <label for="">Função</label>
      <input type="text" name="function">
    </div>
    <div class="content-form">
      <label for="">Telefone</label>
      <input type="text" name="phone">
    </div>
    <div class="content-form">
      <label for="">Comissao</label>
      <input type="number" name="commission">
    </div>
    <div class="content-form">
      <label for="">Commisao por meta</label>
      <input type="number" name="target_commission">
    </div>
    <div class="content-form">
      <label for="">Nivel de acesso</label>
      <select name="access">
          <option value="100">Administrador</option>
          <option value="50">Moderado</option>
          <option value="10">Padrão</option>
      </select>
    </div>
    <div class="content-form">
      <input type="hidden" name="name_table" value="users" />
      <input type="submit" name="action" value="Cadastrar">
    </div>
  </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('.form');

        form.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); 

                var currentInput = event.target;
                var formElements = form.elements;
                var currentIndex = Array.from(formElements).indexOf(currentInput);

                if (currentIndex < formElements.length - 1) {
                    formElements[currentIndex + 1].focus();
                }
            }
        });
    });
</script>