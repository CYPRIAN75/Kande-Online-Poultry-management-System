<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Insert data into the database
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $total_birds = $_POST['total_birds'];
    $number_of_deaths = $_POST['number_of_deaths'];
    $remaining_birds = $total_birds - $number_of_deaths;
    $cause_of_mortality = $_POST['cause_of_mortality'];

    // SQL to insert data into birds_mortality table
    $sql = "INSERT INTO birds_mortality (date, total_birds, number_of_deaths, remaining_birds, cause_of_mortality) 
            VALUES (:date, :total_birds, :number_of_deaths, :remaining_birds, :cause_of_mortality)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':total_birds', $total_birds, PDO::PARAM_INT);
    $query->bindParam(':number_of_deaths', $number_of_deaths, PDO::PARAM_INT);
    $query->bindParam(':remaining_birds', $remaining_birds, PDO::PARAM_INT);
    $query->bindParam(':cause_of_mortality', $cause_of_mortality, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if ($lastInsertId) {
        $_SESSION['msg'] = "Bird mortality entry added successfully";
        header('location:birds_mortality.php'); // Redirect to avoid form resubmission
        exit;
    } else {
        $_SESSION['error'] = "Failed to add bird mortality entry";
    }
}

// Delete record from the database
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM birds_mortality WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Bird mortality entry deleted successfully";
    header('location:birds_mortality.php'); // Redirect after deletion
    exit;
}

// Display records from the database
$sql = "SELECT * FROM birds_mortality";
$query = $dbh->prepare($sql);
$query->execute();
$records = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bird Mortality</title>
    <?php include_once('includes/head.php'); ?>
    <style>
        /* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

.container-scroller {
    min-height: 100vh;
    background-color: #f5f5f5;
}

.container-fluid {
    padding: 20px;
}

.card {
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 20px;
}

.card-title {
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.btn {
    cursor: pointer;
}

.table {
    width: 100%;
    margin-bottom: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.table th,
.table td {
    padding: 10px;
    text-align: center;
}

.alert {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid transparent;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

    </style>
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
                                    <h4 class="card-title">Add Bird Mortality Entry</h4>
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
                                            <input type="date" class="form-control" name="date" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Number of Birds</label>
                                            <input type="number" class="form-control" name="total_birds" id="total_birds" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Number of Deaths</label>
                                            <input type="number" class="form-control" name="number_of_deaths" id="number_of_deaths" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Remaining Number of Birds</label>
                                            <input type="number" class="form-control" name="remaining_birds" id="remaining_birds" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Cause of Mortality</label>
                                            <input type="text" class="form-control" name="cause_of_mortality" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2" name="submit">Save</button>
                                        <a href="dashboard.php" class="btn btn-light">Go Back</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Bird Mortality Records</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Total Birds</th>
                                                    <th>Number of Deaths</th>
                                                    <th>Remaining Birds</th>
                                                    <th>Cause of Mortality</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($records as $record) { ?>
                                                    <tr>
                                                        <td><?php echo $record['id']; ?></td>
                                                        <td><?php echo $record['date']; ?></td>
                                                        <td><?php echo $record['total_birds']; ?></td>
                                                        <td><?php echo $record['number_of_deaths']; ?></td>
                                                        <td><?php echo $record['remaining_birds']; ?></td>
                                                        <td><?php echo $record['cause_of_mortality']; ?></td>
                                                        <td>
                                                            <a href="edit_birds_mortality.php?id=<?php echo $record['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                                            <a href="birds_mortality.php?delete_id=<?php echo $record['id']; ?>" class="btn btn-info btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
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
            var remainingBirds = totalBirds - numberOfDeaths;
            document.getElementById('remaining_birds').value = remainingBirds;
        });
    </script>
</body>
</html>
