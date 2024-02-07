
<?php 

$company_id = Controllers::Select('company');
$id_company = $company_id['id'];

if (isset($_POST['action'])) {

    $number_pix = $_POST['pix'];
    $number_pix = doubleval($number_pix);
    $company_id['id_company'];

    if ($number_pix == '') {
        Panel::Alert('attention', 'Os campos não podem ficar vázios!');
    } else {
        $verification = Db::Connection()->prepare("SELECT * FROM banck_account WHERE pix = ? AND id_company = ?");
        $verification->execute(
            array(
                $_POST['pix'],
                $company_id['id_company']
            )
        );

        if ($verification->rowCount() == 0) {
            $arr = [
                'pix' => $number_pix,
                'id_company' => $id_company,
                'name_table' => 'banck_account'
            ];

            Controllers::Insert($arr);
            Panel::Alert('sucess', 'O cadastro da conta ' . $number_pix . ' foi realizado com sucesso!');
        } else {
            Panel::Alert('error', 'Já existe uma conta com este numero!');
        }
    }
}

?>

<div class="box-content">
    <form class="form" method="post" action="">
    <div class="content-form">
            <label>Numero Pix</label>
            <input type="text" name="pix">
    </div>
    <div class="content-form">
        <input type="hidden" name="id_company" />
        <input type="hidden" name="name_table" value="banck_account" />
        <input type="submit" name="action" value="Cadastrar">
    </div>
    </form>
</div>