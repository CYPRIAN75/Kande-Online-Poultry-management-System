<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Insert data into database
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $bird_type = $_POST['bird_type'];
    $total_birds = $_POST['total_birds'];
    $medicated = $_POST['medicated'];
    $not_medicated = $_POST['not_medicated'];

    // SQL to insert data into birds_produced table
    $sql = "INSERT INTO birds_produced (date, bird_type, total_birds, medicated, not_medicated) 
            VALUES (:date, :bird_type, :total_birds, :medicated, :not_medicated)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':bird_type', $bird_type, PDO::PARAM_STR);
    $query->bindParam(':total_birds', $total_birds, PDO::PARAM_INT);
    $query->bindParam(':medicated', $medicated, PDO::PARAM_INT);
    $query->bindParam(':not_medicated', $not_medicated, PDO::PARAM_INT);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if ($lastInsertId) {
        $_SESSION['msg'] = "Birds produced entry added successfully";
        header('location:birds_produced.php'); // Redirect to avoid form resubmission
        exit;
    } else {
        $_SESSION['error'] = "Failed to add birds produced entry";
    }
}

// Delete record from database
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM birds_produced WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Birds produced entry deleted successfully";
    header('location:birds_produced.php'); // Redirect after deletion
    exit;
}

// Display records from database
$sql = "SELECT * FROM birds_produced";
$query = $dbh->prepare($sql);
$query->execute();
$records = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Birds Produced</title>
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
                                    <h4 class="card-title">Add Birds Produced Entry</h4>
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
                                            <label>Bird Type</label>
                                            <input type="text" class="form-control" name="bird_type" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Number of Birds Produced</label>
                                            <input type="number" class="form-control" name="total_birds" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Medicated</label>
                                            <input type="number" class="form-control" name="medicated" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Not Medicated</label>
                                            <input type="number" class="form-control" name="not_medicated" required>
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
                                    <h4 class="card-title">Birds Produced Records</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Bird Type</th>
                                                    <th>Total Birds</th>
                                                    <th>Medicated</th>
                                                    <th>Not Medicated</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($records as $record) { ?>
                                                    <tr>
                                                        <td><?php echo $record['id']; ?></td>
                                                        <td><?php echo $record['date']; ?></td>
                                                        <td><?php echo $record['bird_type']; ?></td>
                                                        <td><?php echo $record['total_birds']; ?></td>
                                                        <td><?php echo $record['medicated']; ?></td>
                                                        <td><?php echo $record['not_medicated']; ?></td>
                                                        <td>
                                                            <a href="edit_birds_produced.php?id=<?php echo $record['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                                            <a href="delete_produced.php?delete_id=<?php echo $record['id']; ?>" class="btn btn-info btn-sm">Delete</a>
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
</body>
</html>
