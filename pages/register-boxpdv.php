<div class="box-content">
    <h2>Abrir caixa</h2>
    <form class="form" method="post" enctype="multipart/form-data">

        <?php

        $company = Controllers::SelectAll('company');

        $id_users = Controllers::Select("users");

        foreach ($company as $key => $values) {
            if ($values['id'] == $values['id']) {
                if ($id_users['id'] == $id_users['id']) {
                    if (isset($_POST['action'])) {

                        $value = $_POST['value'];
                        $value = str_replace(',', '.', preg_replace("/[^0-9,.]/", "", $value));
                        number_format($value);
                        $open_date = $_POST['open_date'];
                        $observation = $_POST['observation'];
                        $id_company = $values['id'];
                        $status = $_POST['status'];
                        $id_users = $id_users['id'];

                        if ($value == '' || $open_date == '') {
                            Panel::Alert('attention', 'Os campo não podem ficar vázios!');
                        } else {
                            $verification = Db::Connection()->prepare("SELECT value, open_date, observation FROM `boxpdv` WHERE value = ? AND open_date = ? AND observation = ? 
                                                                    AND id_company = ? AND status = ? AND id_users = ?");
                            $verification->execute(
                                array(
                                    $_POST['value'],
                                    $_POST['open_date'],
                                    $_POST['observation'],
                                    $_POST['id_company'],
                                    $id_users['id'],
                                    $status['status']
                                )
                            );

                            if ($verification->rowCount() == 0) {
                                $arr =
                                    [
                                        'value' => $value,
                                        'open_date' => $open_date,
                                        'observation' => $observation,
                                        'id_company' => $id_company,
                                        'status' => 1,
                                        'id_users' => $id_users,
                                        'name_table' => 'boxpdv'
                                    ];

                                Controllers::Insert($arr);
                                $_SESSION['value'] = $value;
                                $_SESSION['open_date'] = $open_date;
                                Panel::Alert('sucess', 'Caixa foi aberto no valor de ' . $value);
                            }
                        }
                    }
                } else {
                    Panel::Alert('error', 'Nenhum usuário logado encontrado!!!');
                }
            }
        }

        ?>

        <div class="content-form">
            <label for="">Valor</label>
            <input type="text" name="value" id="value" oninput="formmaterReal(this)" placeholder="R$ 0,00">
        </div>
        <div class="content-form">
            <label for="">Abertura</label>
            <input type="date" name="open_date" value="<?php if ($today = date("Y-m-d H:i:s")) {
                echo $today;
            } else {
                false;
            } ?>">
        </div>
        <div class="content-form">
            <label for="">Observação</label>
            <input type="text" name="observation">
        </div>
        <div class="content-form">
            <input type="hidden" name="id_users"/>
            <input type="hidden" name="id_company"/>
            <input type="hidden" name="status"/>
            <input type="hidden" name="name_table" value="boxpdv"/>
            <input type="submit" name="action" value="Abrir caixa">
        </div>
    </form>

    <script src="<?php echo INCLUDE_PATH_PANEL; ?>../js/values.js"></script>