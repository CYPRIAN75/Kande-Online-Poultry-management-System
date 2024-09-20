<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM tblEggsProduced WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Eggs production record deleted successfully.";
} else {
    $_SESSION['error'] = "Invalid request.";
}

header('location:eggs_produced.php');
exit;
?>
