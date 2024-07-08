<?php

include_once 'routes.php'; 

$path = rtrim($_SERVER['REQUEST_URI'], '/');

if ($path === '' || $path === '/') {
    $path = '/Klitzke/';
}

Routes::route($path);
?>