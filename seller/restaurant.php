<?php
session_start();
include '../includes/db.php';

// Ambil data restoran dari database
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
</head>
<body>
    <main>
        <section class="popular-warungs">
            <h2>Warung Paling Populer</h2>
            <div class="warung-list">
                <?php foreach ($restaurants as $restaurant): ?>
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
