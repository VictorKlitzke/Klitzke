<?php

global $title_page_delivery;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/delivery.css" />
    <link rel="icon" href="<?php echo INCLUDE_PATH; ?>./public/logo/favicon.ico" type="image/x-icon" />
    <title><?php echo $title_page_delivery; ?></title>
</head>

<body>

<header class="header-main">
    <div class="container-header">
        <h1 class="logo-header">Nome da Sua Empresa</h1>
        <nav class="nav-header">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Cardápio</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container-page">
    <h2>MAIN</h2>
</div>

<footer class="footer">
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> Nome da Sua Empresa. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>