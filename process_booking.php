<?php
session_start();
if ($_SESSION['user']) {
    $flag = 1;
}
include 'connect.php'; 

// Проверка, был ли пользователь авторизован
if (!isset($_SESSION['user'])) {
    // Если не авторизован, перенаправляем на страницу входа
    header("Location: start.php");
    exit();
}

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $branch = $_POST['branch'];
    $date = date('Y-m-d', strtotime($_POST['date']));
    $time = date('H:i:s', strtotime($_POST['time']));
    $persons = $_POST['persons'];
    
    // Получаем код пользователя из сессии
    $user_id = $_SESSION['user']['id'];
    
    // Добавляем информацию о бронировании в таблицу Бронирования
   // $sql = "INSERT INTO Бронирования (Код_филиала, Код_гостя, Дата_бронирования, Время_бронирования, Количество_персон) 
           // VALUES ((SELECT Код_филиала FROM Филиалы WHERE Название = 'branch'), 'user', 'date', 'time', 'persons')";
           $sql = "INSERT INTO бронирования (Код_филиала, Код_гостя, Дата_бронирования, Время_бронирования, Количество_персон) VALUES ((SELECT Код_филиала FROM филиалы WHERE Название = ?), ?, ?, ?, ?)";
    // Подготавливаем запрос
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Привязываем параметры
        $stmt->bind_param('ssssi', $branch, $user_id, $date, $time, $persons);
        
        // Выполняем запрос
        $stmt->execute();
        
        // Проверяем успешность выполнения запроса
        if ($stmt->affected_rows > 0) {
            // Если успешно, перенаправляем на страницу успешного бронирования
            header("Location: index.php");
            exit();
        } else {
            // Если произошла ошибка, показываем сообщение об ошибке
            echo "Ошибка при бронировании столика.";
        }
        
        // Закрываем подготовленное выражение
        //$stmt->close();
        //header("Location: bronir.php");
        exit();
    } else {
        // Если произошла ошибка при подготовке запроса, показываем сообщение об ошибке
        echo "Ошибка при подготовке запроса.";
    }
} else {
    // Если форма не была отправлена, перенаправляем на страницу бронирования
    header("Location: bronir.php");
    exit();
}
?>
