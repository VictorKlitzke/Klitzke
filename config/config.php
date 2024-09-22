<?php

session_start();

date_default_timezone_set('America/Cuiaba');

define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', 'root');
define('DATABASE', 'klitzke');

$title = 'Klitzke Software';
$title_home = 'Klitzke Software - Admin';
$title_login = 'Klitzke software - login';
$chave_secret = 'EFEGREWSWREGERGEGBTBBFDGBTGHERTGSFDSGVB';

define('INCLUDE_PATH', 'http://localhost:3000/Klitzke/');
define('INCLUDE_JAVASCRIPT', 'http://localhost:3000/Klitzke/js/');
define('INCLUDE_PATH_PANEL', INCLUDE_PATH . 'pages/');

define('BASE_DIR_PAINEL', __DIR__ . '/public');

function SelectedMenu($par)
{
    $url = explode('/', @$_GET['url'])[0];
    if ($url == $par) {
        echo 'class="menu-active"';
    }
}

function VerificationAccess($par) {

    $url = explode('/', @$_GET['url'])[0];
    if ($url != $par) {
        include_once('./error/error-access.php');
        exit();
    }
    
}
function VerificationAccessADM() {
    return isset($_SESSION['access']) && $_SESSION['access'] == 100;
}

function VerificationMenu()
{
    if ($_SESSION['function'] == 'Gerente' || $_SESSION['function'] == 'CEO') {
        return;
    } else {
        echo 'style="display:none;"';
    }
}

$showMenuAdm = VerificationAccessADM();

?>