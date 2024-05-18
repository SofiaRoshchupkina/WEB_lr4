<?php
session_start();
include 'connect.php';

if ($_SESSION['user']) {
    $flag = 1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest = $_POST['guest'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $persons = $_POST['persons'];
    $admin_branch_code = $_SESSION['branchCode'];

    $sql = "INSERT INTO бронирования (Код_гостя, Код_филиала, Дата_бронирования, Время_бронирования, Количество_персон) VALUES ('$guest', '$admin_branch_code', '$date', '$time', '$persons')";
    if (mysqli_query($conn, $sql)) {
    $bookingId = mysqli_insert_id($conn);
    } else {
        echo "Ошибка при добавлении бронирования: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    .booking-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px; /* Отступ от header */
    }

    .containerid {
        width: 50%; /* ширина контейнера с формой */
    }

    /* Дополнительные стили для красоты */
    h2 {
        text-align: center;
    }

    form {
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px;
        background-color: #fff;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    .input-field {
        width: 100%; /* одинаковая длина для всех полей ввода */
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box; /* чтобы ширина включала padding и border */
    }

    button[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #a16840;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #794e30
    }
</style>
    <title>Бронирования</title>
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

<section class="booking-section">
    <div class="containerid">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
        <h2>Бронирование столика</h2>
            <label for="guest">Код гостя:</label>
            <select name="guest" id="guest" class="input-field">
                <?php
                $namerest = "SELECT `Код_гостя` FROM `гости`";
                $result = mysqli_query($conn, $namerest);
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<option value='" . $row["Код_гостя"] . "'>" . $row["Код_гостя"] . "</option>";
                }
                ?>
            </select>
        </div>
            <div class="form-group">
                <label for="date">Дата бронирования:</label>
                <input type="date" id="date" name="date" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="time">Время бронирования:</label>
                <select name="time" id="time" class="input-field" required>
                    <?php
                    $start = strtotime('10:00');
                    $end = strtotime('21:00');
                    while ($start <= $end) {
                        echo '<option value="' . date('H:i', $start) . '">' . date('H:i', $start) . '</option>';
                        $start = strtotime('+30 minutes', $start);
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="persons">Количество персон:</label>
                <select name="persons" id="persons" class="input-field" required>
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" href="lk_admin.php">Забронировать</button>
        </form>
    </div>
</section>

</body>
</html>
