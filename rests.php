<?php 
session_start();
if ($_SESSION['user']) {
    $flag = 1;
}
include 'connect.php'; 
// Получаем $username и $userType из сессии, если они там сохранены
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$userType = isset($_SESSION['userType']) ? $_SESSION['userType'] : '';

// Или получаем их из базы данных, в зависимости от вашей логики

$cacheKey = "user_{$username}_{$userType}";
if (isset($_SESSION[$cacheKey])) {
    $user = $_SESSION[$cacheKey];
} else {
    $_SESSION[$cacheKey] = $user;
    $_SESSION['userType'] = $userType;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Рестораны группы WorldTaste</title>

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


<div class="container">
	<div class="jumbotron-item">
	<div class="features-box">
    <div class="rests-item">
		<h1 class="features-t">Список наших ресторанов</h1>
		<table align="center" class="rest-tables" >
        <?php
        $sqlRest = "SELECT * FROM `филиалы`"; 
        $fil = mysqli_query($conn, $sqlRest);
        ?>
			<tr>
				<th>Название</th>
				<th>Номер телефона</th>
				<th>Адрес</th>
        <th>График работы</th>
			</tr>
			<?php 
             while ($FivR = mysqli_fetch_array($fil)) {?>
         <tr>
             <form method="POST">
             <td><div><?= $FivR['Название'] ?></div></td>
             <td><div><?= $FivR['Телефон'] ?></div></td>
             <td><div><?= $FivR['Адрес'] ?></div></td>
             <td><div><?= $FivR['Время_работы'] ?></div></td>
             </form>
         </tr>
         <?php } ?>
		</table>
        </div>
	</div>
	</div>
	</div>

