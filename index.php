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


<?php $NEWS = mysqli_query($conn, "SELECT * FROM news ORDER BY RAND() LIMIT 2"); 
	$outblog = mysqli_fetch_array($NEWS);
	?>

<div id="features-box">
  <div class="news-item">
    <h2><center>Добро пожаловать в группу ресторанов "WorldTaste"</center></h2>
    <p><center>На данном сайте вы можете найти информацию о наших ресторанах и забронировать столик, чтобы насладиться вкуснейшими блюдами!</center></p>
  </div>
  <div class="news-item">
    <h2 class="features-t"><center>Новости</center></h2>
    <div class="container">
			<div class="features">
				<div class="feature">
					<iframe width="900" height="400" src="<?php echo $outblog[3]?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
					<div class="feature-info">
						<h2 class="feature-info-t"><?php echo $outblog[2]?></h2>
						<p class="feature-info-d"><?php echo $outblog[1]?></p>
					</div>
				</div>

			</div>
		</div>
  </div>
</div>

<div class="footer">
		<div class="container">
			<div class="footer-items">
				<div class="footer-item">
          <p>Работу выполнила студентка группы 1045</p>
          <P>Рощупкина София</p>
        </div>
			  </div>
		  </div>
  </div>

</body>
</html>