<?php 

include_once '../config/config.php';
include_once '../services/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id_table_gathers = base64_decode($_POST['id_table_gathers']);

$sql = Db::Connection();
$exec = $sql->prepare("SELECT * FROM request_gathers WHERE id = $id_table_gathers");
$exec->execute();
$stmt = $exec->fetch();

var_dump($exec);die();

?>