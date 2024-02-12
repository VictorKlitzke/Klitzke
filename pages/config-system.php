<div class="dasbord-global">
    <div class="card-global">
        <h2 class="h2-global">Multiplicar valor do produto</h2>
        <div class="global">
            <form class="form-global" action="" method="POST">
                <div class="content-form">
                    <label for="">Número a ser multiplicado</label>
                    <input name="multiply" type="text" placeholder="Número">
                </div>
                <br>
                <input type="hidden" name="status">
                <input type="hidden" name="name_table" value="config_multiply_product">
                <button class="button-global" name="action" type="submit">Configurar</button>
            </form>
        </div>
    </div>
</div>

<?php 

    if (isset($_POST['action'])) {
        $multiply = $_POST['multiply']; 
        $status = $_POST['status'];
        
        if ($multiply == '') {
            Panel::Alert('attention', 'O campo não pode ficar vázio!');
        } else {
            $verification = Db::Connection()->prepare("SELECT * FROM config_multiply_product WHERE multiply = ? AND status - ?");
            $verification->execute(
                array(
                    $_POST['multiply'],
                    $_POST['status']
                )
            );
    
            if ($verification->rowCount() == 0) {
                $arr = [
                    'multiply' => $multiply,
                    'status' => 1,
                    'name_table' => 'config_multiply_product'
                ];
                Controllers::Insert($arr);
                Panel::Alert('sucess', 'O cadastro da mesa ' . $multiply . ' foi realizado com sucesso!');
            } else {
                Panel::Alert('error', 'Já existe uma mesa com este numero!');
            }
        }
    }

?>