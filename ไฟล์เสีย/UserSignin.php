<?php
session_start();
require_once 'config/db.php';

class UserSignin {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
        if (!isset($_SESSION['user_login'])) {
            $_SESSION['error'] = "Please signin to the system";
            header("location:index.php");
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
$user = new UserSignin($conn);
$userData = $user->fetchUserData($_SESSION['user_login']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D&family=Kanit:ital,wght@0,400;1,900&display=swap" rel="stylesheet">
    <link href="./user.css" rel="stylesheet">
    </head>

<body>
    <div class="container">
        <h3 class="mt-4">Welcome <?php echo $userData['firstname'] . ' ' . $userData['lastname'];
                                    $conn = null; ?>. You are User.</h3>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="BMI/index2.php" class="btn btn-primary">Go to BMI Web Application</a>
    </div>
</body>

</html>