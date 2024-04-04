<?php

if (isset($_POST['delete'])) {
    $del = intval($_POST['delete']);
    Controllers::DeleteRequest($del);
    header('Location: ' . INCLUDE_PATH . 'list-request');
}

$currentPage = isset($_POST['page']) ? (int) ($_POST['page']) : 1;
$porPage = 20;

$request = Controllers::SelectAll('request', ($currentPage - 1) * $porPage, $porPage);
?>

<div class="box-content left w100">
    <div class="" style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">
        <h2 style="color: #000">Lista de pedidos</h2>
        <div class="btn-ajust" style="flex-grow: 1; text-align: right; max-width: 120px;">
            <a class="btn-ajust" href="<?php echo htmlspecialchars(INCLUDE_PATH . 'gather-tables'); ?>">
                Ajuntar Comandas
            </a>
        </div>
    </div>
    <div class="list">
        <table>
            <thead>

                <tr>
                    <td>Mesa</td>
                    <td>Status</td>
                    <td>Total</td>
                    <td>Data</td>
                </tr>

            </thead>

            <?php
            $request_dados = Controllers::SelectRequest("request");

            foreach ($request_dados as $key => $value) { ?>

                <tbody>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($value["id_table"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["STATUS_REQUEST"]
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["total_request"]
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["date_request"]
                            ); ?>
                        </td>

                        <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                            <div>
                                <a class="btn-add-items" href="<?php echo INCLUDE_PATH; ?>add-item-order?id=<?php echo base64_encode($value["id"]); ?>">
                                    Adicionar mais items
                                </a>
                            </div>
                            <div>
                                <a class="btn-disable" href="<?php echo INCLUDE_PATH; ?>">Faturar
                                </a>
                            </div>
                            <div>
                                <a actionBtn="delete" class="btn-delete"
                                    href="<?php echo INCLUDE_PATH ?>list-request?delete=<?php echo $value['id']; ?>">Deletar
                                </a>
                            </div>
                        </td>
                    </tr>

                </tbody>

            <?php }
            ?>

        </table>
    </div>
</div>

<div class="page">

    <?php
    $totalPage = ceil(count(Controllers::selectAll("request")) / $porPage);

    for ($i = 1; $i <= $totalPage; $i++) {
        if ($i == $currentPage) {
            echo '<a class="page-selected" href="' .
                INCLUDE_PATH .
                "list-request?page=" .
                $i .
                '">' .
                $i .
                "</a>";
        } else {
            echo '<a href="' .
                INCLUDE_PATH .
                "list-request?page=" .
                $i .
                '">' .
                $i .
                "</a>";
        }
    }
    ?>

</div>