<?php 
session_start();
if ($_SESSION['user']) {
    $flag = 1;
}
include 'connect.php'; 
$code  = $_SESSION['bookingId'];
$userId = $_SESSION['user']['Код_гостя'];
$query = "SELECT заказы.*, меню.*, бронирования.*, филиалы.Название
          FROM `заказы` 
          INNER JOIN `меню` ON заказы.Код_блюда = меню.Код_блюда
          INNER JOIN `бронирования` ON заказы.Код_бронирования = бронирования.Код_бронирования
          INNER JOIN `филиалы` ON бронирования.Код_филиала = филиалы.Код_филиала
          WHERE бронирования.Код_гостя = $userId";
$result = mysqli_query($conn, $query);

// Переменные для отслеживания предыдущего значения Код_бронирования
$prevBookingId = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Рестораны группы WorldTaste</title>
<style>
    table {
	width: 100%;
	border-collapse: separate;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #0D0176;
        color:#ddd;
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
          <?php if($flag == 1): ?>
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

<?php
while ($analysis = mysqli_fetch_array($result)) {
    // Если значение Код_бронирования изменилось, закрываем предыдущий "features-box" и начинаем новый
    if ($analysis['Код_бронирования'] != $prevBookingId) {
        // Если это не первое бронирование, закрываем предыдущий "features-box"
        if ($prevBookingId !== null) {
            echo '</table></div>'; // Закрываем предыдущий "features-box"
        }
        // Начинаем новый "features-box"
        echo '<div id="features-box"><div class="news-item">';
        ?>
        <table>
            <tr>
                <th>Название</th>
                <th>Дата бронирования</th>
                <th>Время бронирования</th>
                <th>Количество</th>
            </tr>    
            <tr>
                <td><?= $analysis['Название'] ?></td>
                <td><?= $analysis['Дата_бронирования'] ?></td>
                <td><?= $analysis['Время_бронирования'] ?></td>
                <td><?= $analysis['Количество_персон'] ?></td>
            </tr>
            <tr>
                <th>Название</th>
                <th>Цена</th>
                <th colspan="2">Ингредиенты</th>
            </tr>
        <?php
        // Обновляем значение предыдущего Код_бронирования
        $prevBookingId = $analysis['Код_бронирования'];
    }
    ?>
    <tr>
        <td><?= $analysis['Название_блюда'] ?></td>
        <td><?= $analysis['Цена'] ?></td>
        <td colspan="2"><?= $analysis['Ингредиенты'] ?></td>
    </tr>
<?php } ?>
</table>
</div> <!-- закрываем последний "features-box" -->
</div> <!-- закрываем контейнер "features-box" -->



</body>
</html>
