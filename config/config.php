<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', 'root');
define('DATABASE', 'Klitzke');

$title_home = 'Klitzke Software - Admin';
$title_login = 'Klitzke software - login';
$chave_secret = 'EFEGREWSWREGERGEGBTBBFDGBTGHERTGSFDSGVB';

define('INCLUDE_PATH', 'http://localhost/Klitzke/');
define('INCLUDE_PATH_PANEL', INCLUDE_PATH . 'pages/');
define('BASE_DIR_PAINEL', __DIR__ . '/public');

function SelectedMenu($par)
{
    $url = explode('/', @$_GET['url'])[0];
    if ($url == $par) {
        echo 'class="menu-active"';
    }
}

function VerificationMenu()
{
    if ($_SESSION['function'] == 'Gerente' || $_SESSION['function'] == 'CEO') {
        return;
    } else {
        echo 'style="display:none;"';
    }
}

?>