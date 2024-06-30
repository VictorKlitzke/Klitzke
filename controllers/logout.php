<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../helpers/response.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class Logout
{
    public static function LoggoutUser()
    {
        $today = date("Y-m-d H:i:s");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Remove o cookie JWT
            setcookie('remember', 'true', time() - 1, '/');
            session_destroy();
            Response::send(true, 'Logout realizado com sucesso', $today);
        } else {
            Response::send(false, 'Método não permitido', $today);
        }
    }

}

?>