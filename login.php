<?php

global $title_login;
global $chave_secret;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PANEL; ?>../css/system.css">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH; ?>css/login.css" />
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title><?php echo $title_login; ?></title>
</head>

<body>
    <div class="message-container" id="message-container"></div>

    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>

        <div class="form-box login">
            <h2 class="animation" style="--i:0;">Login</h2>
            <form>
                <div class="input-box animation" style="--i:1;">
                    <input type="text" name="name" id="name" />
                    <label>Usuario</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:2;">
                    <input type="password" name="password" id="password" />
                    <label>Senha</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div>
                    <button type="button" onclick="login()" class="btn animation" style="--i:3;">Login</button>
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
                    <input type="text" name="name" id="name" required />
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
                    <button type="button" class="btn animation" style="--i:21;">Registrar</button>
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
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>list_system.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>alert.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo INCLUDE_JAVASCRIPT; ?>login.js"></script>
</body>

</html>