<?php
session_start();
include 'konek_db.php'; // Include the combined connection file
$current_page = basename($_SERVER['PHP_SELF']);

// Check login
if (!isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

// Get the ID of the currently logged-in user
$current_user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
< lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanti</title>
    <link rel="stylesheet" href="/Kanti/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Readex+Pro:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="header-container">
            <span style="margin-left: 50px"> <!-- untuk menggeser logo ke arah kanan -->
                <a href="/Kanti/<?= htmlspecialchars($home_page) ?>"><img src="/Kanti/assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="/Kanti/user/index_user.php" class="<?= $current_page == 'index_user.php' ? 'active' : '' ?>">Home</a>
                <a href="/Kanti/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="/Kanti/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="/Kanti/user/contact.php" class="<?= $current_page == '/Kanti/user/contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="user-info">
                <a href="profile_user.php" class="user-link">
                    <img src="assets/images/Akun_icon.svg" alt="User Icon">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </div>
        </div>
    </header>
</body>

<h1 class="basic-text">Udah Nyampe Mana Nih Makananmu?</h1>

<div class="order-container">
    <h2>Pesananmu</h2>
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
            // Mengambil data pesanan dari database menggunakan koneksi mysqli
            $sql = "SELECT id, item_name, quantity, price, status, date FROM orders WHERE user_id = $current_user_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $deleteButton = "";
                    if ($row['status'] == 'Delivered' || $row['status'] == 'Cancelled') {
                        $deleteButton = "<form method='post' action='delete_order.php'>
                                            <input type='hidden' name='order_id' value='{$row['id']}'>
                                            <button type='submit' class='delete-button'>🗑️</button>
                                         </form>";
                    }
                    echo "<tr>
                            <td>{$row['item_name']}</td>
                            <td>{$row['quantity']}</td>
                            <td>Rp." . number_format($row['price'], 0, ',', '.') . "</td>
                            <td><span class='status " . strtolower(str_replace(' ', '-', $row['status'])) . "'>{$row['status']}</span></td>
                            <td>{$row['date']}</td>
                            <td>$deleteButton</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>

</html>