<?php
session_start();

// Проверка, была ли отправлена форма
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $username = $_POST['Логин'];
    $password = $_POST['Пароль'];

    // Проверка, был ли выбран тип пользователя
    if (empty($userType)) {
        $error = "Выберите тип пользователя";
    } else {
        // Выбор таблицы в зависимости от типа пользователя
        $table = ($userType == 'гости') ? 'гости' : 'администраторы';
        $redirectPage = ($userType == 'гости') ? 'lk_guest.php' : 'lk_admin.php';

        // Проверка, есть ли кэшированные данные для пользователя
        $cacheKey = "user_{$username}_{$userType}";
        if (isset($_SESSION[$cacheKey])) {
            $user = $_SESSION[$cacheKey];
            $ID = $_SESSION["{$cacheKey}_id"];
        } else {
            // Создание соединения
            $conn = mysqli_connect("localhost", "root", "", "restaurant");
            if (!$conn) {
                die("Error connect to database!");
            }
            // Подготовка запроса
            $stmt = mysqli_prepare($conn, "SELECT * FROM $table WHERE `Логин` = ? AND `Пароль` = ?");
            mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($userType == 'гости') {
                $stmt1 = mysqli_prepare($conn, "SELECT `Код_гостя` FROM $table WHERE `Логин` = ? AND `Пароль` = ?");
              } else if ($userType == 'администраторы') {
                  $stmt1 = mysqli_prepare($conn, "SELECT `Код_администратора` FROM $table WHERE `Логин` = ? AND `Пароль` = ?");
            }          
            mysqli_stmt_bind_param($stmt1, 'ss', $username, $password);
            mysqli_stmt_execute($stmt1);
            $resultID = mysqli_stmt_get_result($stmt1);
            if ($result && $resultID && mysqli_num_rows($result) > 0) {
                // Получение данных пользователя
                $user = mysqli_fetch_assoc($result);
                $ID = mysqli_fetch_assoc($resultID);
                // Кэширование данных пользователя в сессии
                $_SESSION[$cacheKey] = $user;
                $_SESSION["{$cacheKey}_id"] = $ID;
            } else {
                // Авторизация не удалась
                $error = "Неверный логин или пароль.";
            }

            mysqli_stmt_close($stmt);
            mysqli_stmt_close($stmt1);
            mysqli_close($conn);
        }

        if (!empty($user)) {
            $_SESSION['user'] = $user;
            $_SESSION['userID'] = $ID;
            $_SESSION['userType'] = $userType;
            $_SESSION['userName'] = $username; // Установка имени пользователя
            $flag = 1; // Предполагаем, что пользователь успешно авторизован
            header("Location: $redirectPage");
            exit();
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
<title>Авторизация</title>
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

    
<div class="login-form">
    <div class="container">
        <h2>Авторизация</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-type-switch">
                <input type="radio" id="гости" name="userType" value="гости" checked>
                <label for="гости">Гость</label>
                <input type="radio" id="администраторы" name="userType" value="администраторы">
                <label for="администраторы">Администратор</label>
            </div>
            <label for="username">Логин:</label>
            <input type="text" id="Логин" name="Логин">
            <label for="password">Пароль:</label>
            <input type="password" id="Пароль" name="Пароль">
            <input type="submit" value="Войти">
        </form>
    </div>
</div>

</body>
</html>
