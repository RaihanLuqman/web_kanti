<?php
include '../includes/header.php';
include '../includes/db.php';

$categoryId = $_GET['category_id'] ?? '';

$query = "SELECT f.*, r.name as restaurant_name FROM foods f
          JOIN restaurants r ON f.restaurant_id = r.id
          JOIN food_categories fc ON f.id = fc.food_id
          WHERE fc.category_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$categoryId]);
$foods = $stmt->fetchAll();

echo '<h2>Filtered Results</h2>';
foreach ($foods as $food) {
    echo '<div>' . $food['name'] . ' - ' . $food['restaurant_name'] . ' - ' . $food['price'] . '</div>';
}

include '../includes/footer.php';
?>
