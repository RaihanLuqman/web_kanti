<?php
include '../includes/header.php';
include '../includes/db.php';

$searchTerm = $_GET['search'] ?? '';

$query = "SELECT f.*, r.name as restaurant_name FROM foods f
          JOIN restaurants r ON f.restaurant_id = r.id
          WHERE f.name LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute(['%' . $searchTerm . '%']);
$foods = $stmt->fetchAll();

echo '<h2>Search Results</h2>';
foreach ($foods as $food) {
    echo '<div>' . $food['name'] . ' - ' . $food['restaurant_name'] . ' - ' . $food['price'] . '</div>';
}

include '../includes/footer.php';
?>
