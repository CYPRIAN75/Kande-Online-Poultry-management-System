<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Include database connection
include('includes/dbconnection.php');

// Initialize message variable
$message = "";

// Handle form submission for saving, editing, and deleting records
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        $supplier_name = $_POST['supplier_name'];
        $supplier_email = $_POST['supplier_email'];

        // Insert record into suppliers table
        $query = "INSERT INTO suppliers (supplier_name, supplier_email) VALUES ('$supplier_name', '$supplier_email')";
        if ($conn->query($query) === TRUE) {
            $message = "Supplier added successfully.";
        } else {
            $message = "Error adding supplier: " . $conn->error;
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $supplier_name = $_POST['supplier_name'];
        $supplier_email = $_POST['supplier_email'];

        // Update record in suppliers table
        $query = "UPDATE suppliers SET supplier_name='$supplier_name', supplier_email='$supplier_email' WHERE id='$id'";
        if ($conn->query($query) === TRUE) {
            $message = "Supplier updated successfully.";
        } else {
            $message = "Error updating supplier: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Delete record from suppliers table
        $query = "DELETE FROM suppliers WHERE id='$id'";
        if ($conn->query($query) === TRUE) {
            $message = "Supplier deleted successfully.";
        } else {
            $message = "Error deleting supplier: " . $conn->error;
        }
    }
}

// Fetch all records from suppliers table
$query = "SELECT * FROM suppliers";
$result = $conn->query($query);

$suppliers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Suppliers</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Adjust path as per your file structure -->
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .records-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .message {
            background-color: #f2f2f2;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="go-back-button">Go Back</a>
        <h2>Manage Suppliers</h2>

        <!-- Display message -->
        <?php if ($message != ""): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Form for adding/editing records -->
        <div class="form-container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" id="id" name="id">
                
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" id="supplier_name" name="supplier_name" required>
                
                <label for="supplier_email">Supplier Email:</label>
                <input type="email" id="supplier_email" name="supplier_email" required>
                
                <input type="submit" name="save" value="Save">
                <input type="submit" name="edit" value="Edit">
                <input type="submit" name="delete" value="Delete">
            </form>
        </div>

        <!-- Table displaying records -->
        <div class="records-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier Name</th>
                        <th>Supplier Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $supplier): ?>
                        <tr>
                            <td><?php echo $supplier['id']; ?></td>
                            <td><?php echo $supplier['supplier_name']; ?></td>
                            <td><?php echo $supplier['supplier_email']; ?></td>
                            <td>
                                <button onclick="editSupplier(<?php echo $supplier['id']; ?>, '<?php echo $supplier['supplier_name']; ?>', '<?php echo $supplier['supplier_email']; ?>')">Edit</button>
                                <button onclick="deleteSupplier(<?php echo $supplier['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editSupplier(id, supplierName, supplierEmail) {
            document.getElementById('id').value = id;
            document.getElementById('supplier_name').value = supplierName;
            document.getElementById('supplier_email').value = supplierEmail;
            document.querySelector('input[name="save"]').style.display = 'none';
            document.querySelector('input[name="edit"]').style.display = 'inline-block';
            document.querySelector('input[name="delete"]').style.display = 'none';
        }

        function deleteSupplier(id) {
            document.getElementById('id').value = id;
            document.querySelector('input[name="save"]').style.display = 'none';
            document.querySelector('input[name="edit"]').style.display = 'none';
            document.querySelector('input[name="delete"]').style.display = 'inline-block';
        }
    </script>
</body>
</html>
