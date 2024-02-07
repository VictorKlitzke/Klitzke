<?php

if (isset($_POST['action'])) {
    $name_table = $_POST['name'];

    if ($name_table == '') {
        Panel::Alert('attention', 'Os campos não podem ficar vázios!');
    } else {
        $verification = Db::Connection()->prepare("SELECT * FROM table_requests WHERE name = ?");
        $verification->execute(
            array(
                $_POST['name']
            )
        );

        if ($verification->rowCount() == 0) {
            $arr = [
                'name' => $name_table,
                'name_table' => 'table_requests'
            ];
            Controllers::Insert($arr);
            Panel::Alert('sucess', 'O cadastro da mesa ' . $name_table . ' foi realizado com sucesso!');
        } else {
            Panel::Alert('error', 'Já existe uma mesa com este numero!');
        }
    }
}

?>

<div class="box-content">
    <form class="form" method="post" enctype="multipart/form-data">
        <div class="content-form">
            <label>Numero da mesa</label>
            <input type="text" name="name">
        </div>
        <div class="content-form">
            <input type="hidden" name="name_table" value="table_requests" />
            <input type="submit" name="action" value="Cadastrar">
        </div>
    </form>
</div>