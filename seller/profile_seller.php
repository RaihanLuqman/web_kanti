<?php
session_start();
include '../konek_db.php';
include '../includes/db.php'; // Adjust the path according to your project structure
$current_page = basename($_SERVER['PHP_SELF']);

// Redirect to login if trying to access track_order.php without logging in
if ($current_page == 'track_order.php' && !isset($_SESSION['username'])) {
    header('Location: /Kanti/login.php');
    exit();
}

// Get the user ID, for example, from the session or query string
$user_id = 4; // Example: replace with a dynamic value

// Assume the form action is handled by this PHP script
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the slogan from the form input
    $slogan = $_POST['slogan'];

    // Update the restaurant data in the database
    $stmt_update = $pdo->prepare("UPDATE restaurants SET slogan = :slogan WHERE id_user = :user_id");
    $stmt_update->execute([
        'slogan' => $slogan,
        'user_id' => $user_id
    ]);

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch user data from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user not found
if (!$user) {
    die("User not found.");
}

// Fetch restaurant data from the database
$stmt_restaurant = $pdo->prepare("SELECT * FROM restaurants WHERE id_user = :user_id");
$stmt_restaurant->execute(['user_id' => $user_id]);
$restaurant = $stmt_restaurant->fetch(PDO::FETCH_ASSOC);

// Check for missing data
$missing_data = [];
if (empty($restaurant['nama_warung'])) {
    $missing_data[] = 'Nama Warung';
}
if (empty($restaurant['slogan'])) {
    $missing_data[] = 'Slogan Warung';
}
if (empty($restaurant['alamat_warung'])) {
    $missing_data[] = 'Alamat Warung';
}
if (empty($restaurant['phone_number'])) {
    $missing_data[] = 'Phone Number';
}
if (empty($restaurant['whatsapp_number'])) {
    $missing_data[] = 'WhatsApp Number';
}

?>
<!DOCTYPE html>
<html lang="en">

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
            <span style="margin-left: 50px"> <!-- to shift the logo to the right -->
                <a href="/Kanti/<?= htmlspecialchars($home_page) ?>"><img src="/Kanti/assets/images/Logo_Kanti.png" alt="Kanti Logo" class="logo"></a>
            </span>
            <nav class="tombol-page">
                <a href="/Kanti/user/index_user.php" class="<?= $current_page == 'index_user.php' ? 'active' : '' ?>">Home</a>
                <a href="/Kanti/restaurants.php" class="<?= $current_page == 'restaurants.php' ? 'active' : '' ?>">Restaurants</a>
                <a href="/Kanti/track_order.php" class="<?= $current_page == 'track_order.php' ? 'active' : '' ?>">Track Order</a>
                <a href="/Kanti/user/contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </nav>
            <div class="user-info">
                <a href="profile_user.php" class="user-link">
                    <img src="../assets/images/Akun_icon.svg" alt="User Icon">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </div>
        </div>
    </header>

    <main>
        <!-- Search Section -->
        <section class="search-section">
            <h1>Hello, <?php echo htmlspecialchars($user['username']); ?></h1>
            <div class="search-bar"></div>
        </section>

        <!-- User Signin Section -->
        <section class="user-signin">
            <h2>Profile</h2>
        </section>

        <!-- Profile Section -->
        <section class="profile">
            <div class="profile-container">
                <div class="profile-picture">
                    <img src="/Kanti/assets/images/p.jpeg" alt="Profile Picture">
                    <button id="edit-profile-button" class="edit-profile-button" type="button">Edit Profile</button>
                </div>
                <form>
                    <div class="form-group">
                        <div class="form-item">
                            <label for="username">Username</label>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>

                        <div class="form-row">
                            <div class="form-item">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly>
                            </div>
                            <div class="form-item">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-item">
                                <label for="email">Your Email</label>
                                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="form-item">
                                <label for="phone-number">Phone Number</label>
                                <input type="text" id="phone-number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-item password-container">
                            <label for="password">Password</label>
                            <input type="password" id="password" value="<?php echo htmlspecialchars($user['password']); ?>" readonly>
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                                <img src="/Kanti/assets/images/eye-icon.png" alt="Show Password" id="toggleIcon">
                            </span>
                        </div>

                        <div class="form-item">
                            <label for="address">Your Address</label>
                            <textarea id="address" readonly><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>

                        <div id="form-actions" class="form-actions" style="display: none;">
                            <button type="button" class="cancel-button">Cancel</button>
                            <button type="submit" class="save-button">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Restaurant Section -->
        <?php if ($restaurant) : ?>
            <section class="restaurant">
                <h2>Your Restaurant</h2>
                <div class="restaurant-container">
                    <div class="restaurant-picture">
                        <img src="<?php echo htmlspecialchars($restaurant['image']); ?>" alt="Restaurant Picture">
                        <button id="edit-restaurant-button" class="edit-restaurant-button" type="button">Edit Restaurant</button>
                    </div>
                    <form>
                        <div class="form-group">
                            <div class="form-item">
                                <label for="nama-restaurant">Restaurant Name</label>
                                <input type="text" id="nama-restaurant" value="<?php echo htmlspecialchars($restaurant['nama_warung']); ?>" readonly>
                            </div>

                            <div class="form-item">
                                <label for="slogan-restaurant">Slogan Warung</label>
                                <textarea id="slogan-restaurant" readonly><?php echo htmlspecialchars($restaurant['slogan']); ?></textarea>
                            </div>

                            <div class="form-item">
                                <label for="alamat-restaurant">Restaurant Address</label>
                                <textarea id="alamat-restaurant" readonly><?php echo htmlspecialchars($restaurant['alamat_warung']); ?></textarea>
                            </div>

                            <div class="form-item">
                                <label for="phone-number-restaurant">Phone Number</label>
                                <input type="text" id="phone-number-restaurant" value="<?php echo htmlspecialchars($restaurant['nomor_ponsel']); ?>" readonly>
                            </div>

                            <div class="form-item">
                                <label for="whatsapp-number-restaurant">WhatsApp Number</label>
                                <input type="text" id="whatsapp-number-restaurant" value="<?php echo htmlspecialchars($restaurant['nomor_whatsapp']); ?>" readonly>
                            </div>

                            <div id="form-actions-restaurant" class="form-actions-restaurant" style="display: none;">
                                <button type="button" class="cancel-restaurant-button">Cancel</button>
                                <button type="submit" class="save-restaurant-button">Save</button>
                            </div>
                        </div>
                    </form>
                    <button class="kelola-restaurant-button" onclick="window.location.href='dashboard_seller.php'">Manage Restaurant</button>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Kanti/includes/footer.php';
    ?>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById('password');
            var toggleIcon = document.getElementById('toggleIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.src = '/Kanti/assets/images/eye-off-icon.png'; // Closed eye icon
                toggleIcon.alt = 'Hide Password';
            } else {
                passwordField.type = 'password';
                toggleIcon.src = '/Kanti/assets/images/eye-icon.png'; // Open eye icon
                toggleIcon.alt = 'Show Password';
            }
        }

        document.getElementById('edit-profile-button').addEventListener('click', function() {
            // Enable form fields for editing
            var formFields = document.querySelectorAll('.form-item input, .form-item textarea');
            formFields.forEach(function(field) {
                field.removeAttribute('readonly');
            });

            // Show the save and cancel buttons
            document.getElementById('form-actions').style.display = 'flex';

            // Hide the edit profile button
            document.getElementById('edit-profile-button').style.display = 'none';
        });

        document.querySelector('.cancel-button').addEventListener('click', function() {
            // Reload the page to reset the form
            window.location.reload();
        });

        document.getElementById('edit-restaurant-button').addEventListener('click', function() {
            // Enable form fields for editing
            var formFieldsRestaurant = document.querySelectorAll('.form-item input, .form-item textarea');
            formFieldsRestaurant.forEach(function(field) {
                field.removeAttribute('readonly');
            });

            // Show the save and cancel buttons
            document.getElementById('form-actions-restaurant').style.display = 'flex';

            // Hide the edit restaurant button
            document.getElementById('edit-restaurant-button').style.display = 'none';
        });

        document.querySelector('.cancel-restaurant-button').addEventListener('click', function() {
            // Reload the page to reset the form
            window.location.reload();
        });
    </script>
</body>

</html>