<?php
include '../includes/header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $categories = $_POST['categories']; // Array of selected category IDs
    $restaurant_id = $_SESSION['restaurant_id']; // Assuming restaurant_id is stored in session

    // Image upload handling (you should add more validation and security checks)
    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);

    // Insert food into the foods table
    $stmt = $pdo->prepare('INSERT INTO foods (restaurant_id, name, description, price, image, popular) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$restaurant_id, $name, $description, $price, $image, false]);
    $food_id = $pdo->lastInsertId();

    // Insert into food_categories table
    foreach ($categories as $category_id) {
        $stmt = $pdo->prepare('INSERT INTO food_categories (food_id, category_id) VALUES (?, ?)');
        $stmt->execute([$food_id, $category_id]);
    }

    echo "Food uploaded successfully!";
}
?>

<h2>Upload Food</h2>
<form action="upload_food.php" method="post" enctype="multipart/form-data">
    <label for="name">Food Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required><br>

    <label for="image">Image:</label>
    <input type="file" id="image" name="image" required><br>

    <label for="categories">Categories:</label><br>
    <?php
    // Fetch categories from the database
    $stmt = $pdo->query('SELECT * FROM categories');
    while ($row = $stmt->fetch()) {
        echo '<input type="checkbox" name="categories[]" value="' . $row['id'] . '"> ' . $row['name'] . '<br>';
    }
    ?><br>

    <input type="submit" value="Upload">
</form>
