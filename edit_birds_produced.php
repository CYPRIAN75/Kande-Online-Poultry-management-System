<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Get record ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    header('location:birds_produced.php');
    exit;
}

// Fetch record details
$sql = "SELECT * FROM birds_produced WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$record = $query->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    $_SESSION['error'] = "Record not found";
    header('location:birds_produced.php');
    exit;
}

// Update record in the database
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $bird_type = $_POST['bird_type'];
    $total_birds = $_POST['total_birds'];
    $medicated = $_POST['medicated'];
    $not_medicated = $_POST['not_medicated'];

    // SQL to update data in birds_produced table
    $sql = "UPDATE birds_produced SET date = :date, bird_type = :bird_type, total_birds = :total_birds, 
            medicated = :medicated, not_medicated = :not_medicated WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':bird_type', $bird_type, PDO::PARAM_STR);
    $query->bindParam(':total_birds', $total_birds, PDO::PARAM_INT);
    $query->bindParam(':medicated', $medicated, PDO::PARAM_INT);
    $query->bindParam(':not_medicated', $not_medicated, PDO::PARAM_INT);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Birds produced entry updated successfully";
    header('location:birds_produced.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Birds Produced Entry</title>
    <?php include_once('includes/head.php'); ?>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/head.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Birds Produced Entry</h4>
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
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date" value="<?php echo htmlentities($record['date']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Bird Type</label>
                                            <input type="text" class="form-control" name="bird_type" value="<?php echo htmlentities($record['bird_type']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Number of Birds Produced</label>
                                            <input type="number" class="form-control" name="total_birds" value="<?php echo htmlentities($record['total_birds']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Medicated</label>
                                            <input type="number" class="form-control" name="medicated" value="<?php echo htmlentities($record['medicated']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Not Medicated</label>
                                            <input type="number" class="form-control" name="not_medicated" value="<?php echo htmlentities($record['not_medicated']); ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2" name="submit">Save</button>
                                        <a href="birds_produced.php" class="btn btn-light">Go Back</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>
</body>
</html>
