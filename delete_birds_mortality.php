<?php
// Include necessary files (dbconnection.php, session start, etc.)
include('includes/dbconnection.php');
session_start();

// Check if user is logged in and authenticated as admin
if (!isset($_SESSION['odmsaid'])) {
    header('Location: login.php');
    exit;
}

// Authorization check: Ensure user is admin
$aid = $_SESSION['odmsaid'];
$sqlCheckAdmin = "SELECT * FROM tbladmin WHERE ID = :aid AND AdminName = 'Admin'";
$queryCheckAdmin = $dbh->prepare($sqlCheckAdmin);
$queryCheckAdmin->bindParam(':aid', $aid, PDO::PARAM_STR);
$queryCheckAdmin->execute();
if ($queryCheckAdmin->rowCount() <= 0) {
    // Redirect to unauthorized page or show a message
    echo "<script>alert('You are not authorized to perform this action');</script>";
    echo "<script>window.location.href = 'dashboard.php'</script>";
    exit;
}

// Process delete action if confirmed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete query
    $sqlDelete = "DELETE FROM birds_mortality WHERE id = :id";
    $queryDelete = $dbh->prepare($sqlDelete);
    $queryDelete->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute delete query
    if ($queryDelete->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Failed to delete record');</script>";
    }

    // Redirect to previous page or appropriate location
    echo "<script>window.location.href = 'birds_mortality.php'</script>";
}
?>
