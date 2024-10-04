<?php
session_start(); // Inicie a sessão

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../classes/panel.php';
include_once '../classes/controllers.php';
include_once '../helpers/response.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$sql = Db::Connection();

$check_access = Querys::QueryAccess($user_id, $sql);
echo json_encode(['access' => $check_access]);

class Querys
{
  public static function QueryAccess($user_id, $sql)
  {
    try {

      $check_access = $sql->prepare("SELECT access FROM users WHERE id = :user_id");
      $check_access->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $check_access->execute();
      return $check_access->fetchColumn();

    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage(), 'code' => $e->getCode()]);
    }
  }
}
