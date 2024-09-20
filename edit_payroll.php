<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Check if ID parameter is passed
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request";
    header('location: payroll.php');
    exit;
}

$id = $_GET['id'];

// Fetch payroll details based on ID
$sql = "SELECT p.id, p.employee_id, a.username as employee_name, p.wages, p.role 
        FROM payroll p 
        INNER JOIN tbladmin a ON p.employee_id = a.id 
        WHERE p.id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$payroll = $query->fetch(PDO::FETCH_ASSOC);

if (!$payroll) {
    $_SESSION['error'] = "Payroll entry not found";
    header('location: payroll.php');
    exit;
}

// Fetch employees from tbladmin
$sql = "SELECT id, username FROM tbladmin";
$query = $dbh->prepare($sql);
$query->execute();
$employees = $query->fetchAll(PDO::FETCH_ASSOC);

// Update data in database
if (isset($_POST['submit'])) {
    $employee_id = $_POST['employee_id'];
    $wages = $_POST['wages'];
    $role = $_POST['role'];

    // SQL to update data in payroll table
    $sql = "UPDATE payroll 
            SET employee_id = :employee_id, wages = :wages, role = :role 
            WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $query->bindParam(':wages', $wages, PDO::PARAM_STR);
    $query->bindParam(':role', $role, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Payroll entry updated successfully";
    header('location: payroll.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Payroll Entry</title>
    <?php include_once('includes/head.php'); ?>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/dbconnection.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Payroll Entry</h4>
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
                                            <label>Employee</label>
                                            <select class="form-control" name="employee_id" required>
                                                <option value="">Select Employee</option>
                                                <?php foreach ($employees as $employee) { ?>
                                                    <option value="<?php echo $employee['id']; ?>" <?php if ($employee['id'] == $payroll['employee_id']) echo 'selected'; ?>>
                                                        <?php echo $employee['username']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Wages</label>
                                            <input type="text" class="form-control" name="wages" value="<?php echo $payroll['wages']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <input type="text" class="form-control" name="role" value="<?php echo $payroll['role']; ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                                        <a href="payroll.php" class="btn btn-light">Cancel</a>
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
