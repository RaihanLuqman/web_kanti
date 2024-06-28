<?php
session_start();
include '../konek_db.php';
include '../includes/db.php'; // Ubah sesuai dengan lokasi file yang benar
$current_page = basename($_SERVER['PHP_SELF']);

// Asumsikan Anda mendapatkan id_user dari sesi login
$id_user = $_SESSION['user_id']; // Pastikan user_id sudah di set di sesi login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_warung = $_POST['nama_warung'];
    $nama_asli_pemilik = $_POST['nama_pemilik'];
    $nomor_whatsapp = $_POST['whatsapp_number'];
    $nomor_ponsel = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $alamat_warung = $_POST['address'];

    // Pastikan id_user tidak null dan ada di tabel users
    if (!is_null($id_user)) {
        // Perbaiki query SQL sesuai dengan nama kolom dalam database
        $stmt = $pdo->prepare('INSERT INTO restaurants (nama_warung, nama_asli_pemilik, nomor_whatsapp, nomor_ponsel, password, alamat_warung, id_user) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nama_warung, $nama_asli_pemilik, $nomor_whatsapp, $nomor_ponsel, $password, $alamat_warung, $id_user]);

        // Update role user menjadi seller
        $update_role_stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        $update_role_stmt->execute(['seller', $id_user]);

        // Redirect ke profile_seller.php
        header('Location: ./seller/profile_seller.php');
        exit;
    } else {
        echo "Error: Invalid user ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanti</title>
    <link rel="stylesheet" href="/Kanti/assets/css/style2.css">
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
                    <img src="../assets/images/Akun_icon.svg" alt="User Icon">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </div>
        </div>
    </header>
</body>

</html>

<main>
    <section class="search-section">
        <h1>Halo!, Selamat Datang Di Kanti!</h1>
        <div class="search-bar">
        </div>
    </section>

    <section class="user-signin">
        <h2>Daftarkan Warungmu!</h2>
        <div class="uname-reg">
            <form action="reg_warung.php" method="post">
                <input type="text" id="username" name="nama_warung" placeholder="Nama Warung" required>
                <input type="text" id="username" name="nama_pemilik" placeholder="Nama Pemilik" required>
        </div>
        <div class="fname-reg">
            <input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" required><br>
            <input type="text" id="whatsapp_number" name="whatsapp_number" placeholder="Whatsapp Number" required><br>
        </div>

        <div class="pass-reg">
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required><br>
        </div>

        <div class="address-reg">
            <textarea id="address" name="address" placeholder="Alamat Warung" required></textarea><br>
            <!-- Hidden field to store user ID -->
            <input type="hidden" id="id_user" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">
            <button type="submit">Daftarkan Sekarang</button>
        </div>
            </form>
    </section>
</main>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Kanti/includes/footer.php';
?>
