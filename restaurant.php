<?php
session_start();
include 'konek_db.php';
include 'includes/db.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Redirect to login if trying to access track_order.php without logging in
if ($current_page == 'track_order.php' && !isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

// Fetch restaurant data from the database
$stmt = $pdo->query("SELECT id, nama_warung, nama_asli_pemilik, nomor_whatsapp, nomor_ponsel, alamat_warung, image FROM restaurants");
$restaurants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
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
                <a href="index.php" class="<?= $current_page == "index.php" ? 'active' : '' ?>">Home</a>
                <a href="/Kanti/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="/Kanti/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="/Kanti/contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="login-register">
                <a href="login.php" class="tulisan-login" aria-label="Login or Signup">
                    <img src="assets/images/Akun_icon.svg" alt="Login/Register Icon">
                    Login/Signup
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="search-section">
            <h1 class="basic-text">Males Ke Kantin? Pesan Kanti Aja!</h1>
            <div class="search-bar">
                <form action="user/search.php" method="get">
                    <input type="text" name="search" placeholder="Cari makanan yang kamu mau">
                    <button type="submit">
                        <img src="assets/images/Search_icon.svg" alt="Search" class="search-icon">Search
                    </button>
                </form>
            </div>
        </section>
        <section class="popular-warungs">
            <h2>Warung Paling Populer</h2>
            <div class="warung-list">
                <?php foreach ($restaurants as $restaurant) : ?>
                    <div class="warung-item">
                        <img src="<?php echo htmlspecialchars($restaurant['image']); ?>" alt="Warung Image">
                        <h3><?php echo htmlspecialchars($restaurant['nama_warung']); ?></h3>
                        <p>Nama Pemilik: <?php echo htmlspecialchars($restaurant['nama_asli_pemilik']); ?></p>
                        <p>Nomor WhatsApp: <?php echo htmlspecialchars($restaurant['nomor_whatsapp']); ?></p>
                        <p>Nomor Ponsel: <?php echo htmlspecialchars($restaurant['nomor_ponsel']); ?></p>
                        <p>Alamat: <?php echo htmlspecialchars($restaurant['alamat_warung']); ?></p>
                        <a href="lihat_menu.php?warung_id=<?php echo $restaurant['id']; ?>" class="menu-button">Lihat Menu</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>

</html>