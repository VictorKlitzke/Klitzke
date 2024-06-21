<?php

include_once 'services/db.php';
include_once 'config/config.php';

global $title_page_delivery;

try {
    $sql = Db::Connection();
} catch (RuntimeException $e) {
    var_dump($e->getMessage());
}

$exec = $sql->prepare("SELECT * FROM products WHERE show_on_page = 1");
$exec->execute();
$result = $exec->fetchAll(PDO::FETCH_ASSOC);

if (!$result) {
    echo "Nenhum produto encontrado.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/delivery.css" />
    <title><?php echo $title_page_delivery; ?> Pagina delivery</title>
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

<main class="container-page">
    <h2>Nosso Cardápio</h2>
    <div class="product-grid">
        <?php

            if ($result) {
                 foreach ($result as $key => $value){

        ?>
            <div class="product-card">
                <img src="path/to/product-image1.jpg" alt="Produto 1">
                <h3>Produto 1</h3>
                <p>Descrição do produto 1</p>
                <span class="price">R$ 29,90</span>
                <button class="btn-add-to-cart">Adicionar ao carrinho</button>
            </div>
        <?php }} else { ?>
                <p>Nenhum produto disponível no momento.</p>
        <?php } ?>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> Nome da Sua Empresa. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>