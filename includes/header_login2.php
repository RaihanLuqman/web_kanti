<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);

// Redirect to login if trying to access track_order.php without logging in
if ($current_page == 'track_order.php' && !isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

// Determine the home page based on the user role
$home_page = 'index.php';
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'user') {
        $home_page = 'index_user.php';
    } elseif ($_SESSION['role'] == 'seller') {
        $home_page = 'index_seller.php';
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
                <a href="/Kanti/<?= $home_page ?>"><img src="/Kanti/assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="/Kanti/<?= $home_page ?>" class="<?= $current_page == $home_page ? 'active' : '' ?>">Home</a>
                <a href="/Kanti/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="/Kanti/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="/Kanti/contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="user-info">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="/Kanti/profile_<?php echo $_SESSION['role']; ?>.php" class="user-link">
                        <img src="/Kanti/assets/images/Akun_icon.svg" alt="User Icon">
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                <?php else: ?>
                    <div class="login-register">
                        <a href="/Kanti/login.php" class="tulisan-login" aria-label="Login or Signup">
                            <img src="/Kanti/assets/images/Akun_icon.svg" alt="Login/Register Icon">
                            Login/Signup
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
</body>

</html>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanti</title>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Readex+Pro:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="header-container">
            <span style="margin-left: 50px"> <!-- untuk menggeser logo ke arah kanan -->
                <a href="index_user.php"><img src="<?= $base_url ?>/assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="index_user.php" class="<?= $current_page == 'index_user.php' ? 'active' : '' ?>">Home</a>
                <a href="<?= $base_url ?>/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="<?= $base_url ?>/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="<?= $base_url ?>/contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="user-info">
                <a href="profile_user.php" class="user-link">
                    <img src="<?= $base_url ?>/assets/images/Akun_icon.svg" alt="User Icon">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </div>
        </div>
    </header>
</body>

</html>
