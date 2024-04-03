<?php

if (isset($_GET['delete'])) {
    $del = intval($_GET['delete']);
    Controllers::DeleteRequest($del);
    header('Location: ' . INCLUDE_PATH . 'list-request');
}

$currentPage = isset($_GET['page']) ? (int) ($_GET['page']) : 1;
$porPage = 20;

$request = Controllers::SelectAll('request', ($currentPage - 1) * $porPage, $porPage);
?>

<div class="box-content left w100">
    <h2>Lista de pedidos</h2>
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
                                <a class="btn-edit" href="<?php echo INCLUDE_PATH; ?>add-item-order?id=<?php echo base64_encode(
                                       $value["id"]
                                   ); ?>">Adicionar mais items
                                </a>
                            </div>
                            <div>
                                <a class="btn-edit"
                                    href="<?php echo htmlspecialchars(INCLUDE_PATH . 'gather-tables?id=' . urlencode(base64_encode($value["id"]))); ?>">
                                    Ajuntar mesas
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