<?php

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

$sql = Db::Connection();

class Register {
    public static function RegisterUsers($sql) {

    }

    public static function RegisterClient($sql) {

    }

    public static function RegisterCompany($sql) {

    }
}

?>
