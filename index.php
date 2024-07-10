<?php

ob_start();

include_once 'config/config.php';
include_once 'classes/panel.php';

if (Panel::Logged()) {
    include ('homepage.php');
} else {
    include ('login.php');
}

ob_end_flush();

?>