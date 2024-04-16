<?php 
session_start();
if ($_SESSION['user']) {
    $flag = 1;
}
include 'connect.php'; 
// Проверяем, есть ли данные о пользователе в сессии
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    // Если нет, выводим сообщение об ошибке и завершаем скрипт
    echo '<p>Вы не авторизованы.</p>';
    exit();
}

// Устанавливаем флаг в 1, если пользователь авторизован
$flag = 1;


// Проверяем, является ли пользователь гостем
if ($_SESSION['userType'] == 'гости') {
    // Получаем имя пользователя из сессии и экранируем его
    $username = mysqli_real_escape_string($conn, $_SESSION['userName']);

    // Подготавливаем запрос для извлечения данных о госте
    $query = "SELECT * FROM `гости` WHERE `Логин` = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        die('Ошибка при подготовке запроса: ' . mysqli_error($connect));
    }
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Проверяем, есть ли результаты запроса
    if ($result && mysqli_num_rows($result) > 0) {
        // Если есть, извлекаем данные о госте
        $guest = mysqli_fetch_assoc($result);
        // Дополнительная обработка данных о госте, если необходимо
    }

    // Закрываем подготовленное выражение
    mysqli_stmt_close($stmt);
} else {
    // Если пользователь не является гостем, выводим сообщение об ошибке
    echo '<p>Вы не авторизованы как гость.</p>';
}

// Закрываем соединение с базой данных
//mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ЛК гостя</title>
<style>
    h2 {
        text-align: center;
    }
    .news-item {
        margin-bottom: 10px;
    }
    .form-item {
        margin-bottom: 10px;
    }
    .guest-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    tr {
        background-color: #f6f1e7;
    }

    form {
        margin-top: 10px;
    }
    a {
        text-decoration: none;
        color: #0D0176;
    }
    a:hover {
        text-decoration: underline;
    }
    .imgc{
        width: 100%;
        max-width: 300px;
        margin: 5px auto;
    }
    b {
        color: #0D0176;
    }
    /* Расположение элементов */
    .container {
        display: flex;
    }
    .left-column {
        width: 40%; /* Левая колонка занимает 1/3 ширины */
        align-items: center;
    }
    .right-column {
        width: 60%; /* Правая колонка занимает остальное место */
        align-items: center;
    }
    .tdtext {
        width: 100%;
    }

    .lk-tables {
        width: 500px;
    }
    .lk-tables th {
        width: 20%;
        font-weight: normal;
        font-size: 14px;
        color: #000000;
        padding: 10px 12px;
        background: #ffffff;
    }
    .lk-tables td {
        width: 100%;
        box-sizing: border-box;
        color: #7c6239;
        border-top: 1px solid white;
        padding: 10px 12px;
        background: #EDE5D9;
    }
    .lk-tables td input[type="text"]{
        width: 100%;
        box-sizing: border-box;
    }
    /*кнопки в левом столбце*/
    .left-column {
        display: flex;
        flex-direction: column;
    }

    .left-column .navs-item {
        margin-bottom: 10px; /* Добавляем небольшой отступ между кнопками */
    }

    .left-column .navs-item a {
        display: inline-block;
        text-decoration: none;
        color: #0D0176;
    }

    .left-column .navs-item .notbtnlk {
    margin: 10px; /* Расстояние между кнопками */
    transition: 0.3s;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    color: #101047;
    }

    .left-column .navs-item a:hover {
        text-decoration: underline;
    }
    
    .bron-item {
    background-color: white;
    margin: 20px;
    padding: 20px 80px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

</style>
</head>
<body>

<header>
  <div class="navbar">
        <div class="container">
          <div class="navbar-nav">
            <div class="navs" id="navs">
              <div class="navs-item notbtn"><a href="index.php" class="txt-uppercase">WorldTaste</a></div>
              <div class="navs-item notbtn"><a href="menu.php" class="txt-uppercase">Меню</a></div>
              <div class="navs-item notbtn"><a href="rests.php" class="txt-uppercase">Рестораны</a></div>
            <? if($flag == 1){ ?>
                            <div class="navs-item"><a href="lk_guest.php"><button class="btn txt-uppercase shadow-sm">Личный кабинет</button></a></div>
                            <div class="navs-item"><a href="logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
                        <? }else{ ?>
                <div class="navs-item"><a href="./start.php"><button class="btn txt-uppercase shadow-sm">Вход</a></div>
                <div class="navs-item"><a href="./register.php"><button class="btn txt-uppercase shadow-sm">Регистрация</a></div>
            <? } ?> 
            </div>
          </div>
        </div>
  </div>
</header>

<div id="news-feed">
    <div class="container">
        <!-- Левая колонка -->
        <div class="left-column">
            <div class="bron-item">
                <div class="navs-item notbtnlk"><a href="bronir.php">Забронировать столик</a></div>
                <div class="navs-item notbtnlk"><a href="zakaz.php">История бронирований</button></a></div>
            </div>
        </div>
        <!-- Правая колонка -->
        <div class="right-column">
            <div class="news-item">
                <h2>Ваши данные</h2>
                <table class="lk-tables">
                    <form method="POST">
                        <tr>
                            <th><label for="fio">ФИО:</label></th>
                            <td><input type="text" id="fio" name="fio" value="<?php echo htmlspecialchars($guest['ФИО']); ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="phone">Телефон:</label></th>
                            <td><input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($guest['Телефон']); ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="email">Email:</label></th>
                            <td><input type="text" id="email" name="email" value="<?php echo htmlspecialchars($guest['Email']); ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="email">Логин:</label></th>
                            <td><input type="text" id="login" name="login" value="<?php echo htmlspecialchars($guest['Логин']); ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="password">Пароль:</label></th>
                            <td><input type="text" id="password" name="password" value="<?php echo htmlspecialchars($guest['Пароль']); ?>"></td>
                        </tr>
                        <tr>
                            <th colspan="2"><center><input type="submit" name="update" value="Сохранить"></center></th>
                        </tr>
                    </form>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    $id = $_SESSION['user']['Код_гостя'];
if (isset($_POST["update"])) {
    // Получаем данные из формы
    $fio = mysqli_real_escape_string($conn, $_POST['fio']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Формируем и выполняем запрос на обновление данных
    $update_query = "UPDATE `гости` SET `ФИО` = '$fio', `Телефон` = '$phone', `Email` = '$email', `Логин` = '$login', `Пароль` = '$password' WHERE `Код_гостя` = $id";
    if (mysqli_query($conn, $update_query)) {
        echo "Данные успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных: " . mysqli_error($conn);
        echo "ID: " .$id;
    }
}
?>
</body>
</html>
