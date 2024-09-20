<?php
// Start session and include database connection
session_start();
include('includes/dbconnection.php');

// Check if ID parameter is set and valid
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Handle form submission
    if(isset($_POST['submit'])) {
        $medicine_category = $_POST['medicine_category'];
        $importance = $_POST['importance'];
        $use = $_POST['use'];
        $date = $_POST['date'];
        $person_in_charge = $_POST['person_in_charge'];
        $total_medicine_store = $_POST['total_medicine_store'];
        $quantity_consumed = $_POST['quantity_consumed'];
        $remaining_medicine_store = $total_medicine_store - $quantity_consumed;

        $sql = "UPDATE tblMedicineConsumed 
                SET medicine_category = :medicine_category,
                    importance = :importance,
                    use_purpose = :use_purpose,
                    date = :date,
                    person_in_charge = :person_in_charge,
                    total_medicine_store = :total_medicine_store,
                    quantity_consumed = :quantity_consumed,
                    remaining_medicine_store = :remaining_medicine_store
                WHERE id = :id";

        $query = $dbh->prepare($sql);
        $query->bindParam(':medicine_category', $medicine_category, PDO::PARAM_STR);
        $query->bindParam(':importance', $importance, PDO::PARAM_STR);
        $query->bindParam(':use_purpose', $use, PDO::PARAM_STR);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':person_in_charge', $person_in_charge, PDO::PARAM_STR);
        $query->bindParam(':total_medicine_store', $total_medicine_store, PDO::PARAM_INT);
        $query->bindParam(':quantity_consumed', $quantity_consumed, PDO::PARAM_INT);
        $query->bindParam(':remaining_medicine_store', $remaining_medicine_store, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        // Redirect to dashboard after update
        header("Location: dashboard.php");
        exit();
    }

    // Fetch existing record data
    $sql = "SELECT * FROM tblMedicineConsumed WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if(!$result) {
        // Redirect to dashboard if record not found
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Redirect to dashboard if ID parameter is missing or invalid
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Medicine Consumed</title>
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
        .form-group select,
        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            form {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Medicine Consumed</h2>
        <form method="post">
            <div class="form-group">
                <label for="medicine_category">Medicine Category:</label>
                <select id="medicine_category" name="medicine_category" required>
                    <option value="">Select Medicine Category</option>
                    <option value="Antibiotics" <?php if($result['medicine_category'] === 'Antibiotics') echo 'selected'; ?>>Antibiotics</option>
                    <option value="Anticoccidials" <?php if($result['medicine_category'] === 'Anticoccidials') echo 'selected'; ?>>Anticoccidials</option>
                    <option value="Antivirals" <?php if($result['medicine_category'] === 'Antivirals') echo 'selected'; ?>>Antivirals</option>
                    <option value="Anthelmintics (Dewormers)" <?php if($result['medicine_category'] === 'Anthelmintics (Dewormers)') echo 'selected'; ?>>Anthelmintics (Dewormers)</option>
                    <option value="Vaccines" <?php if($result['medicine_category'] === 'Vaccines') echo 'selected'; ?>>Vaccines</option>
                    <option value="Anti-inflammatory drugs" <?php if($result['medicine_category'] === 'Anti-inflammatory drugs') echo 'selected'; ?>>Anti-inflammatory drugs</option>
                    <option value="Vitamins and Supplements" <?php if($result['medicine_category'] === 'Vitamins and Supplements') echo 'selected'; ?>>Vitamins and Supplements</option>
                    <option value="Probiotics" <?php if($result['medicine_category'] === 'Probiotics') echo 'selected'; ?>>Probiotics</option>
                    <option value="Disinfectants" <?php if($result['medicine_category'] === 'Disinfectants') echo 'selected'; ?>>Disinfectants</option>
                    <option value="Growth Promoters" <?php if($result['medicine_category'] === 'Growth Promoters') echo 'selected'; ?>>Growth Promoters</option>
                </select>
            </div>
            <div class="form-group">
                <label for="importance">Importance:</label>
                <input type="text" id="importance" name="importance" value="<?php echo $result['importance']; ?>" readonly required>
            </div>
            <div class="form-group">
                <label for="use">Use:</label>
                <textarea id="use" name="use" rows="4" readonly required><?php echo $result['use_purpose']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $result['date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="person_in_charge">Person in Charge:</label>
                <input type="text" id="person_in_charge" name="person_in_charge" value="<?php echo $result['person_in_charge']; ?>" required>
            </div>
            <div class="form-group">
                <label for="total_medicine_store">Total Medicine in Store (kg):</label>
                <input type="number" id="total_medicine_store" name="total_medicine_store" value="<?php echo $result['total_medicine_store']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity_consumed">Quantity Consumed (kg):</label>
                <input type="number" id="quantity_consumed" name="quantity_consumed" value="<?php echo $result['quantity_consumed']; ?>" required>
            </div>
            <button type="submit" name="submit">Update</button>
            <a href="medicine_consumed.php" class="btn btn-light">Cancel</a>
        </form>
    </div>
</body>
</html>
