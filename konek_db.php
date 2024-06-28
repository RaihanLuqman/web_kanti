<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kanti";

// Create mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check mysqli connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Create PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname: " . $e->getMessage());
}
?>
