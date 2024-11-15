<?php
session_start();

$notaInfo = $_SESSION['notaInfo'] ?? null;

if (!$notaInfo) {
    echo 'Nenhuma informação encontrada.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Nota Fiscal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid shadow-lg mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h2>Detalhes da Nota Fiscal</h2>
            </div>
            <div class="card-body">
                <h3 class="h4">Emitente</h3>
                <p><strong>Razão Social:</strong> <?php echo htmlspecialchars($notaInfo['emitterName']); ?></p>
                <p><strong>CNPJ:</strong> <?php echo htmlspecialchars($notaInfo['emitterCnpj']); ?></p>
                <p><strong>Numero da Nota:</strong> <?php echo htmlspecialchars($notaInfo['invoiceNumber']); ?></p>

                <h3 class="h4">Valor Total</h3>
                <p><strong>R$ <?php echo $notaInfo['totalValue']; ?></strong></p>

                <h3 class="h4">Produtos</h3>
                <form method="POST">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Unidade</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notaInfo['products'] as $product): ?>
                                <tr>
                                    <td>
                                        <input type="number" value="<?php echo htmlspecialchars($product['codigo']); ?>"
                                            name="cod[] " id="cod_product" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="name_product" name="description[]"
                                            value="<?php echo htmlspecialchars($product['descricao']); ?>" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="unit" name="unit[]"
                                            value="<?php echo htmlspecialchars($product['unidade']); ?>" />
                                    </td>
                                    <td>
                                        <input type="number" step="any" id="quantity" class="form-control" name="quantity[]"
                                            value="<?php echo htmlspecialchars($product['quantidade']); ?>" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="value_product" name="unit_value[]"
                                            value="R$ <?php echo $product['valor_unitario']; ?>" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="button" onclick="RegisterDisplayInvoice()" class="btn btn-success">Salvar
                        Alterações</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="./js/register_system.js"></script>

</body>

</html>