<?php
session_start();
include '../konek_db.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Redirect to login if trying to access track_order.php without logging in
if ($current_page == 'track_order.php' && !isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

$restaurant_id = $_GET['restaurant_id'];

// Fetch menu data from the database based on restaurant_id
$stmt = $pdo->prepare("SELECT name, price, photo FROM menus WHERE restaurant_id = ?");
$stmt->execute([$restaurant_id]);
$menus = $stmt->fetchAll();

// Fetch restaurant information
$stmt = $pdo->prepare("SELECT nama_warung, nama_asli_pemilik, nomor_whatsapp, nomor_ponsel, alamat_warung, image, slogan FROM restaurants WHERE id = ?");
$stmt->execute([$restaurant_id]);
$restaurant = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Warung</title>
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
                <a href="/Kanti/user/index_user.php" class="<?= $current_page == $home_page ? 'active' : '' ?>">Home</a>
                <a href="/Kanti/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="/Kanti/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="/Kanti/contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="user-info">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="profile_user.php" class="user-link">
                        <img src="../assets/images/Akun_icon.svg" alt="User Icon">
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <section class="warung-info">
            <img src="<?php echo htmlspecialchars($restaurant['image']); ?>" alt="Warung Image">
            <h2><?php echo htmlspecialchars($restaurant['nama_warung']); ?></h2>
            <p>Slogan: <?php echo htmlspecialchars($restaurant['slogan']); ?></p>
            <p>Nama Pemilik: <?php echo htmlspecialchars($restaurant['nama_asli_pemilik']); ?></p>
            <p>Nomor WhatsApp: <?php echo htmlspecialchars($restaurant['nomor_whatsapp']); ?></p>
            <p>Nomor Ponsel: <?php echo htmlspecialchars($restaurant['nomor_ponsel']); ?></p>
            <p>Alamat: <?php echo htmlspecialchars($restaurant['alamat_warung']); ?></p>
        </section>
        <section class="menu-items">
            <h3>Menu</h3>
            <div class="menu-list">
                <?php foreach ($menus as $menu): ?>
                    <div class="menu-item">
                        <img src="<?php echo htmlspecialchars($menu['photo']); ?>" alt="Menu Image">
                        <p><?php echo htmlspecialchars($menu['name']); ?></p>
                        <p>Price: <?php echo htmlspecialchars($menu['price']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
