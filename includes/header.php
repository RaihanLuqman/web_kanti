<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanti</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Readex+Pro:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="header-container">
            <span style="margin-left: 50px"> <!-- untuk menggeser logo ke arah kanan -->
                <a href="index.php"><img src="assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="index.html" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="login-register">
                <a href="login.php" class="tulisan-login" aria-label="Login or Signup">
                    <img src="assets/images/Akun_icon.svg" alt="Login/Register Icon">
                    Login/Signup
                </a>
            </div>
        </div>
    </header>