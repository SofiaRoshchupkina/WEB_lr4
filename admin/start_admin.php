<?php
session_start();

// Предопределенные данные администраторов
$admins = [
    'admin1' => ['password' => 'password1', 'branch_code' => 1],
    'admin2' => ['password' => 'password2', 'branch_code' => 2],
    'admin3' => ['password' => 'password3', 'branch_code' => 3]
];

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Логин'];
    $password = $_POST['Пароль'];

    // Проверка наличия пользователя в массиве администраторов
    if (isset($admins[$username]) && $admins[$username]['password'] === $password) {
        // Авторизация успешна
        $_SESSION['adminUsername'] = $username;
        $_SESSION['branchCode'] = $admins[$username]['branch_code'];
        header("Location: lk_admin.php");
        exit();
    } else {
        // Авторизация не удалась
        $error = "Неверный логин или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Авторизация Администратора</title>
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
        </div>
      </div>
    </div>
  </div>
</header>

<div class="login-form">
    <div class="container">
        <h2>Авторизация Администратора</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Логин:</label>
            <input type="text" id="Логин" name="Логин" required>
            <label for="password">Пароль:</label>
            <input type="password" id="Пароль" name="Пароль" required>
            <input type="submit" value="Войти">
        </form>
    </div>
</div>

</body>
</html>
