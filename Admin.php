<?php
session_start();
require_once 'UserEntity.php';
require_once 'db.php';  // This should define $conn

class Admin implements UserEntity {
    private $conn;
    private $admin_data;

    public function __construct($connection) {
        $this->conn = $connection;
        if (!isset($_SESSION['admin_login'])) {
            $_SESSION['error'] = "Please signin to the system";
            header("location:signin.php");
        }
        $this->fetchData($_SESSION['admin_login']);
    }

    public function fetchData($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $this->admin_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$this->admin_data) {
            throw new Exception("No admin data found for id: $id");
        }
    }

    public function getName() {
        return $this->admin_data['firstname'] . ' ' . $this->admin_data['lastname'];
    }
}

$db = Database::getInstance();
$conn = $db->getConnection();
$admin = new Admin($conn);

$welcomeMessage = "Error: Not logged in.";
try {
    if (!isset($conn) || !($conn instanceof PDO)) {
        throw new Exception('Database connection error.');
    }

    $admin = new Admin($conn);
    $welcomeMessage = "Welcome, " . $admin->getName() . ". You are Administrator.";
} catch (Exception $e) {
    $welcomeMessage = "Error: " . $e->getMessage();
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D&family=Kanit:ital,wght@0,400;1,900&display=swap" rel="stylesheet">
    <link href="./admin.css" rel="stylesheet">
    </head>

<body>
    <div class="container">
        <h3 class="mt-4"><?php echo $welcomeMessage; $conn = null; ?></h3>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="BMI/index.php" class="btn btn-primary">Go to BMI Web Application</a>
    </div>
</body>

</html>