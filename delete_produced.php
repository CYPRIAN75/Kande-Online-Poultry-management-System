<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Delete record from database
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // SQL to delete record from birds_produced table
    $sql = "DELETE FROM birds_produced WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Birds produced entry deleted successfully";
    header('location:birds_produced.php'); // Redirect after deletion
    exit;
} else {
    $_SESSION['error'] = "Invalid request";
    header('location:birds_produced.php'); // Redirect on error
    exit;
}
?>
