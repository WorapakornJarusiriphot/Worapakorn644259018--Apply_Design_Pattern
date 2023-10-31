<?php
session_start();
require_once 'config/db.php';

class User {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    public function checkAdminSession() {
        if (!isset($_SESSION['user_login'])) {
            $_SESSION['error'] = "Please signin to the system";
            header("location:/ปี%203%20เทอม%201/สถาปัตยกรรมซอฟต์แวร์/งาน/งานที่%203%20log%20in3/PHP_PDO_MySQL_Bootstrap5_Register_Login_System-main/signin.php");
        }
    }
    
    public function fetchUserData($user_id) {
        $stmt = $this->conn->query("SELECT * FROM users WHERE id=$user_id");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);
$user->checkAdminSession();
$userData = $user->fetchUserData($_SESSION['user_login']);
?>




<!DOCTYPE html>
<html>

<head>
    <title>BMI Calculator</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D&family=Kanit:ital,wght@0,400;1,900&display=swap" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>

<body>
    <?php
    if (isset($_SESSION['user_login'])) {
        $user_id = $_SESSION['user_login'];
        $stmt = $conn->query("SELECT * FROM users WHERE id=$user_id");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href=""><?php echo $row['firstname'] . ' ' . $row['lastname'];
                                            $conn = null; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href=""><?php echo $row['urole']; ?></a>
                    </li>
                </ul>
                <!-- Adding the Logout button here -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../logout.php" class="btn btn-danger">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php

    include_once('BmiIndexer.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $weight = floatval($_POST["weight"]);
        $height = floatval($_POST["height"]);

        $human = new HumanBeing();
        $human->setWeight($weight);
        $human->setHeight($height);
        $human->calculateBmi();

        $bmiIndexer = new BmiIndexer();
        $bmiIndexer->setHuman($human);

        $bmi = $bmiIndexer->getBmi();
        $interpretBMI = $bmiIndexer->interpretBMI();
        $interpretimagePathBMI = $bmiIndexer->interpretimagePathBMI();
        $interpretdetailsBMI = $bmiIndexer->interpretdetailsBMI();

        // แสดงผล
        echo "<div class='container py-5'>";
        echo "<div class='row justify-content-center'>";
        echo "<div class='col-lg-6'>";
        echo "<div class='card shadow bg-dark'>";
        echo "<div class='card-body'>";
        echo "<h2 class='text-center mb-4'>ผลลัพธ์</h2>";
        echo "<p class='text-center'><strong>ค่า BMI ของคุณคือ:</strong> " . $bmi . "</p>";
        echo "<p class='text-center'><strong>" . $interpretBMI . "</strong></p>";
        echo "<div class='text-center'>";
        echo "<img src='" . $interpretimagePathBMI . "' alt='BMI Image' class='img-fluid rounded mx-auto d-block' />";
        echo "</div>";
        echo "<div class='details'>";
        echo "<h3>ข้อแนะนำ:</h3>";
        echo "<ul>";
        echo $interpretdetailsBMI;
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow bg-dark">
                    <div class="card-body">
                        <h1 class="text-center mb-4">BMI Calculator</h1>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="weight">Weight (kg):</label>
                                <input type="text" class="form-control" name="weight" id="weight" required>
                            </div>
                            <div class="form-group">
                                <label for="height">Height (cm):</label>
                                <input type="text" class="form-control" name="height" id="height" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Calculate BMI</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-bottom text-center mb-3">
            <!-- <a href="../logout.php" class="btn btn-danger" style="width: 500px;">Logout</a> -->
        </div>


</body>

</html>