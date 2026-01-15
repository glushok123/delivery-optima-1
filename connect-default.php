<?php
$host = 'localhost'; // адрес сервера
$database = 'a0458868_devdel'; // имя базы данных
$user = 'root'; // имя пользователя
$password = ''; // пароль
$dsn = "mysql:host={$host};dbname={$database}";
$dbh = new PDO($dsn, $user, $password);
?>
