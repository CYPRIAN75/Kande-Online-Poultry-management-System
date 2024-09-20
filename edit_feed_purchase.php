<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Check if ID parameter exists and fetch record details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch record from database
    $sql = "SELECT * FROM feed_purchases WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $feed_purchase = $query->fetch(PDO::FETCH_ASSOC);
    
    // Check if record exists
    if (!$feed_purchase) {
        // Redirect to feed purchase list or handle error
        header('location:feed_purchase.php');
        exit;
    }
} else {
    // Redirect to feed purchase list or handle error
    header('location:feed_purchase.php');
    exit;
}

// Update record in database upon form submission
if (isset($_POST['update_record'])) {
    $date = $_POST['date'];
    $feed_name = $_POST['feed_name'];
    $invoice_number = $_POST['invoice_number'];
    $supplier_name = $_POST['supplier_name'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_contact = $_POST['supplier_contact'];
    $person_in_charge = $_POST['person_in_charge'];
    $quantity = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $total_price = $_POST['total_price'];

    // Update query
    $sql = "UPDATE feed_purchases SET date = :date, feed_name = :feed_name, invoice_number = :invoice_number, supplier_name = :supplier_name, supplier_email = :supplier_email, supplier_contact = :supplier_contact, person_in_charge = :person_in_charge, quantity = :quantity, price_per_unit = :price_per_unit, total_price = :total_price WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':feed_name', $feed_name, PDO::PARAM_STR);
    $query->bindParam(':invoice_number', $invoice_number, PDO::PARAM_STR);
    $query->bindParam(':supplier_name', $supplier_name, PDO::PARAM_STR);
    $query->bindParam(':supplier_email', $supplier_email, PDO::PARAM_STR);
    $query->bindParam(':supplier_contact', $supplier_contact, PDO::PARAM_STR);
    $query->bindParam(':person_in_charge', $person_in_charge, PDO::PARAM_STR);
    $query->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $query->bindParam(':price_per_unit', $price_per_unit, PDO::PARAM_STR);
    $query->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($query->execute()) {
        // Redirect to feed purchase list or success page
        header('location:feed_purchase.php');
        exit;
    } else {
        // Handle update error
        echo "Error updating record.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Feed Purchase</title>
    <?php include_once('includes/head.php'); ?>
    <!-- Add additional CSS or JavaScript includes as needed -->
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/dbconnection.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- Navigation Bar - Optional -->
                    <div class="row">
                        <div class="col-12">
                            <a href="feed_purchase.php" class="btn btn-primary">Back to Feed Purchases</a>
                        </div>
                    </div>
                    <!-- End Navigation Bar -->

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Feed Purchase</h4>
                                    <form method="post" action="">
                                        <!-- Populate form fields with existing data -->
                                        <input type="hidden" name="id" value="<?php echo $feed_purchase['id']; ?>">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date" value="<?php echo $feed_purchase['date']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Feed Name</label>
                                            <input type="text" class="form-control" name="feed_name" value="<?php echo $feed_purchase['feed_name']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Invoice Number from Supplier</label>
                                            <input type="text" class="form-control" name="invoice_number" value="<?php echo $feed_purchase['invoice_number']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <input type="text" class="form-control" name="supplier_name" value="<?php echo $feed_purchase['supplier_name']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier's Email</label>
                                            <input type="email" class="form-control" name="supplier_email" value="<?php echo $feed_purchase['supplier_email']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier's Contact</label>
                                            <input type="text" class="form-control" name="supplier_contact" value="<?php echo $feed_purchase['supplier_contact']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Person in Charge</label>
                                            <input type="text" class="form-control" name="person_in_charge" value="<?php echo $feed_purchase['person_in_charge']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control" name="quantity" value="<?php echo $feed_purchase['quantity']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Price per Unit</label>
                                            <input type="text" class="form-control" name="price_per_unit" value="<?php echo $feed_purchase['price_per_unit']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Price</label>
                                            <input type="text" class="form-control" name="total_price" value="<?php echo $feed_purchase['total_price']; ?>" readonly>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2" name="update_record">Update</button>
                                        <a href="feed_purchase.php" class="btn btn-light">Cancel</a>
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
