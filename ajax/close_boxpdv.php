<?php

include_once '../config/config.php';
include_once '../services/db.php';

$value_close = $_POST['value_close'];
$date_close = $_POST['date_close'];

$sql = Db::Connection();

$exec = $sql->prepare("UPDATE boxpdv SET value_close = ? AND date_close = ?");
$exec->execute([$value_close, $date_close]);

echo 'teste';

?>