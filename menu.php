<?php 
session_start();
if ($_SESSION['user']) {
    $flag = 1;
}
include 'connect.php'; 
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

<table>
    <tr>
        <td>
            <form method="POST">
            <b>Выберите ресторан </b>
                <select name="restaurant">
                    <option value="">Все рестораны</option>
                    <?php 
                    // Получаем список всех ресторанов из базы данных
                    $namerest = "SELECT `название` FROM `филиалы`";
                    $result = mysqli_query($conn, $namerest);
                    while ($row = mysqli_fetch_assoc($result)){
                        echo "<option value='" . $row["название"] . "'>" . $row["название"] . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="NameRest" value="Выбрать">
            </form>
        </td>
    </tr>
</table>

<div id="features-box">
    <div class="news-item">
        <h2><center>Меню ресторана <?= isset($_POST['restaurant']) ? $_POST['restaurant'] : 'Все рестораны' ?></center></h2>
    </div>
</div>

<table>
    <tr>
        <th>Название блюда</th>
        <th>Цена</th>
        <th>Ингредиенты</th>
        <th>Категория блюда</th>
        <th>Фотография</th>
    </tr>
    <?php
    // Формируем SQL-запрос для получения данных из таблицы "меню"
    $sqlMenu = "SELECT * FROM `меню`";
    
    // Если выбран конкретный ресторан, добавляем условие в SQL-запрос
    if(isset($_POST['restaurant']) && !empty($_POST['restaurant'])) {
        $selectedRestaurant = $_POST['restaurant'];
        $sqlMenu .= " WHERE `Код_филиала` IN (SELECT `Код_филиала` FROM `филиалы` WHERE `название` = '$selectedRestaurant')";
    }
    
    $as = mysqli_query($conn, $sqlMenu);
    ?>
    <?php
    while ($MenuB = mysqli_fetch_array($as)) {?>
    <tr>
        <form method="POST">
        <td><div><?= $MenuB['Название_блюда'] ?></div></td>
        <td><div><?= $MenuB['Цена'] ?></div></td>
        <td><div><?= $MenuB['Ингредиенты'] ?></div></td>
        <td><div><?= $MenuB['Категория_блюда'] ?></div></td>
        <td><div class="imgc"><img src="<?= $MenuB['Murl'] ?>" width="300" height="200"></div></td>
        </form>
    </tr>
    <?php } ?>
</table>

</body>
</html>
