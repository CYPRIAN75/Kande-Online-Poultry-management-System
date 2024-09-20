<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch user role to ensure only admin can delete
$admin_id = $_SESSION['odmsaid'];
$sql = "SELECT role FROM tbladmin WHERE id = :admin_id";
$query = $dbh->prepare($sql);
$query->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$query->execute();
$userRole = $query->fetch(PDO::FETCH_ASSOC)['role'];

if ($userRole != 'admin') {
    $_SESSION['error'] = "You do not have permission to perform this action";
    header('location:dashboard.php');
    exit;
}

// Delete payroll entry
if (isset($_GET['id'])) {
    $payroll_id = $_GET['id'];
    $sql = "DELETE FROM payroll WHERE id = :payroll_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':payroll_id', $payroll_id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Payroll entry deleted successfully";
}

header('location:payroll.php'); // Redirect after deletion
exit;
?>
