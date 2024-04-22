<h1 class="h2-global">Ajuntar Comandas</h1>
<main>
    <section class="tables-dis">
        <h2 class="h2-gathers">Mesas Disponíveis</h2>
        <?php

        $tables_command = Controllers::SelectAllTableRequests('request');

        foreach ($tables_command as $key => $value) {

            ?>
            <div class="table-gathers"
                onclick="addGathersArray(<?php echo $key ?>, '<?php echo $value['id'] ?>', '<?php echo $value['table_request'] ?>', '<?php echo $value['total_request'] ?>' )">
                <p>Comanda:
                    <?php echo $value['table_request']; ?>
                </p>
                <p> Valor Total:
                    <?php echo $value['total_request']; ?>
                </p>
            </div>

        <?php } ?>
    </section>

    <section>
        <h2 class="h2-gathers">Mesas Selecionadas</h2>
        <div class="table-gathers-selected">
            <table id="table-gathers-selected" style="color: #000;">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Comanda</td>
                        <td>Valor total da comanda</td>
                    </tr>
                </thead>
                <tbody style="color: #000;">
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>
<div class="w100 info-total-gathers">
    <button class="button-gathers" id="button-gathers" onclick="GathersTables()">Ajuntar comandas</button>
    <h2 class="span-gathers">Total: <span id="totalizador">R$ 0,00</span></h2>
</div>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>