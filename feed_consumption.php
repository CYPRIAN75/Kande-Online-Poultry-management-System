<?php
// Start session and include database connection
session_start();
include('includes/dbconnection.php');

// Handle form submission
if(isset($_POST['submit'])) {
    $date = $_POST['date'];
    $person_in_charge = $_POST['person_in_charge'];
    $total_feed_store = $_POST['total_feed_store'];
    $feed_consumed = $_POST['feed_consumed'];
    $remaining_feed_store = $total_feed_store - $feed_consumed;

    $sql = "INSERT INTO tblFeedConsumption (date, person_in_charge, total_feed_store, feed_consumed, remaining_feed_store) VALUES (:date, :person_in_charge, :total_feed_store, :feed_consumed, :remaining_feed_store)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':person_in_charge', $person_in_charge, PDO::PARAM_STR);
    $query->bindParam(':total_feed_store', $total_feed_store, PDO::PARAM_INT);
    $query->bindParam(':feed_consumed', $feed_consumed, PDO::PARAM_INT);
    $query->bindParam(':remaining_feed_store', $remaining_feed_store, PDO::PARAM_INT);
    $query->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feed Consumption</title>
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

.container {
    width: 80%;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Form Styles */
form {
    margin-bottom: 30px;
}

form h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #28a745;
    text-align: center;
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
.form-group input[type="date"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

form button[type="submit"] {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #28a745;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button[type="submit"]:hover {
    background-color: #218838;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

table th,
table td {
    padding: 15px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.table-actions {
    display: flex;
    gap: 10px;
}

.button-edit,
.button-delete {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align: center;
    text-decoration: none;
    color: white;
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
        function calculateRemainingFeed() {
            var totalFeed = document.getElementById('total_feed_store').value;
            var feedConsumed = document.getElementById('feed_consumed').value;
            var remainingFeed = totalFeed - feedConsumed;
            document.getElementById('remaining_feed_store').value = remainingFeed;
        }
    </script>
</head>
<body>
    <a href="dashboard.php" class="btn btn-light">Go Back</a>
    <div class="container">
        <h2>Feed Consumption</h2>
        <form method="post">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="person_in_charge">Person in Charge:</label>
                <input type="text" id="person_in_charge" name="person_in_charge" required>
            </div>
            <div class="form-group">
                <label for="total_feed_store">Total Amount of Feeds in Store (kg):</label>
                <input type="number" id="total_feed_store" name="total_feed_store" required>
            </div>
            <div class="form-group">
                <label for="feed_consumed">Amount Consumed (kg):</label>
                <input type="number" id="feed_consumed" name="feed_consumed" oninput="calculateRemainingFeed()" required>
            </div>
            <div class="form-group">
                <label for="remaining_feed_store">Remaining Feeds in Store (kg):</label>
                <input type="number" id="remaining_feed_store" name="remaining_feed_store" readonly>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>

        <!-- Table to display the records -->
        <h2>Recorded Feed Consumption</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Person in Charge</th>
                    <th>Total Feeds in Store (kg)</th>
                    <th>Amount Consumed (kg)</th>
                    <th>Remaining Feeds in Store (kg)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblFeedConsumption";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if($query->rowCount() > 0) {
                    foreach($results as $row) {
                        echo "<tr>
                            <td>{$row->date}</td>
                            <td>{$row->person_in_charge}</td>
                            <td>{$row->total_feed_store}</td>
                            <td>{$row->feed_consumed}</td>
                            <td>{$row->remaining_feed_store}</td>
                            <td>
                                <a href='edit_feed_consumption.php?id={$row->id}' class='button-edit'>Edit</a>
                                <a href='delete_feed_consumption.php?id={$row->id}' class='button-delete'>Delete</a>
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


