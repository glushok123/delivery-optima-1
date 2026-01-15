<?php
// echo "получить";
session_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$id = substr($_POST["id"], 2);
require_once 'connect.php';
$stmt = $dbh->prepare("SELECT ev.comment, ev.adddate, ev.addtime, ev.whoadd, ev.phone, ev.adress from events as ev where ev.id = ?;");
$stmt->bindParam(1, $id);
$stmt->execute();
$data = $stmt->fetchAll();
// var_dump($data);
$is_owner = false;
if ($data['0']['whoadd'] == $_SESSION['admin']) {
    $is_owner = true;
}
// var_dump($data);
$getfullevent = array(
    "id" => $id,
    "comment" => $data['0']['comment'],
    "adddate" => $data['0']['adddate'],
    "addtime" => $data['0']['addtime'],
    "is_owner" => $is_owner,
    "phone" => $data['0']['phone'],
    "adress" => $data['0']['adress']
);
echo(json_encode($getfullevent));

?>