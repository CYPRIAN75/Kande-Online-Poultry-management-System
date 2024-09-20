<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch the record to be edited
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM birds_mortality WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $record = $query->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        $_SESSION['error'] = "Record not found";
        header('location:birds_mortality.php');
        exit;
    }
}

// Update the record
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $total_birds = $_POST['total_birds'];
    $number_of_deaths = $_POST['number_of_deaths'];
    $remaining_birds = $total_birds - $number_of_deaths;
    $cause_of_mortality = $_POST['cause_of_mortality'];

    $sql = "UPDATE birds_mortality SET date = :date, total_birds = :total_birds, number_of_deaths = :number_of_deaths, remaining_birds = :remaining_birds, cause_of_mortality = :cause_of_mortality WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':total_birds', $total_birds, PDO::PARAM_INT);
    $query->bindParam(':number_of_deaths', $number_of_deaths, PDO::PARAM_INT);
    $query->bindParam(':remaining_birds', $remaining_birds, PDO::PARAM_INT);
    $query->bindParam(':cause_of_mortality', $cause_of_mortality, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Bird mortality entry updated successfully";
    header('location:birds_mortality.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Bird Mortality</title>
    <?php include_once('includes/head.php'); ?>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Bird Mortality Entry</h4>
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
                                        <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date" value="<?php echo $record['date']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Number of Birds</label>
                                            <input type="number" class="form-control" name="total_birds" id="total_birds" value="<?php echo $record['total_birds']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Number of Deaths</label>
                                            <input type="number" class="form-control" name="number_of_deaths" id="number_of_deaths" value="<?php echo $record['number_of_deaths']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Remaining Number of Birds</label>
                                            <input type="number" class="form-control" name="remaining_birds" id="remaining_birds" value="<?php echo $record['remaining_birds']; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Cause of Mortality</label>
                                            <input type="text" class="form-control" name="cause_of_mortality" value="<?php echo $record['cause_of_mortality']; ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                                        <a href="birds_mortality.php" class="btn btn-light">Cancel</a>
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

    <script>
        document.getElementById('number_of_deaths').addEventListener('input', function() {
            var totalBirds = document.getElementById('total_birds').value;
            var numberOfDeaths = document.getElementById('number_of_deaths').value;
            var remainingBirds = totalBirds - numberOfDeaths;
            document.getElementById('remaining_birds').value = remainingBirds;
        });

        document.getElementById('total_birds').addEventListener('input', function() {
            var totalBirds = document.getElementById('total_birds').value;
            var numberOfDeaths = document.getElementById('number_of_deaths').value;
            var
