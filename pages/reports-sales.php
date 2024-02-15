<?php

$sql = Db::Connection();
$result = $sql->prepare("SELECT 
                            S.id AS NUMERO_VENDA, 
                            S.date_sales AS DATA_VENDA, 
                            P.name AS PRODUTO, 
                            C.name AS CLIENTE, 
                            U.name AS USUARIO, 
                            SP.number_portion AS NUMERO_PARCELA, 
                            SP.value_portion AS VALOR_PARCELAS, 
                            S.total_value AS TOTAL_VENDA, 
                            SI.amount AS QUANTIDADE_ITEM, 
                            SI.price_sales AS VALOR_PROD 
                        FROM 
                            sales S 
                            INNER JOIN sales_items SI ON SI.id_sales = S.id 
                            INNER JOIN products P ON P.id = SI.id_product 
                            INNER JOIN clients C ON C.id = S.id_client 
                            INNER JOIN users U ON U.id = S.id_users 
                            INNER JOIN sales_portion SP ON SP.id_sales = S.id"
);

$result->execute();
$report_sales = $result->fetch(PDO::FETCH_ASSOC);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio de vendas</title>
</head>

<body>
    <div class="box-content">
        <div class="list">
            <table>
                <thead>

                    <tr>
                        <td>
                            <p>Numero venda</p>
                        </td>
                        <td>Data venda</td>
                        <td>Cliente</td>
                        <td>Usuario</td>
                        <td>Produto</td>
                        <td>
                            <p>Valor produto</p>
                        </td>
                        <td>
                            <p>Quantidade</p>
                        </td>
                        <td>
                            <p>Numero de percelas</p>
                        </td>
                        <td>
                            <p>valor de parcelas</p>
                        </td>
                        <td>Total venda</td>
                    </tr>

                </thead>

                <?php

                foreach ($report_sales as $key => $report) {

                    ?>

                    <tbody>

                        <tr>
                            <td>
                                <p>
                                    <?php echo $report['NUMERO_VENDA'] ?>
                                </p>
                            </td>
                            <td>
                                <?php echo $report['DATA_VENDA'] ?>
                            </td>
                            <td>
                                <?php echo $report['CLIENTE'] ?>
                            </td>
                            <td>
                                <?php echo $report['USUARIO'] ?>
                            </td>
                            <td>
                                <?php echo $report['PRODUTO'] ?>
                            </td>
                            <td>
                                <p>
                                    <?php echo $report['VALOR_PROD'] ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php echo $report['QUANTIDADE_ITEM'] ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php echo $report['NUMERO_PARCELA'] ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php echo $report['VALOR_PARCELA'] ?>
                                </p>
                            </td>
                            <td>
                                <?php echo $report['TOTAL_VENDA'] ?>
                            </td>
                        </tr>

                    </tbody>

                <?php } ?>

            </table>
        </div>
    </div>
</body>