<?php 
$server= "localhost";
$user="root";
$password="";
$DB="restaurant";
$conn = mysqli_connect($server, $user, $password, $DB)
OR die ("Невозможно соединиться с mysql-сервером. Выполнение программы остановлено");
	mysqli_query($conn, "SET NAMES utf8");
?>