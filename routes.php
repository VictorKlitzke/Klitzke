<?php

include_once 'classes/panel.php';

class Routes
{
    public static function route($path)
    {
        if (Panel::Logged()) {
            if ($path = '/Klitzke/homepage') {
                include 'homepage.php';
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "404 Not Found";
            }
        } else {
            include 'login.php';
        }
    }
}

?>