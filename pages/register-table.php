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

<div class="container-fluid bg-light p-4 rounded-4 border shadow-lg">
    <div class="row g-3">
        <h2 class="text-dark mt-4">Cadastrar Comanda</h2>
        <div class="col-md-4">
            <label class="form-label text-dark">Comanda</label>
            <input type="text" id="name_table" class="form-control border-dark" placeholder="Numero da comanda">
            <span id="error" class="error-message">Campo está invalido, Ajuste se possivel.</span>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" onclick="RegisterTableRequest()" type="button">Cadastrar</button>
        </div>
    </div>
</div>