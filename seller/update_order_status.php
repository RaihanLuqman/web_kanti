<?php
session_start();
include '../konek_db.php';
include '../includes/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update the order status in the database
    $sql_update = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('si', $new_status, $order_id);

    if ($stmt_update->execute()) {
        header('Location: /Kanti/dashboard_seller.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
