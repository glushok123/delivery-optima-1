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
echo md5('mypass');
session_start();

if ($_SESSION['admin']) {
    header("Location: admin.php");
    exit;
}

$admin = 'admin';
$pass = 'a029d0df84eb5549c641e04a9ef389e5';

if ($_POST['submit']) {
    if ($admin == $_POST['user'] and $pass == md5($_POST['pass'])) {
        $_SESSION['admin'] = $admin;
        header("Location: admin.php");
        exit;
    } else echo '<p>Логин или пароль неверны!</p>';
}
?>
<p><a href="index.php">Главная</a> | <a href="contact.php">Контакты</a> | <a href="admin.php">Админка</a></p>
<hr/>
Это страница авторизации.
<br/>
<form method="post">
    Username: <input type="text" name="user"/><br/>
    Password: <input type="password" name="pass"/><br/>
    <input type="submit" name="submit" value="Войти"/>
</form>