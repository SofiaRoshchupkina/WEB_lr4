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

// Получение случайной капчи из базы данных
$sql = "SELECT URLcap, значение FROM Капчи ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $URLcap = $row["URLcap"];
    $captcha_value = $row["значение"];
} else {
    echo "Ошибка: капчи не найдены в базе данных.";
    exit;
}

$captcha_error = ""; // Переменная для хранения сообщения об ошибке капчи
$fio_error = $phone_error = $email_error = $login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $fio = $_POST['ФИО'];
    $mail = $_POST['Email'];
    $phone = $_POST['Телефон'];
    $login = $_POST['Логин'];
    $password = $_POST['Пароль'];
    $input_captcha = $_POST["captcha"];
    $expected_captcha = $_POST["captcha_value"];

    // Проверка капчи
    if ($input_captcha != $expected_captcha) {
        $captcha_error = "Капча введена неправильно. Пожалуйста, попробуйте еще раз.";
    }

    // Проверка поля ФИО
    if (!preg_match("/^[a-zA-Zа-яА-ЯёЁ\s]+$/u", $fio)) {
        $fio_error = "ФИО должно содержать только буквы и пробелы.";
    }

    // Проверка поля Телефон
    if (!preg_match("/^\+7\d{10}$/", $phone)) {
        $phone_error = "Запишите номер телефона в формате: +7---------";
    }

    // Проверка поля Email
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Неверный формат адреса электронной почты.";
    }

    // Проверка уникальности логина
    $check_login_sql = "SELECT Логин FROM гости WHERE Логин = '$login'";
    $check_login_result = $conn->query($check_login_sql);
    if ($check_login_result->num_rows > 0) {
        $login_error = "Этот логин уже занят. Пожалуйста, выберите другой.";
    }

    // Если есть ошибки, прерываем выполнение и выводим сообщения об ошибках
    if (!empty($captcha_error) || !empty($fio_error) || !empty($phone_error) || !empty($email_error) || !empty($login_error)) {
        // Ошибка есть, выводим форму с сообщениями об ошибках
    } else {
        // Защита от SQL-инъекций
        $fio = mysqli_real_escape_string($conn, $fio);
        $mail = mysqli_real_escape_string($conn, $mail);
        $phone = mysqli_real_escape_string($conn, $phone);
        $login = mysqli_real_escape_string($conn, $login);
        $password = mysqli_real_escape_string($conn, $password);

        // Вставка данных в таблицу гости
        $sql = "INSERT INTO гости (ФИО, Телефон, Email, Логин, Пароль)
                VALUES ('$fio', '$phone', '$mail', '$login', '$password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: start.php");
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
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
        .login-form input[type="tel"],
        .login-form input[type="text"].error {
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
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .error {
            border-color: red;
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
            <input type="text" id="ФИО" name="ФИО" value="<?php echo isset($_POST['ФИО']) ? $_POST['ФИО'] : ''; ?>" required class="<?php echo !empty($fio_error) ? 'error' : ''; ?>">
            <span class="error-message"><?php echo $fio_error; ?></span>

            <label for="phone">Телефон:</label>
            <input type="tel" id="Телефон" name="Телефон" value="<?php echo isset($_POST['Телефон']) ? $_POST['Телефон'] : ''; ?>" required class="<?php echo !empty($phone_error) ? 'error' : ''; ?>">
            <span class="error-message"><?php echo $phone_error; ?></span>

            <label for="mail">Email:</label>
            <input type="text" id="Email" name="Email" value="<?php echo isset($_POST['Email']) ? $_POST['Email'] : ''; ?>" required class="<?php echo !empty($email_error) ? 'error' : ''; ?>">
            <span class="error-message"><?php echo $email_error; ?></span>

            <label for="login">Логин:</label>
            <input type="text" id="Логин" name="Логин" value="<?php echo isset($_POST['Логин']) ? $_POST['Логин'] : ''; ?>" required class="<?php echo !empty($login_error) ? 'error' : ''; ?>">
            <span class="error-message"><?php echo $login_error; ?></span>

            <label for="password">Пароль:</label>
            <input type="password" id="Пароль" name="Пароль" value="<?php echo isset($_POST['Пароль']) ? $_POST['Пароль'] : ''; ?>" required>

            <label for="captcha">Введите код с картинки:</label>
            <img src="<?php echo $URLcap; ?>" alt="Captcha">
            <input type="text" id="captcha" name="captcha" required>
            <span class="error-message"><?php echo $captcha_error; ?></span>

            <input type="hidden" name="captcha_value" value="<?php echo $captcha_value; ?>">

            <input type="submit" value="Зарегистрироваться">
        </form>
        <a href="start.php" class="register-button">Авторизация</a>
    </div>
</div>

</body>
</html>
