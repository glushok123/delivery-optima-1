<?php
session_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// $info = json_decode($_POST); 
$info = $_POST;
// var_dump($info);
require_once 'connect.php';
try {
    $stmt = $dbh->prepare("INSERT INTO events(comment,adddate,addtime,whoadd,phone,adress) values (?,?,?,?,?,?)");// запрос на добавление доставки

    $stmt->bindParam(1, $comment);
    $stmt->bindParam(2, $adddate);
    $stmt->bindParam(3, $addtime);
    $stmt->bindParam(4, $whoadd);
    $stmt->bindParam(5, $phone);
    $stmt->bindParam(6, $adress);


    $comment = $info["commment"];
    $adddate = $info["date"];
    $addtime = $info["time"];
    $whoadd = $_SESSION['admin'];
    $phone = $info["phone"];
    $adress = $info["adress"];
    $stmt->execute();
// echo "added";
} catch (PDOExecption $e) {
    $dbh->rollback();
    print "Error!: " . $e->getMessage() . "</br>";
}
?>
