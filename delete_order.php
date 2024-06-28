<?php
session_start();
include 'konek_db.php'; // Include the combined connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];

    // Delete the order from the database
    $sql_delete = "DELETE FROM orders WHERE id = ? AND (status = 'Delivered' OR status = 'Cancelled')";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $order_id);

    if ($stmt_delete->execute()) {
        header('Location: /Kanti/track_order.php');
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
