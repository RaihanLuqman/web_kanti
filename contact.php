<?php
session_start();
include 'konek_db.php';

// Handle contact form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = "kanti@gmail.com";
    $subject = "Contact Form Submission from " . $name;
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Failed to send the message. Please try again.";
    }
}

// Determine the current page
$current_page = basename($_SERVER['PHP_SELF']);

// Redirect to login if trying to access track_order.php without logging in
if ($current_page == 'track_order.php' && !isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

// Determine the home page based on the login status
$home_page = 'index.php';
if (isset($_SESSION['username'])) {
    $home_page = 'index_user.php';
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

<h1 class="basic-text">Need Something? Contact Us!</h1>

<main>
    <section class="contact-section">
        <h2>GET IN TOUCH</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h2>Our Contact</h2>
                <a href="https://wa.me/6282113472156" class="contact-item" target="_blank">
                    <img src="assets/images/wa_icon.png" alt="WhatsApp Icon">
                    <p>082113472156</p>
                </a>
                <a href="https://www.instagram.com/KANTI.ITI" class="contact-item" target="_blank">
                    <img src="assets/images/instagram_icon.png" alt="Instagram Icon">
                    <p>KANTI.ITI</p>
                </a>
                <a href="https://twitter.com/KANTI_ITI" class="contact-item" target="_blank">
                    <img src="assets/images/twitter_icon.png" alt="Twitter Icon">
                    <p>KANTI.ITI</p>
                </a>
                <div class="contact-location">
                    <h3>Location</h3>
                    <p1>777 Casino Ave,<br>Thackerville, OK 73459,<br>United States</p1>
                </div>
            </div>
            <div class="contact-form">
                <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form action="contact.php" method="post">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" placeholder="Your Message" required></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>
</main>
<?php include 'includes/footer.php'; ?>
