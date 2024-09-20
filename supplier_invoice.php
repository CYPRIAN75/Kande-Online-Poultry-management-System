<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Insert data into database
if(isset($_POST['submit'])) {
    $supplier_address = $_POST['supplier_address'];
    $invoice_number = $_POST['invoice_number'];
    $product_type = $_POST['product_type']; // New field for product type

    // Validate and sanitize inputs if needed

    // SQL to insert data into supplier_invoices table
    $sql = "INSERT INTO supplier_invoices (supplier_address, invoice_number, product_type) VALUES (:supplier_address, :invoice_number, :product_type)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':supplier_address', $supplier_address, PDO::PARAM_STR);
    $query->bindParam(':invoice_number', $invoice_number, PDO::PARAM_STR);
    $query->bindParam(':product_type', $product_type, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if($lastInsertId) {
        $_SESSION['msg'] = "Invoice added successfully";
        header('location:supplier_invoice.php'); // Redirect to avoid form resubmission
        exit;
    } else {
        $_SESSION['error'] = "Failed to add invoice";
    }
}

// Delete record from database
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM supplier_invoices WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Invoice deleted successfully";
    header('location:supplier_invoice.php'); // Redirect after deletion
    exit;
}

// Display records from database
$sql = "SELECT * FROM supplier_invoices";
$query = $dbh->prepare($sql);
$query->execute();
$records = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Supplier Invoice</title>
    <?php include_once('includes/head.php'); ?>
    <!-- Add additional CSS or JavaScript includes as needed -->
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
                                    <h4 class="card-title">Add Supplier Invoice</h4>
                                    <?php
                                    if(isset($_SESSION['msg'])) {
                                        echo '<div class="alert alert-success">' . $_SESSION['msg'] . '</div>';
                                        unset($_SESSION['msg']);
                                    }
                                    if(isset($_SESSION['error'])) {
                                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                        unset($_SESSION['error']);
                                    }
                                    ?>
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label>Supplier Address</label>
                                            <input type="text" class="form-control" name="supplier_address" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Invoice Number</label>
                                            <input type="text" class="form-control" name="invoice_number" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Product Type</label>
                                            <select class="form-control" name="product_type" required>
                                                <option value="">Select Product Type</option>
                                                <option value="Feeds">Feeds</option>
                                                <option value="Materials">Materials</option>
                                                <option value="Medicine">Medicine</option>
                                            </select>
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
                                    <h4 class="card-title">Saved Supplier Invoices</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Supplier Address</th>
                                                    <th>Invoice Number</th>
                                                    <th>Product Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($records as $record) { ?>
                                                    <tr>
                                                        <td><?php echo $record['id']; ?></td>
                                                        <td><?php echo $record['supplier_address']; ?></td>
                                                        <td><?php echo $record['invoice_number']; ?></td>
                                                        <td><?php echo $record['product_type']; ?></td>
                                                        <td>
                                                                                                                    <a href="delete_supplier_invoice.php?id=<?php echo $record['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
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
