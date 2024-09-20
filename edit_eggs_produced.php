<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tblEggsProduced WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        $_SESSION['error'] = "Record not found.";
        header('location:eggs_produced.php');
        exit;
    }
}

if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $total_eggs = $_POST['total_eggs'];
    $spoilt_eggs = $_POST['spoilt_eggs'];
    $hatching_eggs = $_POST['hatching_eggs'];
    $sale_eggs = $total_eggs - $spoilt_eggs - $hatching_eggs;
    $price_per_egg = $_POST['price_per_egg'];
    $total_price = $sale_eggs * $price_per_egg;

    $sql = "UPDATE tblEggsProduced SET date = :date, total_eggs = :total_eggs, spoilt_eggs = :spoilt_eggs, hatching_eggs = :hatching_eggs, sale_eggs = :sale_eggs, price_per_egg = :price_per_egg, total_price = :total_price WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':total_eggs', $total_eggs, PDO::PARAM_INT);
    $query->bindParam(':spoilt_eggs', $spoilt_eggs, PDO::PARAM_INT);
    $query->bindParam(':hatching_eggs', $hatching_eggs, PDO::PARAM_INT);
    $query->bindParam(':sale_eggs', $sale_eggs, PDO::PARAM_INT);
    $query->bindParam(':price_per_egg', $price_per_egg, PDO::PARAM_STR);
    $query->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Eggs production record updated successfully.";
    header('location:eggs_produced.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Eggs Produced</title>
    <style>
        /* Include the same styles as in the eggs_produced.php for consistency */
        /* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #eef2f3;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Form Styles */
form {
    margin-bottom: 20px;
}

form h2 {
    margin-top: 0;
    color: #4CAF50;
    border-bottom: 2px solid #4CAF50;
    padding-bottom: 10px;
    font-size: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="date"],
form button[type="submit"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group input[type="date"]:focus {
    border-color: #4CAF50;
}

form button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
    font-size: 16px;
}

table th {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table-actions {
    display: flex;
    gap: 10px;
}

.button-edit,
.button-delete {
    padding: 8px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 14px;
    color: white;
    text-decoration: none;
    text-align: center;
}

.button-edit {
    background-color: #ffc107;
}

.button-edit:hover {
    background-color: #e0a800;
}

.button-delete {
    background-color: #dc3545;
}

.button-delete:hover {
    background-color: #c82333;
}

/* Back Button Styles */
.btn-light {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 5px;
    background-color: #ddd;
    color: #333;
    text-decoration: none;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

.btn-light:hover {
    background-color: #ccc;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 95%;
    }

    table,
    form {
        font-size: 14px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="date"],
    form button[type="submit"] {
        font-size: 14px;
        padding: 10px;
    }

    .button-edit,
    .button-delete {
        font-size: 12px;
        padding: 6px 10px;
    }

    .btn-light {
        font-size: 14px;
        padding: 8px 12px;
    }
}

    </style>
    <script>
        function calculateTotalPrice() {
            var totalEggs = document.getElementById('total_eggs').value;
            var spoiltEggs = document.getElementById('spoilt_eggs').value;
            var hatchingEggs = document.getElementById('hatching_eggs').value;
            var saleEggs = totalEggs - spoiltEggs - hatchingEggs;
            document.getElementById('sale_eggs').value = saleEggs;

            var pricePerEgg = document.getElementById('price_per_egg').value;
            var totalPrice = saleEggs * pricePerEgg;
            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn btn-light">Go Back</a>
        <h2>Edit Eggs Produced</h2>
        <form method="post">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required value="<?php echo $result->date; ?>">
            </div>
            <div class="form-group">
                <label for="total_eggs">Total Number of Eggs Produced:</label>
                <input type="number" id="total_eggs" name="total_eggs" required oninput="calculateTotalPrice()" value="<?php echo $result->total_eggs; ?>">
            </div>
            <div class="form-group">
                <label for="spoilt_eggs">Number of Eggs Spoilt/Damaged:</label>
                <input type="number" id="spoilt_eggs" name="spoilt_eggs" required oninput="calculateTotalPrice()" value="<?php echo $result->spoilt_eggs; ?>">
            </div>
            <div class="form-group">
                <label for="hatching_eggs">Number of Eggs Left for Hatching:</label>
                <input type="number" id="hatching_eggs" name="hatching_eggs" required oninput="calculateTotalPrice()" value="<?php echo $result->hatching_eggs; ?>">
            </div>
            <div class="form-group">
                <label for="sale_eggs">Number of Eggs Left for Sale:</label>
                <input type="number" id="sale_eggs" name="sale_eggs" readonly value="<?php echo $result->sale_eggs; ?>">
            </div>
            <div class="form-group">
                <label for="price_per_egg">Price of Each Egg:</label>
                <input type="number" id="price_per_egg" name="price_per_egg" step="0.01" required oninput="calculateTotalPrice()" value="<?php echo $result->price_per_egg; ?>">
            </div>
            <div class="form-group">
                <label for="total_price">Total Price of Eggs Left for Sale:</label>
                <input type="number" id="total_price" name="total_price" step="0.01" readonly value="<?php echo $result->total_price; ?>">
            </div>
            <button type="submit" name="submit">Update</button>
        </form>
    </div>
</body>
</html>
