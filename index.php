<?php
ob_start();

include_once 'config/config.php';
include_once 'classes/panel.php';

// Pegando a URL solicitada
$request = $_SERVER['REQUEST_URI'];

// Removendo parâmetros de query string da URL
$request = strtok($request, '?');

// Roteamento básico
switch ($request) {
  case '/home':
    if (Panel::Logged()) {
      include ('home.php');
    } else {
      header('Location: ' . INCLUDE_PATH);
      exit();
    }
    break;
  case '/':
  case '/Klitzke/':
  case '/Klitzke':
    include ('login.php');
    break;
  // Adicione mais casos conforme necessário
  default:
    // Página padrão ou erro 404
    include ('404.php');
    break;
}

ob_end_flush();
