<?php 
session_start();

if (isset($_SESSION['user'])) {
    $flag = 1;
} else {
    $flag = 0;
}

include 'connect.php'; 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Включаем строгий режим отчета ошибок

// Получаем данные администратора из сессии
if(isset($_SESSION['adminUsername']) && isset($_SESSION['branchCode'])) {
    $branchCode = $_SESSION['branchCode'];
    
    // Формируем SQL-запрос для извлечения названия филиала
    $sql = "SELECT Название FROM филиалы WHERE Код_филиала = $branchCode";
    
    // Выполняем запрос
    $result = mysqli_query($conn, $sql);
    
    // Проверяем успешность выполнения запроса и получаем название филиала
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $branch_name = $row['Название'];
    }
}

// Обработка удаления
if (isset($_POST["delete"])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $delete_query = "DELETE FROM `меню` WHERE `Код_блюда` = $id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<p>Запись успешно удалена.</p>";
    } else {
        echo "<p>Ошибка при удалении записи: " . mysqli_error($conn) . "</p>";
    }
}

// Обработка обновления данных
if (isset($_POST["save"])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $url = mysqli_real_escape_string($conn, $_POST['URLimage']);

    $update_query = "UPDATE `меню` SET `Название_блюда` = '$name', `Цена` = '$price', `Ингредиенты` = '$ingredients', `Категория_блюда` = '$category', `Murl` = '$url' WHERE `Код_блюда` = $id";
    if (mysqli_query($conn, $update_query)) {
        echo "<p>Данные успешно обновлены.</p>";
    } else {
        echo "<p>Ошибка при обновлении данных: " . mysqli_error($conn) . "</p>";
    }
}

// Переменная для проверки, нужно ли обновлять запись
$update_id = isset($_POST['update']) ? $_POST['id'] : null;

// Обработка добавления нового блюда
if (isset($_POST["AddMeal"])) {
    $name = mysqli_real_escape_string($conn, $_POST['OptName']);
    $price = mysqli_real_escape_string($conn, $_POST['OptPrice']);
    $ing = mysqli_real_escape_string($conn, $_POST['OptIng']);
    $cat = mysqli_real_escape_string($conn, $_POST['OptCat']);
    $url = mysqli_real_escape_string($conn, $_POST['OptURL']);
    $fil = $_SESSION['branchCode'];

    $check_query = "SELECT * FROM `меню` WHERE `Название_блюда` = '$name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<p>В меню уже есть блюдо с таким названием.</p>";
    } else {
        $insert_query = "INSERT INTO `меню` (`Название_блюда`, `Цена`, `Ингредиенты`, `Категория_блюда`, `Murl`, `Код_филиала`) VALUES ('$name', '$price', '$ing', '$cat', '$url', '$fil')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<p>Данные успешно добавлены.</p>";
        } else {
            echo "<p>Ошибка при добавлении данных: " . mysqli_error($conn) . "</p>";
        }
    } 
}
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
    .bron-table b {
        color: #a16840;
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

<?php if (isset($branch_name)): ?>
<div id="features-box">
    <div class="news-item">
        <h2><center>Редактор меню для ресторана "<?=$branch_name?>"</center></h2>
    </div>
    
    <table class="bron-table">
        <form method="POST">
            <td>
            <b>Поиск по названию блюда: </b>
            <input type="text" name="meal_name" value="<?php if(isset($_POST['meal_name'])) echo $_POST['meal_name']; ?>">
            <input type="submit" name="search_meal" value="Найти">
            </td>
        </form>
    </table>
</div>
<?php endif; ?>

<table>
    <tr>
        <th>Название блюда</th>
        <th>Цена</th>
        <th>Ингредиенты</th>
        <th>Категория блюда</th>
        <th>URL фотографии</th>
        <th colspan="2">Действия</th>
    </tr>
    <?php
    // Логика поиска по названию блюда
    $search_condition = '';
    if (isset($_POST['search_meal']) && !empty($_POST['meal_name'])) {
        $meal_name = mysqli_real_escape_string($conn, $_POST['meal_name']);
        $search_condition = "AND `Название_блюда` LIKE '%$meal_name%'";
    }

    // Формируем SQL-запрос для извлечения данных из таблицы "меню" по коду филиала администратора
    $sqlMenu = "SELECT * FROM `меню` WHERE `код_филиала` = $branchCode $search_condition";
        
    // Выполняем запрос
    $resultMenu = mysqli_query($conn, $sqlMenu);
    
    // Выводим таблицу с данными из таблицы "меню"
    if ($resultMenu && mysqli_num_rows($resultMenu) > 0):
        while ($MenuB = mysqli_fetch_array($resultMenu)): ?>
        <tr>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $MenuB['Код_блюда'] ?>">
                <?php if ($update_id == $MenuB['Код_блюда']): ?>
                    <td><input type="text" name="name" value="<?= $MenuB['Название_блюда'] ?>"></td>
                    <td><input type="text" name="price" value="<?= $MenuB['Цена'] ?>"></td>
                    <td><input type="text" name="ingredients" value="<?= $MenuB['Ингредиенты'] ?>"></td>
                    <td><input type="text" name="category" value="<?= $MenuB['Категория_блюда'] ?>"></td>
                    <td><input type="text" name="URLimage" value="<?= $MenuB['Murl'] ?>"></td>
                    <td><input type="submit" name="save" value="Сохранить"></td>
                <?php else: ?>
                    <td><div><?= $MenuB['Название_блюда'] ?></div></td>
                    <td><div><?= $MenuB['Цена'] ?></div></td>
                    <td><div><?= $MenuB['Ингредиенты'] ?></div></td>
                    <td><div><?= $MenuB['Категория_блюда'] ?></div></td>
                    <td><div class="imgc"><img src="<?= $MenuB['Murl'] ?>" width="300" height="200"></div></td>
                    <td><input type="submit" name="update" value="Обновить"></td>
                <?php endif; ?>
                <td><input type="submit" name="delete" value="Удалить"></td>
            </form>
        </tr>
    <?php endwhile; ?>
    <tr>
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
<?php else: ?>
    <p>Меню пустое.</p>
<?php endif; ?>

<?php mysqli_close($conn); ?>

</body>
</html>
