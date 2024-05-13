<?php  
session_start();
if ($_SESSION['user']) {
    $flag = 1;
}
include 'connect.php'; 

$IDmenu = $_GET['id'] ?? null;

if ($IDmenu) {
    // Подготовка запроса с использованием подготовленного выражения
    $stmt = mysqli_prepare($connect, "DELETE FROM `меню` WHERE `Код_блюда` = ?");
    mysqli_stmt_bind_param($stmt, "i", $IDmenu);
    // Выполнение запроса
    if (mysqli_stmt_execute($stmt)) {
        echo "Блюдо успешно удалено.";
    } else {
        echo "Ошибка при удалении блюда: " . mysqli_error($connect);
    }

    // Закрытие подготовленного выражения
    mysqli_stmt_close($stmt);
    } else {
        echo "Не указан код блюда.";
    }

    // Перенаправление на страницу sql.php
    header('Location: Amenu.php');
    die();
?>