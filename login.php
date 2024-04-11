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
        $_SESSION['id'] = $info['id'];
        $_SESSION['phone'] = $info['phone'];
        $_SESSION['function'] = $info['function'];
        $_SESSION['commission'] = $info['commission'];
        $_SESSION['target_commission'] = $info['target_commission'];
        header('Location: ' . INCLUDE_PATH); 
        die();
    } else {
        Panel::Alert('error', 'Credenciais inválidas. Ocorreu um erro ao fazer login.');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH; ?>css/login.css" />
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title>Klitzke software - login</title>
</head>

<body>
    <div class="wrapper">
         <span class="bg-animate"></span><!--LINHA ADD -->
         <span class="bg-animate2"></span><!--LINHA ADD -->
        <div class="form-box login">
            <h2>Login</h2>
            <form action="#" method="POST">
                <div class="input-box">
                    <input type="text" name="name" required  />
                    <label>Usuario</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password"  name="password" required />
                    <label>Senha</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                    <div>
                        <button type="submit" name="action" class="btn">Login</button>
                         <div class="logreg-link">
                            <p>Não tem uma Conta ?<a href="#" class="register-link">Registrar-me</a></p>
                        </div>
                    </div>
            </form>
        </div>
        <div class="info-text login">
            <h2>Bem vindo de volta !</h2>
            <p>Onde seu negocio toma novos rumos</p>
        </div>
        <div class="form-box register">
            <h2>Registrar-me</h2>
            <form action="#" method="POST">
                <div class="input-box">
                    <input type="text" name="name" required  />
                    <label>Usuario</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="name" required  />
                    <label>Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password"  name="password" required />
                    <label>Senha</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                    <div>
                        <button type="submit" name="action" class="btn">Registrar</button>
                         <div class="logreg-link">
                            <p>Já tem uma Conta ?<a href="#" class="login-link">Entrar</a></p>
                        </div>
                    </div>
            </form>
        </div>
        <div class="info-text register">
            <h2>Bem vindo de volta !</h2>
            <p>Onde seu negocio toma novos rumos</p>
        </div>
    </div>
    <script src="script.js"></script> <!--criar arquivo js para animação> -->
</body>

</html>