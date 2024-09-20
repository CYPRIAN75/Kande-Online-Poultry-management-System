<?php
// Start session and include database connection
session_start();
include('includes/dbconnection.php');

// Handle form submission
if(isset($_POST['submit'])) {
    $date = $_POST['date'];
    $total_eggs = $_POST['total_eggs'];
    $spoilt_eggs = $_POST['spoilt_eggs'];
    $hatching_eggs = $_POST['hatching_eggs'];
    $sale_eggs = $total_eggs - $spoilt_eggs - $hatching_eggs;
    $price_per_egg = $_POST['price_per_egg'];
    $total_price = $sale_eggs * $price_per_egg;

    $sql = "INSERT INTO tblEggsProduced (date, total_eggs, spoilt_eggs, hatching_eggs, sale_eggs, price_per_egg, total_price) VALUES (:date, :total_eggs, :spoilt_eggs, :hatching_eggs, :sale_eggs, :price_per_egg, :total_price)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':total_eggs', $total_eggs, PDO::PARAM_INT);
    $query->bindParam(':spoilt_eggs', $spoilt_eggs, PDO::PARAM_INT);
    $query->bindParam(':hatching_eggs', $hatching_eggs, PDO::PARAM_INT);
    $query->bindParam(':sale_eggs', $sale_eggs, PDO::PARAM_INT);
    $query->bindParam(':price_per_egg', $price_per_egg, PDO::PARAM_STR);
    $query->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $query->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Eggs Produced</title>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f6;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
}

/* Form Styles */
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

form h2 {
    margin-top: 0;
    color: #4CAF50;
}

.form-group {
    margin-bottom: 15px;
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
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

form button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table-actions {
    display: flex;
    gap: 10px;
}

.button-edit {
    background-color: #ffc107;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button-edit:hover {
    background-color: #e0a800;
}

.button-delete {
    background-color: #dc3545;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button-delete:hover {
    background-color: #c82333;
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
        <h2>Eggs Produced</h2>
        <form method="post">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="total_eggs">Total Number of Eggs Produced:</label>
                <input type="number" id="total_eggs" name="total_eggs" required oninput="calculateTotalPrice()">
            </div>
            <div class="form-group">
                <label for="spoilt_eggs">Number of Eggs Spoilt/Damaged:</label>
                <input type="number" id="spoilt_eggs" name="spoilt_eggs" required oninput="calculateTotalPrice()">
            </div>
            <div class="form-group">
                <label for="hatching_eggs">Number of Eggs Left for Hatching:</label>
                <input type="number" id="hatching_eggs" name="hatching_eggs" required oninput="calculateTotalPrice()">
            </div>
            <div class="form-group">
                <label for="sale_eggs">Number of Eggs Left for Sale:</label>
                <input type="number" id="sale_eggs" name="sale_eggs" readonly>
            </div>
            <div class="form-group">
                <label for="price_per_egg">Price of Each Egg:</label>
                <input type="number" id="price_per_egg" name="price_per_egg" step="0.01" required oninput="calculateTotalPrice()">
            </div>
            <div class="form-group">
                <label for="total_price">Total Price of Eggs Left for Sale:</label>
                <input type="number" id="total_price" name="total_price" step="0.01" readonly>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>

        <!-- Table to display the records -->
        <h2>Recorded Eggs Production</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total Eggs</th>
                    <th>Spoilt Eggs</th>
                    <th>Hatching Eggs</th>
                    <th>Sale Eggs</th>
                    <th>Price per Egg</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblEggsProduced";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if($query->rowCount() > 0) {
                    foreach($results as $row) {
                        echo "<tr>
                            <td>{$row->date}</td>
                            <td>{$row->total_eggs}</td>
                            <td>{$row->spoilt_eggs}</td>
                            <td>{$row->hatching_eggs}</td>
                            <td>{$row->sale_eggs}</td>
                            <td>{$row->price_per_egg}</td>
                            <td>{$row->total_price}</td>
                            <td>
                                <div class='table-actions'>
                                    <a href='edit_eggs_produced.php?id={$row->id}' class='button-edit'>Edit</a>
                                    <a href='delete_eggs_produced.php?id={$row->id}' class='button-delete'>Delete</a>
                                </div>
                            </td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
