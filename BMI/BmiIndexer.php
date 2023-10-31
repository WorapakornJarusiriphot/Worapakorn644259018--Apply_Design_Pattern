
<?php

include_once 'HumanBeing.php';

class BmiIndexer
{
    // Database connection details
    private $host = 'localhost';
    private $dbname = 'login_test2';
    private $user = 'root';
    private $pass = '';
    private $conn;
    private $human;

    public function __construct() {
        try {
            // Connect to the database using PDO
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function setHuman($human)
    {
        $this->human = $human;
    }

    public function getBmi()
    {
        return $this->human->getBmi();
    }

    public function interpretBMI()
    {
        $bmi = $this->getBmi();

        $interpretations_stmt = $this->conn->prepare("SELECT * FROM bmi_interpretations WHERE :bmi >= bmi_min AND :bmi <= bmi_max LIMIT 1");
        $interpretations_stmt->bindParam(':bmi', $bmi);
        $interpretations_stmt->execute();

        $bmi_data = $interpretations_stmt->fetch(PDO::FETCH_ASSOC);
        if ($bmi_data) {
            return $bmi_data['interpretation'];
        } else {
            return "Unknown interpretation";
        }
    }

    public function interpretimagePathBMI()
    {
        $bmi = $this->getBmi();

        $imagePath_stmt = $this->conn->prepare("SELECT * FROM bmi_interpretations WHERE :bmi >= bmi_min AND :bmi <= bmi_max LIMIT 1");
        $imagePath_stmt->bindParam(':bmi', $bmi);
        $imagePath_stmt->execute();

        $bmi_data = $imagePath_stmt->fetch(PDO::FETCH_ASSOC);
        if ($bmi_data) {
            return $bmi_data['imagePath'];
        } else {
            return "Unknown imagePath";
        }
    }

    public function interpretdetailsBMI()
    {
        $bmi = $this->getBmi();

        $details_stmt = $this->conn->prepare("SELECT * FROM bmi_interpretations WHERE :bmi >= bmi_min AND :bmi <= bmi_max LIMIT 1");
        $details_stmt->bindParam(':bmi', $bmi);
        $details_stmt->execute();

        $bmi_data = $details_stmt->fetch(PDO::FETCH_ASSOC);
        if ($bmi_data) {
            return $bmi_data['details'];
        } else {
            return "Unknown details";
        }
    }
}

// // Initialize the variables
// $bmi = "";
// $interpretation = "";
// $imagePath = "";
// $details = "";