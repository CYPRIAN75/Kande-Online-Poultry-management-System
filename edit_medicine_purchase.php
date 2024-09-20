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
    $medicine_id = $_POST['medicine_id'];
    $supplier_id = $_POST['supplier_id'];
    $purchase_date = $_POST['purchase_date'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Update query
    $sqlUpdate = "UPDATE medicine_purchase SET medicine_id = :medicine_id, supplier_id = :supplier_id, purchase_date = :purchase_date, quantity = :quantity, price = :price WHERE id = :id";

    // Admin can update any record, user can only update their own records
    if (!$isAdmin) {
        $sqlUpdate .= " AND user_id = :uid";
    }

    $queryUpdate = $dbh->prepare($sqlUpdate);
    $queryUpdate->bindParam(':medicine_id', $medicine_id, PDO::PARAM_INT);
    $queryUpdate->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);
    $queryUpdate->bindParam(':purchase_date', $purchase_date, PDO::PARAM_STR);
    $queryUpdate->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $queryUpdate->bindParam(':price', $price, PDO::PARAM_STR);
    $queryUpdate->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$isAdmin) {
        $queryUpdate->bindParam(':uid', $uid, PDO::PARAM_INT);
    }

    // Execute update query
    if ($queryUpdate->execute()) {
        $msg = '<div class="alert alert-success" role="alert">Medicine purchase updated successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger" role="alert">Failed to update medicine purchase</div>';
    }
}

// Fetch existing record details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Select query to fetch record details
    $sqlSelect = "SELECT * FROM medicine_purchase WHERE id = :id";

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
    <title>Edit Medicine Purchase</title>
    <!-- Include your CSS and JavaScript files -->
</head>
<body>
    <div class="container">
        <h2>Edit Medicine Purchase</h2>
        <?php echo $msg; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
            <div class="form-group">
                <label for="medicine_id">Medicine ID:</label>
                <input type="text" class="form-control" id="medicine_id" name="medicine_id" value="<?php echo $result['medicine_id']; ?>" required>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier ID:</label>
                <input type="text" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo $result['supplier_id']; ?>" required>
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date:</label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo $result['purchase_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo $result['quantity']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo $result['price']; ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <a href="medicine_purchase.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
