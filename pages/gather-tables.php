<?php

if (isset($_GET['id'])) {
    $id = (int) base64_decode($_GET['id']);
    $update = Controllers::SelectRequestItensOrder('request', 'r.id=?', array($id));
} else {
    Panel::alert('error', 'Você precisa passar o parametro ID.');
    die();
}

?>

<h2 class="h2-global">Ajuntar commandas</h2>
<div class="gather-tables">

    <?php

    $tables_command = Controllers::SelectAllTableRequests('table_requests');

    foreach ($tables_command as $key => $value) {

    ?>

        <div class="info-tabes">
            <h2 class="h2-gather">
                <?php echo $value['name']; ?>
                <form action="">
                    <input type="hidden" name="name_table" id="<?php echo base64_encode($value['name']); ?>">
                </form>
            </h2>
        </div>

    <?php } ?>

</div>

<br>

<h2 class="h2-global">Comandas ajuntadas</h2>
<div class="command-gathers">
    <div class="info-gathers">
        <p>Comanda:</p>
    </div>
</div>


<form id="form-juntar-mesas" action="processar_juncao.php" method="post">
    <input type="hidden" name="mesas_selecionadas" id="mesas_selecionadas">
    <button type="submit">Ajuntar Mesas</button>
</form>
