<?php 
session_start();
include 'connect.php'; 

if ($_SESSION['user']) {
    $flag = 1;
}

// Проверяем наличие выбранного филиала в сессии
if(isset($_SESSION['selected_branch'])) {
    $selected_branch = $_SESSION['selected_branch'];
}

if(isset( $_SESSION['bookingId'])) {
    $code = $_SESSION['bookingId'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if(isset($_POST['procedures']) && is_array($_POST['procedures'])) {
        $selected_procedures = $_POST['procedures'];

        // Вставка новых выбранных процедур в таблицу
        $insert_query = "INSERT INTO `заказы` (`Код_бронирования`, `Код_блюда`) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);

        foreach ($selected_procedures as $procedure) {
            mysqli_stmt_bind_param($stmt_insert, 'ss', $code, $procedure);
            mysqli_stmt_execute($stmt_insert);
        }

        echo "<p>Блюда добавлены в бронирование.</p>";
    } else {
        echo "<p>Ничего не выбрано.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Корзина с блюдами</title>
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
    // Запрос на получение имени филиала
    $branchNameQuery = "SELECT `название` FROM `филиалы` WHERE `Код_филиала`='$selected_branch'";
    $branchNameResult = mysqli_query($conn, $branchNameQuery);
    $row = mysqli_fetch_assoc($branchNameResult);
    $branchName = $row['название'];
    ?>
<div id="features-box">
    <div class="news-item">
        <h2><center>Меню ресторана <?= $branchName ?></center></h2>
    </div>
</div>

<form method="POST">
<table>
    <tr>
        <th>Выбор</th>
        <th>Название блюда</th>
        <th>Цена</th>
        <th>Ингредиенты</th>
        <th>Категория блюда</th>
        <th>Фотография</th>
    </tr>
    <?php
        // Формируем SQL-запрос для получения данных из таблицы "меню" по выбранному ресторану
        $sqlMenu = "SELECT * FROM `меню` WHERE `Код_филиала` = '$selected_branch'";
        // Получение значения параметра branch из URL
        $resultMenu = mysqli_query($conn, $sqlMenu);
        while ($rowMenu = mysqli_fetch_assoc($resultMenu)) {
            ?>
            <tr>
                <td><input type="checkbox" name="procedures[]" value="<?= $rowMenu['Код_блюда'] ?>"></td>
                <td><?= $rowMenu['Название_блюда'] ?></td>
                <td><?= $rowMenu['Цена'] ?></td>
                <td><?= $rowMenu['Ингредиенты'] ?></td>
                <td><?= $rowMenu['Категория_блюда'] ?></td>
                <td><div class="imgc"><img src="<?= $rowMenu['Murl'] ?>" width="300" height="200"></div></td>
            </tr>
            <?php
        }
    ?>
</table>
    <input type="submit" name="submit" value="Добавить выбранные блюда в бронирование">
</form>

</body>
</html>
