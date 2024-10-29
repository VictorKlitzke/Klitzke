<?php
global $title_login;
global $title;
global $chave_secret;
include_once './services/db.php';
include_once './classes/panel.php';

$today = date("Y-m-d H:i:s");
function generateRandomCode($length = 10)
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

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

            if ($info['access'] != 100) {
                $randomCode = generateRandomCode(10) . date('Ymd');
                $dateStart = date('Y-m-d H:i:s');
                $dateFinal = date('Y-m-d H:i:s', strtotime($dateStart . ' + 15 days'));

                $insertCode = $sql->prepare("INSERT INTO validade_system (code, date_start, date_final, id_users) 
                                            VALUES(:code, :date_start, :date_final, :id_users)");
                $insertCode->bindValue(':code', $randomCode, PDO::PARAM_STR);
                $insertCode->bindValue(':date_start', $dateStart, PDO::PARAM_STR);
                $insertCode->bindValue(':date_final', $dateFinal, PDO::PARAM_STR);
                $insertCode->bindValue(':id_users', $info['id'], PDO::PARAM_INT);
                $insertCode->execute();

            }

        }

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
        $_SESSION['access'] = $info['access'];
        $_SESSION['random_code'] = $randomCode;
        $_SESSION['access'] = $info['access'];

        $message_log = "Usuário $name logado com sucesso";
        Panel::LogAction($info['id'], 'Login Usuário', $message_log, $today);

        header('Location: ' . INCLUDE_PATH);
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title><?php echo $title_login; ?></title>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
        <div class="row w-100 mx-0" style="max-width: 1000px;">
            <div
                class="col-lg-6 p-5 d-none d-lg-flex flex-column justify-content-center bg-primary text-light rounded-start">
                <h2 class="display-5 fw-bold mb-4">Bem-vindo ao <?php echo $title; ?></h2>
                <p class="lead">Onde seu negócio toma novos rumos. Acesse sua conta para começar!</p>
            </div>
            <div class="col-lg-6 p-5 bg-white rounded-end shadow-lg">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Acesse sua Conta</h3>
                    <p class="text-muted">Faça login para continuar</p>
                </div>
                <form method="POST">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" name="name" id="usernameLogin" placeholder="Usuário" required>
                        <label for="usernameLogin">Usuário</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" name="password" id="passwordLogin" placeholder="Senha" required>
                        <label for="passwordLogin">Senha</label>
                    </div>
                    <button type="submit" name="action" class="btn btn-primary w-100 py-2 fs-5">Login</button>
                </form>
                <div class="text-center mt-4">
                    <a class="text-decoration-none text-primary">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>