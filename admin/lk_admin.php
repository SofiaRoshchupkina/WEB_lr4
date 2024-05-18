<?php 
session_start();

// Проверяем, есть ли данные о пользователе в сессии
if (!isset($_SESSION['adminUsername'])) {
    echo '<p>Вы не авторизованы.</p>';
    exit();
}

include 'connect.php'; 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Включаем строгий режим отчета ошибок

// Устанавливаем флаг в 1, если пользователь авторизован
$flag = 1;

// Получаем имя администратора и его код филиала из сессии
$username = mysqli_real_escape_string($conn, $_SESSION['adminUsername']);
$branchCode = $_SESSION['branchCode'];

// Обработка удаления
if (isset($_POST["delete"])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $delete_query = "DELETE FROM `бронирования` WHERE `Код_бронирования` = $id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<p>Запись успешно удалена.</p>";
    } else {
        echo "<p>Ошибка при удалении записи: " . mysqli_error($conn) . "</p>";
    }
}

// Обработка обновления данных
if (isset($_POST["save"])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $new_date = mysqli_real_escape_string($conn, $_POST['Дата_бронирования']);
    $new_time = mysqli_real_escape_string($conn, $_POST['Время_бронирования']);
    $new_people = mysqli_real_escape_string($conn, $_POST['Количество_персон']);

    $update_query = "UPDATE бронирования SET Дата_бронирования = ?, Время_бронирования = ?, Количество_персон = ? WHERE Код_бронирования = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'ssii', $new_date, $new_time, $new_people, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Переменная для проверки, нужно ли обновлять запись
$update_id = isset($_POST['update']) ? $_POST['id'] : null;

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

        /* таблица для бронирований ЛК админ */
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
            color: #fff;
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
                <div class="navs-item"><a href="lk_admin.php"><button class="btn txt-uppercase shadow-sm">Личный кабинет</button></a></div>
                <div class="navs-item"><a href="logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
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
            // Запрос на получение названия филиала
            $branch_query = "SELECT Название FROM филиалы WHERE Код_филиала = ?";
            $stmt_branch = mysqli_prepare($conn, $branch_query);
            mysqli_stmt_bind_param($stmt_branch, 'i', $branchCode);
            mysqli_stmt_execute($stmt_branch);
            $branch_result = mysqli_stmt_get_result($stmt_branch);
            $branch_name = mysqli_fetch_assoc($branch_result)['Название'];
            mysqli_stmt_close($stmt_branch);
        ?>
                <tr>
                    <th><label for="fio">Название филиала:</label></th>
                    <td><?php echo htmlspecialchars($branch_name); ?></td>
                </tr>
        </table>
        <table class="lk-tables">
            <tr>
                <th>Выберите действие:</th>
                <td><div class="navs-item notbtnlk"><a href="Amenu.php">Изменить меню</a></div>
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
                        <input type="hidden" name="chosen_date" value="<?php if(isset($_POST['chosen_date'])) echo $_POST['chosen_date']; ?>">
                        <input type="hidden" name="guest_name" value="<?php if(isset($_POST['guest_name'])) echo $_POST['guest_name']; ?>">
                        <input type="submit" name="bronir" value="Выбрать">
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <b>Выберите дату </b>
                        <input type="date" name="chosen_date" value="<?php if(isset($_POST['chosen_date'])) echo $_POST['chosen_date']; ?>">
                        <input type="hidden" name="bron" value="<?php if(isset($_POST['bron'])) echo $_POST['bron']; ?>">
                        <input type="hidden" name="guest_name" value="<?php if(isset($_POST['guest_name'])) echo $_POST['guest_name']; ?>">
                        <input type="submit" name="date_select" value="Выбрать">
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <b>Поиск по ФИО гостя </b>
                        <input type="text" name="guest_name" value="<?php if(isset($_POST['guest_name'])) echo $_POST['guest_name']; ?>">
                        <input type="hidden" name="bron" value="<?php if(isset($_POST['bron'])) echo $_POST['bron']; ?>">
                        <input type="hidden" name="chosen_date" value="<?php if(isset($_POST['chosen_date'])) echo $_POST['chosen_date']; ?>">
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
                <th colspan="2">Действие</th>
            </tr>
            <?php
            $condition = '';
            if (isset($_POST['bronir']) || isset($_POST['date_select']) || isset($_POST['search_guest']) || isset($_POST['save']) || isset($_POST['update'])) {
                $bron_type = isset($_POST['bron']) ? $_POST['bron'] : '';
                $chosen_date = isset($_POST['chosen_date']) ? $_POST['chosen_date'] : '';
                $guest_name = isset($_POST['guest_name']) ? $_POST['guest_name'] : '';

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
                    }
                } else {
                    $condition .= " AND b.Дата_бронирования = '$chosen_date'";
                }

                if (!empty($guest_name)) {
                    $condition .= " AND g.ФИО LIKE '%$guest_name%'";
                }
            }

            $booking_query = "SELECT b.Код_бронирования, b.Дата_бронирования, b.Время_бронирования, b.Количество_персон, g.ФИО
                                FROM бронирования b
                                INNER JOIN гости g ON b.Код_гостя = g.Код_гостя
                                WHERE b.Код_филиала = ? $condition
                                ORDER BY `Дата_бронирования`";
            $stmt = mysqli_prepare($conn, $booking_query);
            mysqli_stmt_bind_param($stmt, 'i', $branchCode);
            mysqli_stmt_execute($stmt);
            $booking_result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($booking_result) > 0) {
                while ($row = mysqli_fetch_assoc($booking_result)) {
                    echo "<tr>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $row['Код_бронирования'] . "'>";
                    echo "<input type='hidden' name='bron' value='" . $bron_type . "'>";
                    echo "<input type='hidden' name='chosen_date' value='" . $chosen_date . "'>";
                    echo "<input type='hidden' name='guest_name' value='" . $guest_name . "'>";
                    if ($update_id == $row['Код_бронирования']) {
                        // Выводим поля для редактирования
                        echo "<td>" . $row['Код_бронирования'] . "</td>";
                        echo "<td><input type='date' name='Дата_бронирования' value='" . date('Y-m-d', strtotime($row['Дата_бронирования'])) . "'></td>";
                        echo "<td><input type='time' name='Время_бронирования' value='" . date('H:i', strtotime($row['Время_бронирования'])) . "'></td>";
                        echo "<td><input type='number' name='Количество_персон' value='" . $row['Количество_персон'] . "'></td>";
                        echo "<td>" . $row['ФИО'] . "</td>"; // ФИО неизменяемое
                        echo "<td><input type='submit' name='save' value='Сохранить'></td>";
                    } else {
                        // Обычный режим отображения
                        echo "<td>" . $row['Код_бронирования'] . "</td>";
                        echo "<td>" . date('d.m.Y', strtotime($row['Дата_бронирования'])) . "</td>";
                        echo "<td>" . date('H:i', strtotime($row['Время_бронирования'])) . "</td>";
                        echo "<td>" . $row['Количество_персон'] . "</td>";
                        echo "<td>" . $row['ФИО'] . "</td>";
                        echo "<td><input type='submit' name='update' value='Обновить'></td>";
                    }
                    echo "<td><input type='submit' name='delete' value='Удалить'></td>";
                    echo "</form></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Нет данных о бронированиях для данного филиала</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
