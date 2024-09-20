<?php
session_start();
include('includes/dbconnection.php');

// Check if ID parameter is present
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header('location: feed_consumption.php');
    exit;
}

$id = $_GET['id'];

// Fetch the existing record from the database
$sql = "SELECT * FROM feed_consumption WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$record = $query->fetch(PDO::FETCH_ASSOC);

// Check if record exists
if (!$record) {
    $_SESSION['error'] = "Record not found.";
    header('location: feed_consumption.php');
    exit;
}

// Handle form submission for updating record
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $person_in_charge = $_POST['person_in_charge'];
    $total_feeds_in_store = $_POST['total_feeds_in_store'];
    $amount_consumed = $_POST['amount_consumed'];
    $remaining_feeds = $total_feeds_in_store - $amount_consumed;

    // Update record in the database
    $sql_update = "UPDATE feed_consumption 
                   SET date = :date, 
                       person_in_charge = :person_in_charge, 
                       total_feeds_in_store = :total_feeds_in_store, 
                       amount_consumed = :amount_consumed,
                       remaining_feeds = :remaining_feeds
                   WHERE id = :id";
    $query_update = $dbh->prepare($sql_update);
    $query_update->bindParam(':date', $date, PDO::PARAM_STR);
    $query_update->bindParam(':person_in_charge', $person_in_charge, PDO::PARAM_STR);
    $query_update->bindParam(':total_feeds_in_store', $total_feeds_in_store, PDO::PARAM_INT);
    $query_update->bindParam(':amount_consumed', $amount_consumed, PDO::PARAM_INT);
    $query_update->bindParam(':remaining_feeds', $remaining_feeds, PDO::PARAM_INT);
    $query_update->bindParam(':id', $id, PDO::PARAM_INT);
    $query_update->execute();

    $_SESSION['msg'] = "Feed consumption record updated successfully.";
    header('location: feed_consumption.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Feed Consumption</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <a href="feed_consumption.php" class="btn btn-light">Go Back</a>
        <h2>Edit Feed Consumption Record</h2>
        <?php
        if (isset($_SESSION['msg'])) {
            echo '<div class="alert alert-success">' . $_SESSION['msg'] . '</div>';
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form method="post">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $record['date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="person_in_charge">Person in Charge:</label>
                <input type="text" id="person_in_charge" name="person_in_charge" value="<?php echo $record['person_in_charge']; ?>" required>
            </div>
            <div class="form-group">
                <label for="total_feeds_in_store">Total Feeds in Store (kg):</label>
                <input type="number" id="total_feeds_in_store" name="total_feeds_in_store" value="<?php echo $record['total_feeds_in_store']; ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="amount_consumed">Amount Consumed (kg):</label>
                <input type="number" id="amount_consumed" name="amount_consumed" value="<?php echo $record['amount_consumed']; ?>" step="0.01" required>
            </div>
            <button type="submit" name="submit">Update</button>
        </form>
    </div>
</body>
</html>
