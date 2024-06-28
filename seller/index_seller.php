<?php
session_start();
include '../konek_db.php';
include '../includes/db.php'; // Ubah sesuai dengan lokasi file yang benar
$current_page = basename($_SERVER['PHP_SELF']);

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
                <a href="profile_seller.php" class="user-link">
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
        <h1 class="basic-text">Males Ke Kantin? Pesan Kanti Aja!</h1>
        <div class="search-bar">
            <form action="user/search.php" method="get">
                <input type="text" name="search" placeholder="Cari makanan yang kamu mau">
                <button type="submit">
                    <img src="../assets/images/Search_icon.svg" alt="Search" class="search-icon">Search
                </button>
            </form>
        </div>
    </section>
    <section class="categories">
        <h2>Lagi pengen makan apa?</h2>
        <div class="category-list">
            <div class="category-item">
                <a href="user/filter.php?category_id=1">
                    <img src="../assets/images/category/Ayam_bebek.jpg" alt="Ayam & Bebek">
                    <p>Ayam & Bebek</p>
                </a>
            </div>
            <div class="category-item">
                <a href="user/filter.php?category_id=2">
                    <img src="../assets/images/category/Minuman.jpg" alt="Minuman">
                    <p>Minuman</p>
                </a>
            </div>
            <div class="category-item">
                <a href="user/filter.php?category_id=3">
                    <img src="../assets/images/category/Aneka_mie.jpg" alt="Aneka Mie">
                    <p>Aneka Mie</p>
                </a>
            </div>
            <div class="category-item">
                <a href="user/filter.php?category_id=4">
                    <img src="../assets/images/category/Aneka_nasi.jpg" alt="Aneka Nasi">
                    <p>Aneka Nasi</p>
                </a>
            </div>
            <div class="category-item">
                <a href="user/filter.php?category_id=5">
                    <img src="../assets/images/category/Aneka_sayuran.jpg" alt="Aneka Sayuran">
                    <p>Aneka Sayuran</p>
                </a>
            </div>
            <div class="category-item">
                <a href="user/filter.php?category_id=6">
                    <img src="../assets/images/category/Western_food.jpg" alt="Western Food">
                    <p>Western Food</p>
                </a>
            </div>
            <div class="category-item">
                <a href="user/filter.php?category_id=7">
                    <img src="../assets/images/category/Bakso_mieayam.jpg" alt="Bakso & Mie Ayam">
                    <p>Bakso & Mie Ayam</p>
                </a>
            </div>
        </div>
    </section>
    <!DOCTYPE html>

    <a href="/Kanti/logout.php" class="logout-button">Logout</a>

    <h2>Makanan Paling Populer</h2>
    <div class="row">
        <?php
        // Mengambil data makanan populer dari database
        $stmt = $pdo->query("SELECT * FROM foods WHERE popular = 1 LIMIT 6");
        while ($row = $stmt->fetch()) {
            echo '
        <div class="col-xs-12 col-sm-6 col-md-4 food-item">
            <div class="food-item-wrap">
                <div class="figure-wrap bg-image" style="background-image: url(\'admin/Res_img/foods/' . $row['image'] . '\');"></div>
                <div class="content">
                    <h5><a href="dishes.php?food_id=' . $row['id'] . '">' . $row['name'] . '</a></h5>
                    <div class="product-name">' . $row['description'] . '</div>
                    <div class="price-btn-block">
                        <span class="price">$' . $row['price'] . '</span>
                        <a href="order.php?food_id=' . $row['id'] . '" class="btn theme-btn-dash pull-right">Order Now</a>
                    </div>
                </div>
            </div>
        </div>';
        }
        ?>
    </div>
</main>
<?php include '../includes/footer.php'; // Ubah sesuai dengan lokasi file yang benar 
?>