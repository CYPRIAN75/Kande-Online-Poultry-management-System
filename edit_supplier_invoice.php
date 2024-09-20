<?php
// Include necessary files (dbconnection.php, session start, etc.)
include('includes/dbconnection.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['odmsuid'])) {
    header('Location: login.php');
    exit;
}

// Get logged-in user ID
$uid = $_SESSION['odmsuid'];

// Authorization Check: Admin can edit any record, user can edit their own records
$isAdmin = false;
if (isset($_SESSION['odmsaid'])) {
    $isAdmin = true;
}

// Initialize variables
$msg = '';

// Process form submission
if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $supplier_id = $_POST['supplier_id'];
    $invoice_number = $_POST['invoice_number'];
    $invoice_date = $_POST['invoice_date'];
    $amount = $_POST['amount'];

    // Update query
    $sqlUpdate = "UPDATE supplier_invoice SET supplier_id = :supplier_id, invoice_number = :invoice_number, invoice_date = :invoice_date, amount = :amount WHERE id = :id";

    // Admin can update any record, user can only update their own records
    if (!$isAdmin) {
        $sqlUpdate .= " AND user_id = :uid";
    }

    $queryUpdate = $dbh->prepare($sqlUpdate);
    $queryUpdate->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);
    $queryUpdate->bindParam(':invoice_number', $invoice_number, PDO::PARAM_STR);
    $queryUpdate->bindParam(':invoice_date', $invoice_date, PDO::PARAM_STR);
    $queryUpdate->bindParam(':amount', $amount, PDO::PARAM_STR);
    $queryUpdate->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$isAdmin) {
        $queryUpdate->bindParam(':uid', $uid, PDO::PARAM_INT);
    }

    // Execute update query
    if ($queryUpdate->execute()) {
        $msg = '<div class="alert alert-success" role="alert">Supplier invoice updated successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger" role="alert">Failed to update supplier invoice</div>';
    }
}

// Fetch existing record details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Select query to fetch record details
    $sqlSelect = "SELECT * FROM supplier_invoice WHERE id = :id";

    // Admin can fetch any record, user can only fetch their own records
    if (!$isAdmin) {
        $sqlSelect .= " AND user_id = :uid";
    }

    $querySelect = $dbh->prepare($sqlSelect);
    $querySelect->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$isAdmin) {
        $querySelect->bindParam(':uid', $uid, PDO::PARAM_INT);
    }

    $querySelect->execute();
    $result = $querySelect->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier Invoice</title>
    <!-- Include your CSS and JavaScript files -->
</head>
<body>
    <div class="container">
        <h2>Edit Supplier Invoice</h2>
        <?php echo $msg; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
            <div class="form-group">
                <label for="supplier_id">Supplier ID:</label>
                <input type="text" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo $result['supplier_id']; ?>" required>
            </div>
            <div class="form-group">
                <label for="invoice_number">Invoice Number:</label>
                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?php echo $result['invoice_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="invoice_date">Invoice Date:</label>
                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo $result['invoice_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" id="amount" name="amount" value="<?php echo $result['amount']; ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <a href="supplier_invoice.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
