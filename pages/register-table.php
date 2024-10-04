<?php

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$page_permission = 'register-table';
if (!isset($_SESSION['user_permissions'][$page_permission]) || $_SESSION['user_permissions'][$page_permission] !== 1) {
    header("Location: " . INCLUDE_PATH . "access-denied.php");
    exit();
}

?>

<div class="box-content">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label text-white">Numero da mesa</label>
            <input type="text" id="name_table" class="form-control">
            <span id="name-error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" onclick="RegisterTableRequest()" type="button">Cadastrar</button>
        </div>
    </div>
</div>