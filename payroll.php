<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['odmsaid']) || empty($_SESSION['odmsaid'])) {
    header('location: logout.php');
    exit;
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM payroll WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Payroll record deleted successfully";
    header('location: payroll.php'); // Redirect after deletion
    exit;
}

// Handle edit action
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];

    // Fetch record to populate form
    $sql = "SELECT * FROM payroll WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $record = $query->fetch(PDO::FETCH_ASSOC);

    // Proceed with editing after form submission
    if (isset($_POST['submit'])) {
        $employee_id = $_POST['employee_id'];
        $wages = $_POST['wages'];

        // Update record in database
        $sql = "UPDATE payroll 
                SET employee_id = :employee_id, wages = :wages
                WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
        $query->bindParam(':wages', $wages, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $_SESSION['msg'] = "Payroll record updated successfully";
        header('location: payroll.php'); // Redirect after update
        exit;
    }
}

// Fetch employees from tbladmin for dropdown
$sql = "SELECT id, UserName FROM tbladmin";
$query = $dbh->prepare($sql);
$query->execute();
$employees = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch all payroll records
$sql = "SELECT p.id, a.UserName, p.wages 
        FROM payroll p 
        INNER JOIN tbladmin a ON p.employee_id = a.id";
$query = $dbh->prepare($sql);
$query->execute();
$payroll_records = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payroll Management</title>
    <?php include_once('includes/head.php'); ?>
    <style>
        /* styles.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

.container-scroller {
    height: 100vh;
    overflow: auto;
}

.container-fluid {
    padding: 30px;
}

.card-title {
    margin-bottom: 20px;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: #fff;
}

.table th,
.table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.table th {
    background-color: #f8f9fa;
    color: #495057;
    font-weight: 600;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.btn {
    cursor: pointer;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.875rem;
}

.alert {
    padding: 0.75rem 1.25rem;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.form-group {
    margin-bottom: 1rem;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mt-4 {
    margin-top: 1.5rem;
}

.card {
    margin-bottom: 20px;
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
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
                                    <a href="dashboard.php" class="btn btn-primary">Previous</a>
                                    <h4 class="card-title">Payroll Management</h4>
                                    <?php
                                    if(isset($_SESSION['msg'])) {
                                        echo '<div class="alert alert-success">' . $_SESSION['msg'] . '</div>';
                                        unset($_SESSION['msg']);
                                    }
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Wages</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($payroll_records as $record) { ?>
                                                    <tr>
                                                        <td><?php echo $record['UserName']; ?></td>
                                                        <td><?php echo $record['wages']; ?></td>
                                                        <td>
                                                            <a href="payroll.php?edit_id=<?php echo $record['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                                            <a href="payroll.php?delete_id=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger btn-sm">Delete</a>
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

                    <?php if (isset($_GET['edit_id']) || isset($_GET['delete_id'])) { ?>
                        <div class="row mt-4">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo isset($_GET['edit_id']) ? 'Edit Payroll Record' : 'Add Payroll Record'; ?></h4>
                                        <form method="post" action="">
                                            <div class="form-group">
                                                <label>Select Employee</label>
                                                <select class="form-control" name="employee_id" required>
                                                    <option value="">Select Employee</option>
                                                    <?php foreach ($employees as $employee) { ?>
                                                        <option value="<?php echo $employee['id']; ?>" <?php if(isset($record) && $record['id'] == $employee['id']) echo 'selected'; ?>><?php echo $employee['UserName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Wages</label>
                                                <input type="text" class="form-control" name="wages" value="<?php if(isset($record)) echo $record['wages']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="submit" class="btn btn-primary mr-2"><?php echo isset($record) ? 'Update' : 'Submit'; ?></button>
                                                <a href="payroll.php" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
</html>
