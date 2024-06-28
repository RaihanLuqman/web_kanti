<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];

    // Periksa apakah username atau email sudah terdaftar
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = "Username atau email sudah terdaftar.";
    } else {
        // Masukkan pengguna baru ke database
        $stmt = $pdo->prepare('INSERT INTO users (username, first_name, last_name, email, phone_number, password, role, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$username, $first_name, $last_name, $email, $phone_number, $password, 'user', $address]);

        // Alihkan ke halaman login setelah berhasil mendaftar
        header("Location: login.php");
        exit();
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
                <a href="/Kanti/<?= htmlspecialchars($home_page) ?>" class="<?= $current_page == $home_page ? 'active' : '' ?>">Home</a>
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
</body>

</html>

<h1 class="basic-text">Halo!, Selamat Datang Di Kanti!</h1>
<main>
    <section class="user-signin">
        <h2>Daftarkan Dirimu!</h2>
        <?php if (isset($error)) : ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="uname-reg">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="fname-reg">
                <input type="text" id="first_name" name="first_name" placeholder="First Name" required><br>
                <input type="text" id="last_name" name="last_name" placeholder="Last Name" required><br>
            </div>
            <div class="fname-reg">
                <input type="email" id="email" name="email" placeholder="Email Address" required><br>
                <input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" required><br>
            </div>
            <div class="pass-reg">
                <input type="password" id="password" name="password" placeholder="Password" required><br>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required><br>
            </div>
            <div class="address-reg">
                <textarea id="address" name="address" placeholder="Your Address" required></textarea><br>
                <button type="submit" value="Register">Register</button>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
    // Validasi kata sandi di sisi klien
    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            event.preventDefault();
            alert('Password dan Konfirmasi Password tidak cocok!');
        }
    });
</script>