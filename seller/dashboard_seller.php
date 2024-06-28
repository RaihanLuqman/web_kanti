<?php
session_start();
include '../konek_db.php';
include '../includes/db.php';

// Check login
if (!isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

// Get the logged-in user's ID
$current_user_id = $_SESSION['user_id'];

// Get the restaurant ID owned by the logged-in user
$sql_restaurant = "SELECT id FROM restaurants WHERE id_user = ?";
$stmt_restaurant = $conn->prepare($sql_restaurant);
$stmt_restaurant->bind_param('i', $current_user_id);
$stmt_restaurant->execute();
$result_restaurant = $stmt_restaurant->get_result();
if ($result_restaurant->num_rows > 0) {
    $row_restaurant = $result_restaurant->fetch_assoc();
    $current_restaurant_id = $row_restaurant['id'];
} else {
    // If the user does not own a restaurant
    echo "Anda tidak memiliki restoran.";
    exit();
}

// Handle the form submission for adding a new menu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu'])) {
    // Get form data
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    
    // Handle file upload
    $image = $_FILES['image']['name'];
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert data into the database
        $sql_add_menu = "INSERT INTO foods (restaurant_id, name, image, stock, price, category, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_add_menu = $conn->prepare($sql_add_menu);
        $stmt_add_menu->bind_param('issdiss', $current_restaurant_id, $name, $image, $stock, $price, $category, $description);
        
        if ($stmt_add_menu->execute()) {
            echo "Menu berhasil ditambahkan.";
        } else {
            echo "Error: " . $stmt_add_menu->error;
        }
    } else {
        echo "Error uploading image.";
    }
}

// Handle the form submission for deleting menu items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_menu'])) {
    if (!empty($_POST['select_menu'])) {
        $menu_ids = $_POST['select_menu'];
        foreach ($menu_ids as $menu_id) {
            $sql_delete_menu = "DELETE FROM foods WHERE id = ?";
            $stmt_delete_menu = $conn->prepare($sql_delete_menu);
            $stmt_delete_menu->bind_param('i', $menu_id);
            $stmt_delete_menu->execute();
        }
        echo "Menu berhasil dihapus.";
    } else {
        echo "Pilih menu yang akan dihapus.";
    }
}

// Handle the form submission for editing a menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_menu'])) {
    $edit_menu_id = $_POST['edit_menu_id'];
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $sql_edit_menu = "UPDATE foods SET name = ?, stock = ?, price = ?, category = ?, description = ? WHERE id = ?";
    
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_file = "../assets/images/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $sql_edit_menu = "UPDATE foods SET name = ?, image = ?, stock = ?, price = ?, category = ?, description = ? WHERE id = ?";
            $stmt_edit_menu = $conn->prepare($sql_edit_menu);
            $stmt_edit_menu->bind_param('sssdssi', $name, $image, $stock, $price, $category, $description, $edit_menu_id);
        } else {
            echo "Error uploading image.";
        }
    } else {
        $stmt_edit_menu = $conn->prepare($sql_edit_menu);
        $stmt_edit_menu->bind_param('ssdssi', $name, $stock, $price, $category, $description, $edit_menu_id);
    }

    if ($stmt_edit_menu->execute()) {
        echo "Menu berhasil diupdate.";
    } else {
        echo "Error: " . $stmt_edit_menu->error;
    }
}

// Fetch order data from the database
$sql_orders = "SELECT id, item_name, quantity, price, status, date FROM orders WHERE restaurant_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param('i', $current_restaurant_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

// Fetch menu data from the database
$sql_menu = "SELECT * FROM foods WHERE restaurant_id = ?";
$stmt_menu = $conn->prepare($sql_menu);
$stmt_menu->bind_param('i', $current_restaurant_id);

// Capture and display errors
if (!$stmt_menu->execute()) {
    echo "Eksekusi gagal: (" . $stmt_menu->errno . ") " . $stmt_menu->error;
}
$result_menu = $stmt_menu->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Kanti</title>
    <link rel="stylesheet" href="/Kanti/assets/css/style.css">
    <script>
        function showAddMenuForm() {
            document.getElementById('add-menu-form').style.display = 'block';
        }

        function hideAddMenuForm() {
            document.getElementById('add-menu-form').style.display = 'none';
        }

        function editMenu(id, name, stock, price, category, description) {
            document.getElementById('edit_menu_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit-menu-form').style.display = 'block';
        }

        function hideEditMenuForm() {
            document.getElementById('edit-menu-form').style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <span style="margin-left: 50px">
                <a href="/Kanti/index_user.php"><img src="/Kanti/assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="/Kanti/index_user.php">Home</a>
                <a href="/Kanti/restaurant.php">Restaurants</a>
                <a href="/Kanti/track_order.php">Track Order</a>
                <a href="/Kanti/contact.php">Contact</a>
                <a href="/Kanti/dashboard_seller.php" class="active">User</a>
            </nav>
            <div class="login-register">
                <a href="logout.php" class="tulisan-login" aria-label="Logout">
                    <img src="assets/images/Akun_icon.svg" alt="Logout Icon">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <h1 class="basic-text">Kelola Warung Mu!</h1>

    <div class="orders-container">
        <h2>Customer Orders</h2>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Your Items</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_orders->num_rows > 0) {
                    while ($row = $result_orders->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['item_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>Rp." . number_format($row['price'], 0, ',', '.') . "</td>
                                <td>
                                    <form method='post' action='update_order_status.php'>
                                        <input type='hidden' name='order_id' value='{$row['id']}'>
                                        <select name='status' class='status-select' onchange='this.form.submit()'>
                                            <option value='Waiting'" . ($row['status'] == 'Waiting' ? ' selected' : '') . ">Waiting</option>
                                            <option value='On The Way'" . ($row['status'] == 'On The Way' ? ' selected' : '') . ">On The Way</option>
                                            <option value='Delivered'" . ($row['status'] == 'Delivered' ? ' selected' : '') . ">Delivered</option>
                                            <option value='Cancelled'" . ($row['status'] == 'Cancelled' ? ' selected' : '') . ">Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{$row['date']}</td>
                                <td><a href='edit_order.php?id={$row['id']}' class='edit-button'>✏️</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <form method="post" action="">
        <div class="menu-container">
            <h2>Daftar Menu</h2>
            <button type="button" class="add-menu-button" onclick="showAddMenuForm()">Add Menu</button>
            <button type="submit" class="delete-menu-button" name="delete_menu">Delete Menu</button>
            <table class="menu-table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Food Image</th>
                        <th>Food Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_menu->num_rows > 0) {
                        while ($row = $result_menu->fetch_assoc()) {
                            echo "<tr>
                                    <td><input type='checkbox' name='select_menu[]' value='{$row['id']}'></td>
                                    <td><img src='/Kanti/assets/images/{$row['image']}' alt='{$row['name']}' class='menu-image'></td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['stock']}</td>
                                    <td>Rp." . number_format($row['price'], 0, ',', '.') . "</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['description']}</td>
                                    <td><button type='button' class='edit-button' onclick=\"editMenu('{$row['id']}', '{$row['name']}', '{$row['stock']}', '{$row['price']}', '{$row['category']}', '{$row['description']}')\">✏️</button></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No menu items found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>

    <div id="add-menu-form" style="display: none;">
        <form method="post" action="" enctype="multipart/form-data">
            <h3>Tambah Menu Baru</h3>
            <div>
                <label for="name">Nama Makanan:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="image">Gambar Makanan:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div>
                <label for="stock">Stok:</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <div>
                <label for="price">Harga:</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div>
                <label for="category">Kategori:</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div>
                <label for="description">Deskripsi:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div>
                <button type="submit" name="add_menu">Tambah Menu</button>
                <button type="button" onclick="hideAddMenuForm()">Batal</button>
            </div>
        </form>
    </div>

    <div id="edit-menu-form" style="display: none;">
        <form method="post" action="" enctype="multipart/form-data">
            <h3>Edit Menu</h3>
            <input type="hidden" id="edit_menu_id" name="edit_menu_id">
            <div>
                <label for="edit_name">Nama Makanan:</label>
                <input type="text" id="edit_name" name="name" required>
            </div>
            <div>
                <label for="edit_image">Gambar Makanan:</label>
                <input type="file" id="edit_image" name="image" accept="image/*">
            </div>
            <div>
                <label for="edit_stock">Stok:</label>
                <input type="number" id="edit_stock" name="stock" required>
            </div>
            <div>
                <label for="edit_price">Harga:</label>
                <input type="number" id="edit_price" name="price" required>
            </div>
            <div>
                <label for="edit_category">Kategori:</label>
                <input type="text" id="edit_category" name="category" required>
            </div>
            <div>
                <label for="edit_description">Deskripsi:</label>
                <textarea id="edit_description" name="description" required></textarea>
            </div>
            <div>
                <button type="submit" name="edit_menu">Edit Menu</button>
                <button type="button" onclick="hideEditMenuForm()">Batal</button>
            </div>
        </form>
    </div>
</body>
</html>
