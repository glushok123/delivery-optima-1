<!doctype html>
<html lang="en">

<head>


    <title>Календарь доставок</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/calendar.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/spinner.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php
// echo md5('Оптимизация');
session_start();

if ($_SESSION['admin']) {
    header("Location: index.php");
    exit;
}
require_once 'connect.php';


if ($_POST['submit']) {
    $login = $_POST['user'];
    $stmt = $dbh->prepare("SELECT us.pass, us.courier from users as us where us.login = ?;");
    $stmt->bindParam(1, $login);
    $stmt->execute();
    $data = $stmt->fetchAll();
// var_dump($data);


    if ($data['0']['pass'] == md5($_POST['pass'])) {
        $_SESSION['admin'] = mb_strtolower($login);
        $_SESSION['is_courier'] = $data['0']['courier'];
        header("Location: index.php");
        exit;
    } else echo '<p class="infologin">Логин или пароль неверны!</p>';
}
?>

<div class="login">
    <span>Вход</span>

    <form method="post">
        <input placeholder="Логин" type="text" name="user"/><br/>
        <input placeholder="Пароль" type="password" name="pass"/><br/>
        <input type="submit" name="submit" value="Войти"/>
    </form>
</div>
</body>
</html>