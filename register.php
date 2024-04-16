<?php
// Подключение к базе данных
$server= "localhost";
$user="root";
$password="";
$DB="restaurant";
$conn = mysqli_connect($server, $user, $password, $DB)
OR die ("Невозможно соединиться с mysql-сервером. Выполнение программы остановлено");
	mysqli_query($conn, "SET NAMES utf8");

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $fio = $_POST['ФИО'];
    $mail = $_POST['Email'];
    $phone = $_POST['Телефон'];
    $login = $_POST['Логин'];
    $password = $_POST['Пароль'];

    // Защита от SQL-инъекций
    $fio = mysqli_real_escape_string($conn, $fio);
    $mail = mysqli_real_escape_string($conn, $mail);
    $phone = mysqli_real_escape_string($conn, $phone);
    $login = mysqli_real_escape_string($conn, $login);
    $password = mysqli_real_escape_string($conn, $password);

    // Вставка данных в таблицу пациенты
    $sql = "INSERT INTO гости (ФИО, Телефон, Email, Логин, Пароль)
            VALUES ( '$fio', '$phone', '$mail', '$login', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Гость успешно зарегистрирован.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Регистрация Гостя</title>
<style>
        .login-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-form label {
            display: block;
            margin-bottom: 5px;
        }
        .login-form input[type="text"],
        .login-form input[type="password"],
        .login-form input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
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

<div class="container">
    <div class="login-form">
        <h2>Регистрация Гостя</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="fio">ФИО:</label>
            <input type="text" id="ФИО" name="ФИО" required>

            <label for="phone">Телефон:</label>
            <input type="tel" id="Телефон" name="Телефон" required>

            <label for="mail">Email:</label>
            <input type="text" id="Email" name="Email" required>

            <label for="login">Логин:</label>
            <input type="text" id="Логин" name="Логин" required>

            <label for="password">Пароль:</label>
            <input type="password" id="Пароль" name="Пароль" required>

            <input type="submit" value="Зарегистрироваться">
        </form>
        <a href="start.php" class="register-button">Авторизация</a>
    </div>
</div>

</body>
</html>
