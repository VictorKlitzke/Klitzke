<?php

include_once './services/db.php';

if (isset($_POST['action'])) {
    $login = $_POST['name'];
    $password = $_POST['password'];

    $sql = Db::Connection();

    $exec = $sql->prepare("SELECT * FROM `users` WHERE name = ? AND password = ?");
    $exec->execute(array($login, $password));

    if ($exec->rowCount() == 1) {
        $info = $exec->fetch();
        $_SESSION['login'] = true;
        $_SESSION['name'] = $login;
        $_SESSION['password'] = $password;
        $_SESSION['phone'] = $info['phone'];
        $_SESSION['function'] = $info['function'];
        $_SESSION['commission'] = $info['commission'];
        $_SESSION['target_commission'] = $info['target_commission'];
        $_SESSION['disable'] = $info['disebla'];
        header('Location: ' . INCLUDE_PATH);
        die();
    } elseif ($_SESSION['disable' == 1]) {
        Panel::Alert('error', 'Ocorreu um erro ao fazer login');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH; ?>css/style.css" />
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title>Klitzke software - login</title>
</head>

<body>
    <div class="overlay-container">
        <div class="container-login">
            <div class="content-img-login">
                <div class="content-login">
                    <h3>Login</h3>
                    <form method="post">
                        <input type="text" name="name" placeholder="Nome..." />
                        <input type="password" name="password" placeholder="Senha..." />
                        <div class="button-login">
                            <input type="submit" name="action" value="Logar no sistema">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>