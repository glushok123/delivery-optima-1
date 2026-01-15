<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'connect.php';

$stmt = $dbh->query("SELECT ev.id, ev.adddate, ev.addtime, ev.whoadd from events as ev ORDER BY id DESC LIMIT 50");
// $stmt->execute();
$data = $stmt->fetchAll();

// echo (json_encode($data));
// var_dump($data);
$finalArr = array();

foreach ($data as $key => $event) {
    $finalArr[$key] = array(
        "id" => $event["id"],
        "time" => $event["adddate"] . "T" . $event["addtime"],
        "desc" => mb_strtolower($event["whoadd"]),
        "cls" => mb_strtolower($event["whoadd"])

    );
}
echo(json_encode($finalArr));
// 	$finalArr += $event;
// 	// var_dump($event);
// 	// var_dump($finalArr);
// 	// $evfinal = array_merge($evfinal, $data[$key]);
// 	 $ev = json_encode($finalArr);

// 	// var_dump($ev);
// }


?>