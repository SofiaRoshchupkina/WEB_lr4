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


// Проверяем, является ли пользователь администратором
if ($_SESSION['userType'] == 'администраторы') {
    // Получаем имя администратора из сессии и экранируем его
    $username = mysqli_real_escape_string($conn, $_SESSION['userName']);

    // Подготавливаем запрос для извлечения данных о администраторе
    $query = "SELECT * FROM `администраторы` WHERE `Логин` = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        die('Ошибка при подготовке запроса: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Проверяем, есть ли результаты запроса
    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
    }

    // Закрываем подготовленное выражение
    mysqli_stmt_close($stmt);
} else {
    echo '<p>Вы не авторизованы как администратор.</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ЛК администратора</title>
<style>
    h2 {
        text-align: center;
    }
    .news-item {
        margin-bottom: 10px;
        text-align: center; 
    }

    .news-item table {
        margin: 0 auto; 
    }
    .form-item {
        margin-bottom: 10px;
    }
    .admin-info {
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
        color: #a16840;
    }
    /* Расположение элементов */
    .container {
        display: flex;
    }
    .tdtext {
        width: 100%;
    }

    .lk-tables {
        width: 60%;
    }

    .lk-tables th {
        width: 40%;
        font-weight: normal;
        font-size: 14px;
        color: #000000;
        padding: 10px 12px;
        background: #ffffff;
        text-align: right;
    }

   .lk-tables td {
        width: 60%;
        box-sizing: border-box;
        color: #a16840;
        border-top: 1px solid white;
        padding: 10px 12px;
        background: #fff;
        text-align: left;
    } 
    
    .bron-item {
    background-color: white;
    margin: 20px;
    padding: 20px 80px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

     /*таблица для бронирований ЛК админ*/
    .bron-table {
        width: 100%;
        border-collapse: separate;
    }
    .bron-table th, .td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .bron-table th {
        background-color: #d9b296;
        color:#fff;
    }
    .bron-table tr {
        background-color: #f6f1e7;
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
              <?php if(isset($flag) && $flag == 1): ?>
                    <div class="navs-item"><a href="<?php echo ($_SESSION['userType'] == 'гости') ? 'lk_guest.php' : 'lk_admin.php'; ?>"><button class="btn txt-uppercase shadow-sm">Личный кабинет</button></a></div>
                    <div class="navs-item"><a href="logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
                <?php else: ?>
                    <div class="navs-item"><a href="./start.php"><button class="btn txt-uppercase shadow-sm">Вход</button></a></div>
                    <div class="navs-item"><a href="./register.php"><button class="btn txt-uppercase shadow-sm">Регистрация</button></a></div>
                <?php endif; ?> 
            </div>
          </div>
        </div>
  </div>
</header>

<div id="features-box">
    <div class="news-item">
        <h2>ЛК администратора</h2>
        <table class="lk-tables">
        <?php
            // Получаем код филиала администратора
            $admin_branch_code = $admin['Код_филиала'];

            // Запрос на получение названия филиала
            $branch_query = "SELECT Название FROM филиалы WHERE Код_филиала = ?";
            $stmt = mysqli_prepare($conn, $branch_query);
            mysqli_stmt_bind_param($stmt, 'i', $admin_branch_code);
            mysqli_stmt_execute($stmt);
            $branch_result = mysqli_stmt_get_result($stmt);

            // Проверка наличия данных о филиале
            if (mysqli_num_rows($branch_result) > 0) {
                // Получение названия филиала
                $branch_data = mysqli_fetch_assoc($branch_result);
                $branch_name = $branch_data['Название'];
            }
        ?>
                <tr>
                    <th><label for="fio">ФИО администратора:</label></th>
                    <td><?php echo htmlspecialchars($admin['ФИО']); ?></td>
                </tr>
                <tr>
                    <th><label for="fio">Название филиала:</label></th>
                    <td><?php echo htmlspecialchars($branch_name); ?></td>
                </tr>
        </table>
        <table class="lk-tables">
            <tr>
                <th>Выберите действие:</th>
                <td><div class="navs-item notbtnlk"><a href="Amenu.php">Изменить меню</button></a></div>
                <div class="navs-item notbtnlk"><a href="Abron.php">Добавить бронирование</a></div></td>
            </tr>
        </table>
    </div>

    <div class="news-item">
        <h2>Бронирования</h2>
        <table class="bron-table">
            <tr>
                <td>
                    <form method="POST">
                        <b>Отображать </b>
                        <select name="bron">
                            <option value="all" <?php if(isset($_POST['bron']) && $_POST['bron'] == 'all') echo 'selected'; ?>>Все бронирования</option>
                            <option value="future" <?php if(isset($_POST['bron']) && $_POST['bron'] == 'future') echo 'selected'; ?>>Будущие бронирования</option>
                            <option value="past" <?php if(isset($_POST['bron']) && $_POST['bron'] == 'past') echo 'selected'; ?>>Прошедшие бронирования</option>
                        </select>
                        <input type="submit" name="bronir" value="Выбрать">
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <b>Выберите дату </b>
                        <input type="date" name="chosen_date" value="<?php if(isset($_POST['chosen_date'])) echo $_POST['chosen_date']; ?>">
                        <input type="submit" name="date_select" value="Выбрать">
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <b>Поиск по ФИО гостя </b>
                        <input type="text" name="guest_name" value="<?php if(isset($_POST['guest_name'])) echo $_POST['guest_name']; ?>">
                        <input type="submit" name="search_guest" value="Найти">
                    </form>
                </td>
            </tr>
        </table>

        <table class="bron-table">
            <tr>
                <th>Номер бронирования</th>
                <th>Дата бронирования</th>
                <th>Время бронирования</th>
                <th>Количество персон</th>
                <th>ФИО гостя</th>
                <th>Действие</th>
            </tr>
            <?php
            if (isset($_POST['bronir']) || isset($_POST['date_select']) || isset($_POST['search_guest']) || empty($_POST)) {
                $bron_type = isset($_POST['bron']) ? $_POST['bron'] : '';
                $chosen_date = isset($_POST['chosen_date']) ? $_POST['chosen_date'] : '';
                $guest_name = isset($_POST['guest_name']) ? $_POST['guest_name'] : '';

                // Формируем условие для SQL-запроса в зависимости от выбранного типа бронирования, даты и ФИО гостя
                $condition = '';
                if (empty($chosen_date)) {
                    switch ($bron_type) {
                        case 'all':
                            break;
                        case 'future':
                            $condition .= " AND b.Дата_бронирования >= CURDATE()";
                            break;
                        case 'past':
                            $condition .= " AND b.Дата_бронирования < CURDATE()";
                            break;
                        default:
                            break;
                    }
                } else {
                    $condition .= " AND b.Дата_бронирования = '$chosen_date'";
                }

                if (!empty($guest_name)) {
                    $condition .= " AND g.ФИО LIKE '%$guest_name%'";
                }

                // Запрос на получение информации о бронированиях в зависимости от выбранного типа, даты и ФИО гостя
                $booking_query = "SELECT b.Код_бронирования, b.Дата_бронирования, b.Время_бронирования, b.Количество_персон, g.ФИО
                                        FROM бронирования b
                                        INNER JOIN гости g ON b.Код_гостя = g.Код_гостя
                                        WHERE b.Код_филиала = ? $condition
                                        ORDER BY `Дата_бронирования` ";
                $stmt = mysqli_prepare($conn, $booking_query);
                mysqli_stmt_bind_param($stmt, 'i', $admin_branch_code);
                mysqli_stmt_execute($stmt);
                $booking_result = mysqli_stmt_get_result($stmt);

                // Проверка наличия данных о бронированиях
                if (mysqli_num_rows($booking_result) > 0) {
                    // Вывод данных о бронированиях в таблицу
                    while ($row = mysqli_fetch_assoc($booking_result)) { 
                        // Вывод данных о бронированиях
                        echo "<tr>";
                        echo "<form method='POST'>";
                        echo "<td>" . $row['Код_бронирования'] . "</td>";
                        echo "<input type='hidden' name='id' value='" . $row['Код_бронирования'] . "'>";
                        echo "<td>" . date('d.m.Y', strtotime($row['Дата_бронирования'])) . "</td>";
                        echo "<td>" . date('H:i', strtotime($row['Время_бронирования'])) . "</td>";
                        echo "<td>" . $row['Количество_персон'] . "</td>";
                        echo "<td>" . $row['ФИО'] . "</td>"; // Замена кода гостя на его ФИО
                        ?>
                            <td><input type="submit" name="delete" value="Удалить"></td>
                            </tr>
                        </form>
                    <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>Нет данных о бронированиях для данного филиала</td></tr>";
                }
            }
            if (isset($_POST["delete"])) {
                $id = mysqli_real_escape_string($conn, $_POST['id']);
                
                // Формируем SQL-запрос для удаления записи
                $delete_query = "DELETE FROM `бронирования` WHERE `Код_бронирования` = $id";
                
                // Выполняем запрос
                if (mysqli_query($conn, $delete_query)) {
                    echo "Запись успешно удалена.";
                } else {
                    echo "Ошибка при удалении записи: " . mysqli_error($conn);
                }
            } 
            ?>
        </table>
    </div>
</div>


</body>
</html>
