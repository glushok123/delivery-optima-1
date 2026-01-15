<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// $info = json_decode($_POST); 
$rmid = $_POST;
// var_dump($rmid);
require_once 'connect.php';
try {
    $stmt = $dbh->prepare("DELETE FROM events WHERE id = ?");


    $stmt->bindParam(1, $rmid['rmid']);

    $stmt->execute();
    // echo ($rmid['rmid']."T".$rmid['rmtime']);
} catch (PDOExecption $e) {
    $dbh->rollback();
    print "Error!: " . $e->getMessage() . "</br>";
}
?>
