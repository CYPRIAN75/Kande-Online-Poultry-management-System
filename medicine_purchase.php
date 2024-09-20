<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['odmsaid']) || empty($_SESSION['odmsaid'])) {
    header('location: logout.php');
    exit;
}

// Fetch data for dropdown of supplier invoices
$sql = "SELECT id, invoice_number FROM supplier_invoices";
$query = $dbh->prepare($sql);
$query->execute();
$invoices = $query->fetchAll(PDO::FETCH_ASSOC);

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM medicine_purchases WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Medicine purchase deleted successfully";
    header('location: medicine_purchase.php'); // Redirect after deletion
    exit;
}

// Handle edit action
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];

    // Fetch record to populate form
    $sql = "SELECT * FROM medicine_purchases WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $record = $query->fetch(PDO::FETCH_ASSOC);

    // Proceed with editing after form submission
    if (isset($_POST['submit'])) {
        $invoice_id = $_POST['invoice_id'];
        $medicine_name = $_POST['medicine_name'];
        $importance = $_POST['importance'];
        $use_info = $_POST['use'];
        $person_in_charge = $_POST['person_in_charge'];
        $quantity = $_POST['quantity'];
        $price_per_unit = $_POST['price_per_unit'];

        // Update record in database
        $sql = "UPDATE medicine_purchases 
                SET invoice_id = :invoice_id, medicine_name = :medicine_name, 
                    importance = :importance, use_info = :use_info, 
                    person_in_charge = :person_in_charge, quantity = :quantity, 
                    price_per_unit = :price_per_unit
                WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $query->bindParam(':medicine_name', $medicine_name, PDO::PARAM_STR);
        $query->bindParam(':importance', $importance, PDO::PARAM_STR);
        $query->bindParam(':use_info', $use_info, PDO::PARAM_STR);
        $query->bindParam(':person_in_charge', $person_in_charge, PDO::PARAM_STR);
        $query->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $query->bindParam(':price_per_unit', $price_per_unit, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $_SESSION['msg'] = "Medicine purchase updated successfully";
        header('location: medicine_purchase.php'); // Redirect after update
        exit;
    }
}

// Display records from database
$sql = "SELECT mp.id, si.invoice_number, mp.medicine_name, mp.importance, mp.use_info, mp.person_in_charge, mp.quantity, mp.price_per_unit, mp.total_price 
        FROM medicine_purchases mp 
        INNER JOIN supplier_invoices si ON mp.invoice_id = si.id";
$query = $dbh->prepare($sql);
$query->execute();
$records = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Medicine Purchase</title>
    <?php include_once('includes/head.php'); ?>
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
                                    <a href="dashboard.php" class="btn btn-secondary">Go Back</a>
                                    <h4 class="card-title">Add Medicine Purchase</h4>
                                    <?php
                                    if(isset($_SESSION['msg'])) {
                                        echo '<div class="alert alert-success">' . $_SESSION['msg'] . '</div>';
                                        unset($_SESSION['msg']);
                                    }
                                    if(isset($_SESSION['error'])) {
                                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                        unset($_SESSION['error']);
                                    }

                                    // Check if editing
                                    if (isset($_GET['edit_id'])) {
                                        $id = $_GET['edit_id'];
                                        $sql = "SELECT * FROM medicine_purchases WHERE id = :id";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':id', $id, PDO::PARAM_INT);
                                        $query->execute();
                                        $record = $query->fetch(PDO::FETCH_ASSOC);
                                    }
                                    ?>
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label>Supplier's Invoice Number</label>
                                            <select class="form-control" name="invoice_id" required>
                                                <option value="">Select Invoice Number</option>
                                                <?php foreach ($invoices as $invoice) { ?>
                                                    <option value="<?php echo $invoice['id']; ?>" <?php if(isset($record) && $record['invoice_id'] == $invoice['id']) echo 'selected'; ?>><?php echo $invoice['invoice_number']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Medicine Name</label>
                                            <input type="text" class="form-control" name="medicine_name" value="<?php if(isset($record)) echo $record['medicine_name']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Importance</label>
                                            <textarea class="form-control" name="importance" rows="3" required><?php if(isset($record)) echo $record['importance']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Use Information</label>
                                            <textarea class="form-control" name="use" rows="3" required><?php if(isset($record)) echo $record['use_info']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Person in Charge</label>
                                            <input type="text" class="form-control" name="person_in_charge" value="<?php if(isset($record)) echo $record['person_in_charge']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control" name="quantity" value="<?php if(isset($record)) echo $record['quantity']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Price per Unit</label>
                                            <input type="text" class="form-control" name="price_per_unit" value="<?php if(isset($record)) echo $record['price_per_unit']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="submit" class="btn btn-primary mr-2"><?php echo isset($record) ? 'Update' : 'Submit'; ?></button>
                                            <a href="medicine_purchase.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Medicine Purchase Records</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Invoice Number</th>
                                                    <th>Medicine Name</th>
                                                    <th>Importance</th>
                                                    <th>Use Information</th>
                                                    <th>Person in Charge</th>
                                                    <th>Quantity</th>
                                                    <th>Price per Unit</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($records as $record) { ?>
                                                    <tr>
                                                        <td><?php echo $record['invoice_number']; ?></td>
                                                        <td><?php echo $record['medicine_name']; ?></td>
                                                        <td><?php echo $record['importance']; ?></td>
                                                        <td><?php echo $record['use_info']; ?></td>
                                                        <td><?php echo $record['person_in_charge']; ?></td>
                                                        <td><?php echo $record['quantity']; ?></td>
                                                        <td><?php echo $record['price_per_unit']; ?></td>
                                                        <td><?php echo $record['total_price']; ?></td>
                                                        <td>
                                                            <a href="medicine_purchase.php?edit_id=<?php echo $record['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                                            <a href="medicine_purchase.php?delete_id=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger btn-sm">Delete</a>
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
            </div>
        </div>
    </div>

    
</body>
</html>
