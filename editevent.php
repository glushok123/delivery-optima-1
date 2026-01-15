<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// $info = json_decode($_POST); 
$info = $_POST;
var_dump($info);
require_once 'connect.php';
try {
    $stmt = $dbh->prepare("UPDATE events SET comment=?,addtime=?,phone=?,adress=? WHERE id = ?");// запрос на добавление доставки
    var_dump($stmt);
    $stmt->bindParam(1, $comment);

    $stmt->bindParam(2, $addtime);
    $stmt->bindParam(3, $phone);
    $stmt->bindParam(4, $adress);
    $stmt->bindParam(5, $id);

    $id = $info["id"];
    $comment = $info["commment"];
    $adddate = $info["date"];
    $addtime = $info["time"];
    $phone = $info["phone"];
    $adress = $info["adress"];
    $oldtime = $info["oldtime"];

    $stmt->execute();
    echo "изм";
} catch (PDOExecption $e) {
    $dbh->rollback();
    print "Error!: " . $e->getMessage() . "</br>";
}
?>
