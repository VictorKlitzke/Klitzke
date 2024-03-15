<?php

if (isset($_GET['id'])) {
    $id = (int) base64_decode($_GET['id']);
    $update = Controllers::SelectRequestItensOrder('request', 'r.id=?', array($id));
} else {
    Panel::alert('error', 'Você precisa passar o parametro ID.');
    die();
}

?>

<h1 class="h2-global">Ajuntar Comandas</h1>
<main>
    <section class="mesas-disponiveis">
        <h2>Mesas Disponíveis</h2>
        <?php

        $tables_command = Controllers::SelectAllTableRequests('request');

        foreach ($tables_command as $key => $value) {

            ?>
            <div class="table-gathers" data-index="<?php echo $key; ?>" data-valor="<?php echo $value['total_request']; ?>">
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

        <h2>Mesas Selecionadas</h2>
        <div class="table-gathers-selected">

            <h2 class="span-gathers right">Total: <span id="totalizador">R$ 0,00</span></h2>

            <button>Ajuntar comandas</button>
        </div>
    </section>
</main>

<script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/add_request.js"></script>