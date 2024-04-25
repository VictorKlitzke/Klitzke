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
    <div class=""
        style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">
        <h2 style="color: #000">Lista de pedidos</h2>
        <div class="btn-ajust" style="flex-grow: 1; text-align: center; max-width: 180px;">
            <a class="btn-ajust" href="<?php echo htmlspecialchars(INCLUDE_PATH . 'gather-tables'); ?>">
                Agrupar Comandas
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
                                <a class="btn-disable" href="<?php echo INCLUDE_PATH; ?>">Faturar
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


<div class="box-content left w100">
    <div class=""
        style="display: flex; background: #ccc; padding: 9px; margin: 4px; border-radius: 4px; justify-content: space-between;">
        <h2 style="color: #000">Lista de pedidos agrupados</h2>
    </div>
    <div class="list">
        <table>
            <thead>

                <tr>
                    <td>Comandas principal</td>
                    <td>Comandas agrupadas</td>
                    <td>Status</td>
                    <td>Valor Total</td>
                    <td>Data</td>
                </tr>

            </thead>

            <?php
            $request_dados = Controllers::SelectrequestGathers("request_gathers");

            foreach ($request_dados as $key => $value) { ?>

                <tbody>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["principal_command_id"]
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["grouped_command_id"]
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["status"]
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["value_total"]
                            ); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars(
                                $value["created_at"]
                            ); ?>
                        </td>

                        <td style="display: flex; justify-content: center; gap: 10px; margin: 6px; padding: 6px;">
                            <div>
                                <form method="post" action="./ajax/ungroup_order.php">
                                    <input type="hidden" name="id_table_gathers" value="<?php echo base64_encode($value["principal_command_id"]); ?>">
                                    <button type="submit" class="btn-ungroup">Desagrupar</button>
                                </form>
                            </div>
                            <div>
                                <form action="" method="post">
                                    <input type="hidden" name="">
                                    <button class="btn-disable">Faturar</button>
                                </form>
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