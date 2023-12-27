<?php 

include_once '../config/config.php';
include_once '../services/db.php';

$id = base64_decode($_POST['id']);
$disable_id = base64_decode($_POST['disable_id']);

$sql = Db::Connection();
$exec = $sql->prepare("SELECT * FROM users WHERE id = $id");
$exec->execute(); 
$disables = $exec->fetch();

var_dump(store($id, $disable_id));

if (!$disables) {
  store($id, $disable_id);
}

function store($id, $disable_id){

 if ($disable_id == NULL) {
  $sql = Db::Connection();
  $exec = $sql->prepare("UPDATE users SET disable = 1 WHERE id = ?");
  $exec->execute([$id]);
 } else {
  return;
 }

  header('Location: ' . INCLUDE_PATH . 'list-users');
}
