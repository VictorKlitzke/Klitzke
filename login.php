<?php

global $title_login;
global $chave_secret;
include_once './services/db.php';
include_once './classes/panel.php';

if (isset($_POST['action'])) {
    $login = $_POST['name'];
    $userProvidedPassword = $_POST['password'];

    $sql = Db::Connection();
    $exec = $sql->prepare("SELECT * FROM `users` WHERE name = ?");
    $exec->execute(array($login));

    if ($exec->rowCount() == 1) {
        $info = $exec->fetch();
        $storedPassword = $info['password']; 
        if (password_verify($userProvidedPassword, $storedPassword)) {

            $secretKey = $chave_secret;
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'data' => [
                    'id' => $info['id'],
                    'name' => $login,
                ]
            ];

            $jwt = base64_encode(json_encode($payload)) . '.' . base64_encode(hash_hmac('sha256', json_encode($payload), $secretKey, true));

            setcookie('jwt', $jwt, $expirationTime, '/', '', false, true);

            $_SESSION['login'] = true;
            $_SESSION['name'] = $login;
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
    <title><?php echo $title_login; ?></title>
</head>

<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>

        <div class="form-box login">
            <h2 class="animation" style="--i:0;">Login</h2>
            <form action="#" method="POST">
                <div class="input-box animation" style="--i:1;">
                    <input type="text" name="name" required />
                    <label>Usuario</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:2;">
                    <input type="password" name="password" required />
                    <label>Senha</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div>
                    <button type="submit" name="action" class="btn animation" style="--i:3;">Login</button>
                    <div class="logreg-link animation" style="--i:4;">
                        <p>Não tem uma Conta ?<a href="#" class="register-link">Registrar-me</a></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="info-text login">
            <h2 class="animation" style="--i:0;">Bem vindo de volta !</h2>
            <p class="animation" style="--i:1;">Onde seu negocio toma novos rumos</p>
        </div>
        <div class="form-box register">
            <h2 class="animation" style="--i:17;">Registrar-me</h2>
            <form action="#" method="POST">
                <div class="input-box animation" style="--i:18;">
                    <input type="text" name="name" required />
                    <label>Usuario</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:19;">
                    <input type="text" name="name" required />
                    <label>Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box animation" style="--i:20;">
                    <input type="password" name="password" required />
                    <label>Senha</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div>
                    <button type="submit" name="action" class="btn animation" style="--i:21;">Registrar</button>
                    <div class="logreg-link animation" style="--i:22;">
                        <p>Já tem uma Conta ?<a href="#" class="login-link">Entrar</a></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="info-text register">
            <h2 class="animation" style="--i:17;">Bem vindo de volta !</h2>
            <p class="animation" style="--i:18;">Onde seu negocio toma novos rumos</p>
        </div>
    </div>

    <script src="<?php echo INCLUDE_PATH ?>js/login.js"></script>
</body>

</html>