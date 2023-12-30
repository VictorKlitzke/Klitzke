<?php
include_once '../config/config.php';
include_once '../services/db.php';

$saleId = $_GET['sale_id'];
$saleDetails = getSaleDetailsFromDatabase($saleId);

function getSaleDetailsFromDatabase($saleId)
{
    $sql = Db::Connection();
    $exec = $sql->prepare("SELECT * FROM sales WHERE id = :saleId");
    $exec->bindParam(':saleId', $saleId, PDO::PARAM_INT);
    $exec->execute();
    return $exec->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Venda</title>
</head>

<body>
    <h1>Comprovante de Venda</h1>

    <p>ID da Venda:
        <?php echo $saleDetails['id']; ?>
    </p>
    <p>Data da Venda:
        <?php echo $saleDetails['date_sales']; ?>
    </p>
    <p>Total: R$
        <?php echo number_format($saleDetails['total_value'], 2, ',', '.'); ?>
    </p>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>