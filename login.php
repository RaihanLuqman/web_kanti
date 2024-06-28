<?php
session_start();
include 'konek_db.php'; // Pastikan file koneksi database di-include dengan benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Prepare statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Password benar, mulai sesi baru
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect berdasarkan role
            if ($role == 'seller') {
                header("Location: /Kanti/seller/index_seller.php");
            } else {
                header("Location: /Kanti/user/index_user.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Akun anda tidak ditemukan! Mungkin anda belum registrasi.";
    }

    $stmt->close();
}

$conn->close();
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
                <a href="/Kanti/index.php"><img src="/Kanti/assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="/Kanti/index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="/Kanti/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="/Kanti/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="/Kanti/contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="login-register">
                <a href="login.php" class="tulisan-login" aria-label="Login or Signup">
                    <img src="/Kanti/assets/images/Akun_icon.svg" alt="Login/Register Icon">
                    Login/Signup
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="user-signin">
            <h2>Login ke Akunmu!</h2>
            <div class="login-form">
                <?php if (isset($error)) {
                    echo "<p style='color:red;'>$error</p>";
                } ?>
                <form action="" method="POST">
                    <input type="text" name="username_or_email" placeholder="Username Or Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <p>Belum Punya Akun? <a href="register.php">Registrasi</a></p>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>
