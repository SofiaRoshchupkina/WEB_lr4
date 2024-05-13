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
<title>Редактор меню</title>
<style>
    table {
	width: 100%;
	border-collapse: separate;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #d9b296;
        color:#fff;
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
    // Проверяем, есть ли в сессии данные об администраторе и извлекаем код филиала администратора
    if(isset($_SESSION['user']) && isset($_SESSION['user']['Код_филиала'])) {
        $admin_branch_code = $_SESSION['user']['Код_филиала'];
        
        // Формируем SQL-запрос для извлечения названия филиала
        $sql = "SELECT Название FROM филиалы WHERE Код_филиала = $admin_branch_code";
        
        // Выполняем запрос
        $result = mysqli_query($conn, $sql);
        
        // Проверяем успешность выполнения запроса и получаем название филиала
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $branch_name = $row['Название'];
            ?>

            <div id="features-box">
                <div class="news-item">
                    <h2><center>Редактор меню для ресторана "<?=$branch_name?>"</center></h2>
                </div>
            </div>

            <?php
        }
    }
?>

<table>
    <tr>
        <th>Код блюда</th>
        <th>Название блюда</th>
        <th>Цена</th>
        <th>Ингредиенты</th>
        <th>Категория блюда</th>
        <th>URL фотографии</th>
        <th colspan="2">Действия</th>
    </tr>
    <?php
    // Формируем SQL-запрос для извлечения данных из таблицы "меню" по коду филиала администратора
    $sqlMenu = "SELECT * FROM `меню` WHERE `код_филиала` = $admin_branch_code";
        
    // Выполняем запрос
    $resultMenu = mysqli_query($conn, $sqlMenu);
    
    // Выводим таблицу с данными из таблицы "меню"
    if ($resultMenu && mysqli_num_rows($resultMenu) > 0) {
        ?>
    <?php
    while ($MenuB = mysqli_fetch_array($resultMenu)) {
        ?>
    <tr>
        <td><?= $MenuB['Код_блюда'] ?></td>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $MenuB['Код_блюда'] ?>">
            <td><input type="text" name="name" value="<?= $MenuB['Название_блюда'] ?>"></td>
            <td><input type="text" name="price" value="<?= $MenuB['Цена'] ?>"></td>
            <td><input type="text" name="ingredients" value="<?= $MenuB['Ингредиенты'] ?>"></td>
            <td><input type="text" name="category" value="<?= $MenuB['Категория_блюда'] ?>"></td>
            <td><input type="text" name="URLimage" value="<?= $MenuB['Murl'] ?>"></td>
            <td><input type="submit" name="update" value="Сохранить"></td>
            <td><input type="submit" name="delete" value="Удалить"></td>
        </form>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td>№</td>
        <form method="POST">
            <td><input type="text" name="OptName" placeholder="Название блюда"></td>
            <td><input type="text" name="OptPrice" placeholder="Цена"></td>
            <td><input type="text" name="OptIng" placeholder="Ингредиенты"></td>
            <td><input type="text" name="OptCat" placeholder="Категория блюда"></td>
            <td><input type="text" name="OptURL" placeholder="Ссылка на картинку 300x200"></td>
            <td colspan="2"><input type="submit" name="AddMeal" value="Добавить"></td>
        </form>
    </tr>
</table>

<?php
}
if (isset($_POST["update"])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $url = mysqli_real_escape_string($conn, $_POST['URLimage']);


    $update_query = "UPDATE `меню` SET `Название_блюда` = '$name', `Цена` = '$price', `Ингредиенты` = '$ingredients', `Категория_блюда` = '$category', `Murl` = '$url' WHERE `Код_блюда` = $id";
    if (mysqli_query($conn, $update_query)) {
        echo "Данные успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных: " . mysqli_error($conn);
    }
}
if (isset($_POST["delete"])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    // Формируем SQL-запрос для удаления записи
    $delete_query = "DELETE FROM `меню` WHERE `Код_блюда` = $id";
    
    // Выполняем запрос
    if (mysqli_query($conn, $delete_query)) {
        echo "Запись успешно удалена.";
    } else {
        echo "Ошибка при удалении записи: " . mysqli_error($conn);
    }
} 
if (isset($_POST["AddMeal"])) {
    $name = mysqli_real_escape_string($conn, $_POST['OptName']);
    $price = mysqli_real_escape_string($conn, $_POST['OptPrice']);
    $fil = $_SESSION['user']['Код_филиала'];
    $ing = mysqli_real_escape_string($conn, $_POST['OptIng']);
    $cat = mysqli_real_escape_string($conn, $_POST['OptCat']);
    $url = mysqli_real_escape_string($conn, $_POST['OptURL']);

    $check_query = "SELECT * FROM `меню` WHERE `Название_блюда` = '$name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "В меню уже есть блюдо с таким названием.";
    } else {
        $insert_query = "INSERT INTO `меню` (`Название_блюда`, `Цена`, `Ингредиенты`, `Категория_блюда`, `Murl`, `Код_филиала`) VALUES ('$name', '$price', '$ing', '$cat', '$url', '$fil')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Данные успешно добавлены.";
        } else {
            echo "Ошибка при добавлении данных: " . mysqli_error($conn);
        }
    } 
}


mysqli_close($conn);
?>

</body>
</html>