<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch data for dropdown of supplier invoices
$sql = "SELECT * FROM supplier_invoices";
$query = $dbh->prepare($sql);
$query->execute();
$invoices = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Feed Purchase</title>
    <?php include_once('includes/head.php'); ?>
    <!-- Add additional CSS or JavaScript includes as needed -->
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .container-scroller {
            min-height: 100vh;
            position: relative;
        }

        .page-body-wrapper {
            min-height: 100vh;
        }

        .main-panel {
            padding: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 1.25rem;
            color: #333;
        }

        /* Form Styles */
        .form-control {
            border: 1px solid #ced4da;
            padding: 0.75rem;
            border-radius: 5px;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .btn {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
            color: #495057;
        }

        .btn-light:hover {
            background-color: #e2e6ea;
            border-color: #dae0e5;
            color: #495057;
        }

        /* Table Styles */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #333;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.15);
        }

        /* Navigation Styles */
        .text-right {
            text-align: right;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mr-3 {
            margin-right: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-title {
                font-size: 1.1rem;
            }
        }

    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/dbconnection.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- Navigation Bar -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-right mt-2 mr-3">
                                <a href="dashboard.php" class="btn btn-primary">Go Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                    <!-- End Navigation Bar -->

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Feed Purchase</h4>
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="date" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Feed Name</label>
                                            <input type="text" class="form-control" name="feed_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Invoice Number from Supplier</label>
                                            <select class="form-control" name="invoice_number" required>
                                                <option value="">Select Invoice Number</option>
                                                <?php foreach ($invoices as $invoice) { ?>
                                                    <option value="<?php echo $invoice['invoice_number']; ?>"><?php echo $invoice['invoice_number']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <input type="text" class="form-control" name="supplier_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier's Email</label>
                                            <input type="email" class="form-control" name="supplier_email" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier's Contact</label>
                                            <input type="text" class="form-control" name="supplier_contact" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Person in Charge</label>
                                            <input type="text" class="form-control" name="person_in_charge" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control" name="quantity" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Price per Unit</label>
                                            <input type="text" class="form-control" name="price_per_unit" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Price</label>
                                            <input type="text" class="form-control" name="total_price" readonly>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2" name="save_record">Save</button>
                                        <button type="reset" class="btn btn-light">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Saved Feed Purchases</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Feed Name</th>
                                                    <th>Invoice Number</th>
                                                    <th>Supplier Name</th>
                                                    <th>Supplier Email</th>
                                                    <th>Supplier Contact</th>
                                                    <th>Person in Charge</th>
                                                    <th>Quantity</th>
                                                    <th>Price per Unit</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- PHP code to fetch and display saved records -->
                                                <?php
                                                $sql = "SELECT * FROM feed_purchases";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $records = $query->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($records as $record) {
                                                    echo "<tr>";
                                                    echo "<td>{$record['id']}</td>";
                                                    echo "<td>{$record['date']}</td>";
                                                    echo "<td>{$record['feed_name']}</td>";
                                                    echo "<td>{$record['invoice_number']}</td>";
                                                    echo "<td>{$record['supplier_name']}</td>";
                                                    echo "<td>{$record['supplier_email']}</td>";
                                                    echo "<td>{$record['supplier_contact']}</td>";
                                                    echo "<td>{$record['person_in_charge']}</td>";
                                                    echo "<td>{$record['quantity']}</td>";
                                                    echo "<td>{$record['price_per_unit']}</td>";
                                                    echo "<td>{$record['total_price']}</td>";
                                                    echo "<td>
                                                            <a href='edit_feed_purchase.php?id={$record['id']}' class='btn btn-info btn-sm'>Edit</a>
                                                            <a href='delete_feed_purchase.php?id={$record['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                                ?>
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
